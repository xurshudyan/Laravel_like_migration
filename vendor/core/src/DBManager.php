<?php
namespace Core\SRC;
abstract class DBManager
{
    /**
     * @brief for database querying
     * @param $sql type of sql string
     * @return mixed
     */
    protected abstract function __construct();

    protected abstract function query($sql);

    protected abstract function close();
}