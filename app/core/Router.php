<?php

class Router
{
    public function dispatch()
    {
        $url = $_GET['url'] ?? 'auth/login';
        $url = explode('/', trim($url, '/'));

        $controllerName = ucfirst($url[0]) . 'Controller';
        $method = $url[1] ?? 'login';

        $controllerPath = __DIR__ . '/../controllers/' . $controllerName . '.php';

        if (!file_exists($controllerPath)) {
            die("Controller not found: $controllerName");
        }

        // ✅ IMPORTANT: require_once
        require_once $controllerPath;

        if (!class_exists($controllerName)) {
            die("Controller class not found: $controllerName");
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $method)) {
            die("Method not found: $method");
        }

        $controller->$method();
    }
}
