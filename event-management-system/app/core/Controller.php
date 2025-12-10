<?php

namespace App\Core;

use App\Core\View;
use App\Core\Session;
use App\Core\Request;
use App\Core\Response;

class Controller {
    protected $view;
    protected $session;
    protected $request;
    protected $response;
    protected $middleware = [];

    public function __construct() {
        $this->view = new View();
        $this->session = new Session();
        $this->request = new Request();
        $this->response = new Response();
    }

    protected function middleware($middleware) {
        $this->middleware[] = $middleware;
    }

    protected function executeMiddleware() {
        foreach ($this->middleware as $middleware) {
            $middleware = new $middleware();
            if (!$middleware->handle($this->request, $this->response)) {
                $this->response->send();
                exit;
            }
        }
    }

    protected function render($view, $data = []) {
        $this->view->render($view, $data);
    }

    protected function json($data, $statusCode = 200) {
        $this->response->setStatusCode($statusCode);
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setContent(json_encode($data));
        $this->response->send();
    }

    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
}
