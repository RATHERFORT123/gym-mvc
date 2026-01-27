<?php

class Controller
{
    protected function view($view, $data = [])
    {
        extract($data);
        require "../app/views/$view.php";
    }

    protected function model($model)
    {
        require_once "../app/models/$model.php";
        return new $model;
    }
}
