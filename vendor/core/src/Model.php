<?php
namespace Core\SRC;
use App\Config\Config;
use Core\SRC\MySql;
use Core\SRC\DBMethods;

abstract class Model extends MySql implements DBMethods
{
    public function __construct()
    {
        parent::__construct();
    }

    public function from($table)
    {
        $this->table = $table;
        return $this;
    }

    public function select()
    {
        if (empty(func_get_args())) {
            $this->sql = 'SELECT * FROM ' . $this->table;
        } else {
            $this->sql = "SELECT " . implode(', ', func_get_args()) . " FROM {$this->table}";
        }
        return $this;
    }

    public function insert($fields = [], $value = [])
    {
        $placeholders = implode(', ', array_fill(0, count($value), '?'));
        $this->fields = $fields;
        $this->value = $value;

        $field = '`' . implode('`, `', $fields) . '`';
        $this->sql = "INSERT INTO {$this->table} ( {$field} ) VALUES ( {$placeholders} )";
        return $this->query($this->sql) ? true : false;
    }

    public function update($fields = [])
    {
        $str = '';
        foreach (array_keys($fields) as $field) {
            $str .= $field . ' = ?, ';
        }
        $str = rtrim($str, ', ');
        $this->sql = "UPDATE {$this->table} SET {$str} ";
        $this->value = array_values($fields);
        return $this;
    }

    public function get($mode = \PDO::FETCH_ASSOC)
    {
        return $this->query($this->sql)->fetch($mode);
    }

    public function run()
    {
        return $this->query($this->sql) ? true : false;
    }

    public function getAll($mode = \PDO::FETCH_ASSOC)
    {
        return $this->query($this->sql)->fetchAll($mode);
    }

    public function where($field, $operator, $value)
    {
        $this->sql .= " where {$field} {$operator} ?";
        $this->value[] = $value;
        return $this;
    }

    public function andWhere($field, $operator, $value)
    {
        $this->sql .= " AND {$field} {$operator} ?";
        $this->value[] = $value;
        return $this;
    }

    public function orWhere($field, $operator, $value)
    {
        $this->sql .= " OR {$field} {$operator} ?";
        $this->value[] = $value;
        return $this;
    }

    public function limit($limit)
    {
        $this->sql .= " LIMIT {$limit}";
        return $this;
    }

    public function orderBy($column, $sort = 'asc')
    {
        $this->sql .= " ORDER BY {$column} {$sort}";
        return $this;
    }

    public function offset($offset)
    {
        $this->sql .= " OFFSET {$offset}";
        return $this;
    }

    public function delete()
    {
        $this->sql = "DELETE FROM {$this->table} ";
        return $this;
    }

    public function distinct()
    {
        $this->sql = preg_replace('/ /', ' DISTINCT ', $this->sql, 1);
        return $this;
    }

    public function count($product_name)
    {
        return $this->pdo->query("select count($product_name) as count from {$this->table}")->fetch(2)['count'];
    }
    
    public function exists($table, $column, $value)
    {
        return !empty($this->from($table)->select($column)->where($column, '=', $value)->get());
    }

    public function join($table, $condition)
    {
        $this->sql .= " INNER JOIN {$table} ON {$condition}";
        return $this;
    }

    public function leftJoin($table, $condition)
    {
        $this->sql .= " LEFT JOIN {$table} ON {$condition}";
        return $this;
    }

    public function rightJoin($table, $condition)
    {
        $this->sql .= " RIGHT JOIN {$table} ON {$condition}";
        return $this;
    }

    protected function dropIfExists($table)
    {
        $this->pdo->query("DROP TABLE IF EXISTS $table");
    }

}