<?php

namespace Core\SRC;
abstract class Controller extends Model
{
    protected $model;
    private $controller_name;
    private $model_name;

    public function __construct($controller_name)
    {
        $this->controller_name = $controller_name;
        $this->getModelName();
        $model_class = "App\\Models\\{$this->model_name}";
        $this->model = new $model_class();
    }

    private function getModelName()
    {
        return $this->model_name = str_replace('Controller', '', end(explode("\\", $this->controller_name)));
    }
}