<?php

namespace Core\SRC;
interface DBMethods
{
    /**
     * @brief for selecting data from database
     * @return mixed
     */
    public function select();

    /**
     * @brief for inserting data into database
     * @return mixed
     */
    public function insert();

    public function update();

    public function delete();

    public function where($field, $operator, $value);

    public function andWhere($field, $operator, $value);

    public function orWhere($field, $operator, $value);

    public function from($table);

    public function limit($limit);

    public function orderBy($column, $sort);

    public function offset($offset);

    public function distinct();

    public function count($product_name);

    public function run();

    public function exists($table, $column, $value);

    public function join($table, $condition);
}