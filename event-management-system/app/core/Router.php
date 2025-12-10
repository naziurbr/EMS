<?php

namespace App\Core;

class Router
{
    protected $routes = [];
    protected $params = [];

    public function add($method, $route, $controller, $action)
    {
        $this->routes[$method][$route] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function get($route, $controller, $action)
    {
        $this->add('GET', $route, $controller, $action);
    }

    public function post($route, $controller, $action)
    {
        $this->add('POST', $route, $controller, $action);
    }

    public function match()
    {
        $method = Request::method();
        $url = Request::uri();

        // Remove base path if exists
        $basePath = str_replace('public', '', dirname($_SERVER['SCRIPT_NAME']));
        $url = str_replace(trim($basePath, '/'), '', $url);
        $url = trim($url, '/');

        if (empty($url)) {
            $url = '/';
        }

        if (isset($this->routes[$method][$url])) {
            $this->params = $this->routes[$method][$url];
            return true;
        }

        return false;
    }

    public function dispatch()
    {
        if ($this->match()) {
            $controller = 'App\\Controllers\\' . $this->params['controller'];
            if (class_exists($controller)) {
                $controller_object = new $controller();
                $action = $this->params['action'];
                if (method_exists($controller_object, $action)) {
                    $controller_object->$action();
                } else {
                    throw new \Exception("Method {$action} in controller {$controller} not found");
                }
            } else {
                throw new \Exception("Controller class {$controller} not found");
            }
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
    }
}
