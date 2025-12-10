<?php

namespace App\Core;

class App {
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];
    protected $db;

    public function __construct() {
        $url = $this->parseUrl();
        
        // Set controller
        if (isset($url[0]) && file_exists('../app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        }
        
        require_once '../app/controllers/' . $this->controller . 'Controller.php';
        $controllerClass = "App\\Controllers\\" . $this->controller . 'Controller';
        $this->controller = new $controllerClass();

        // Set method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Set parameters
        $this->params = $url ? array_values($url) : [];
    }

    public function run() {
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
