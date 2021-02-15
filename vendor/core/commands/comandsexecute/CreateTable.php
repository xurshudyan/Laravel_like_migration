<?php


namespace App\vendor\core\commands\comandsexecute;


class CreateTable
{
    public static function migrate($argument)
    {
        $file = fopen("database/" . self::camel($argument) .".php", "w");
        fwrite($file, str_replace(['CreatePosts', 'users'], [self::camel($argument), self::getTableName($argument)], '<?php

namespace App\database;

use Core\SRC\Migration;

class CreatePosts extends Migration
{
    public function up()
    {
       return $this->createTable(\'users\')
            ->id()
            ->timestamps()
            ->run();
    }

    public function down()
    {
        $this->dropIfExists(\'users\');
    }
}

        '));
    }

    public static function camel($str)
    {
        return ucfirst(lcfirst(join(array_map('ucfirst', explode('_', $str)))));
    }
    
    private static function getTableName($table)
    {
        return explode('_', $table)[1];
    }
}