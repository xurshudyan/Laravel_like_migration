<?php
namespace App\Config;
class Config
{
    public static $host = 'localhost';

    public static $dbname = 'your_dbname';

    public static $username = 'root';

    public static $password = '';

    public static $options = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
    ];
}