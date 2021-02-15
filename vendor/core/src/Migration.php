<?php

namespace Core\SRC;

use Core\SRC\MySql;
use MongoDB\BSON\Type;
use Core\SRC\Helper;

class Migration extends MySql
{
    use Helper;

    private $column;

    protected function createTable($table)
    {
        $this->sql = "CREATE TABLE IF NOT EXISTS {$table} (";
        return $this;
    }

    private function addColumn($column, $type)
    {
        $this->sql .= ", $column $type ";
        return $this;
    }

    protected function boolean($column)
    {
        return $this->addColumn($column, 'BOOLEAN NOT NULL');
    }

    protected function string($column, $length = 255)
    {
        return $this->addColumn($column, "VARCHAR ($length) NOT NULL");
    }

    protected function id()
    {
        return $this->addColumn('id', 'INT (11) AUTO_INCREMENT PRIMARY KEY');
    }

    protected function int($column, $length = 11)
    {
        return $this->addColumn($column, "INT ($length) NOT NULL");
    }

    protected function text($column)
    {
        return $this->addColumn($column_name, "TEXT NOT NULL");
    }

    protected function rememberToken()
    {
        return $this->addColumn('remember_token', 'VARCHAR (100)');
    }

    protected function timestamps()
    {
        $this->addColumn('created_at', 'timestamp default current_timestamp');
        return $this->addColumn('updated_at', 'timestamp default current_timestamp');
    }

    protected function date($column)
    {
        return $this->addColumn($column, 'DATE');
    }

    protected function enum($column, $args = [])
    {
        $arguments = join(', ', array_map(function ($value) {
            return "'" . $value . "'";
        }, $args));
        $this->sql .= ", $column ENUM ($arguments) NOT NULL";
        return $this;
    }

    protected function nullable()
    {
        $this->sql = str_replace('NOT NULL', 'DEFAULT NULL', $this->sql);
        return $this;
    }

    protected function autoIncrement()
    {
        $this->sql .= "AUTO_INCREMENT ";
        return $this;
    }

    protected function uniq()
    {
        $this->sql .= "UNIQUE";
        return $this;
    }

    protected function primary()
    {
        $this->sql .= "PRIMARY KEY ";
        return $this;
    }

    protected function dropIfExists($table)
    {
        $this->pdo->query("DROP TABLE IF EXISTS $table");
    }

    protected function toSql()
    {
        return $this->sql;
    }

    protected function run()
    {
        return $this->pdo->query(preg_replace('/,/', '', $this->sql . ')', 1));
    }

    protected function default($value)
    {
        $this->sql .= "default $value";
        return $this;
    }

    protected function renameColumn($table, $old_column, $new_column)
    {
        $type = $this->pdo->query("DESCRIBE users $old_column")->fetch(2)['Type'];
        $sql = "ALTER TABLE $table CHANGE $old_column $new_column $type";
        try {
            $this->pdo->query($sql);
        } catch (\PDOException $e) {
            echo 'Column already changed!';
        }
    }

    public function addToMigration($tables)
    {
        $step = end($this->pdo->query('select step from migration')->fetchAll(2));
        $step = $step['step'] + 1;

        foreach ($tables as $table) {
            $table = "'" . $table . "'";
            $this->pdo->query("insert ignore into migration (name, step) values ($table, $step)");
        }
    }

    public function migrate()
    {
        $base_dir = dirname(dirname(dirname(dirname(__FILE__))));
        try {
            $this->pdo->beginTransaction();
            $this->createTable('migration')
                ->id()
                ->string('name')->uniq()
                ->int('step')->default(0)
                ->timestamps()
                ->run();
            $this->pdo->commit();
        } catch (\PDOException | \Exception $exception) {
            $this->pdo->rollBack();
            return $exception->getMessage();
        }
        $files = $this->dirToArray($base_dir . '/database');

        try {
//            system('composer dump-autoload');
            $this->addToMigration($files);
            foreach ($files as $file) {
                echo 'Migrated ' . $file . "\n";
                $migrate_class = "App\database\\" . substr($file, 0, -4);
                $migrate = new $migrate_class();
                $migrate->up();
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}

