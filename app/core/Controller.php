<?php

class Controller
{
    protected function view($view, $data = [])
    {
        extract($data);

        $path = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($path)) {
            die("View not found: " . $view);
        }

        require $path;
    }

    protected function model($model)
    {
        $path = __DIR__ . '/../models/' . $model . '.php';

        if (!file_exists($path)) {
            die("Model not found: " . $model);
        }

        require_once $path;
        return new $model;
    }
}
