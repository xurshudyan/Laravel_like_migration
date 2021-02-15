<?php

namespace Core\SRC;

use App\Config\Config;

abstract class MySql extends DBManager
{
    protected $sql;
    protected $pdo;
    protected $table;
    protected $fields = [];
    protected $value = [];

    public function __construct()
    {
        $dsn = "mysql:host=" . Config::$host;
        $this->pdo = new \PDO($dsn, Config::$username, Config::$password);

        $this->pdo->prepare("CREATE DATABASE IF NOT EXISTS " . Config::$dbname)->execute();
        $this->pdo = new \PDO("mysql:host=" . Config::$host . ";dbname=" . Config::$dbname, Config::$username, Config::$password, Config::$options);
        return $this->pdo;
    }

    protected function query($sql)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->value);
        return $stmt;
    }

    public function close()
    {
        $this->pdo = null;
    }
}