<?php

use App\Core\Router;

$router = new Router();

// Authentication Routes
$router->get('/login', 'AuthController', 'showLoginForm');
$router->post('/login', 'AuthController', 'login');
$router->get('/logout', 'AuthController', 'logout');

// Dashboard Route
$router->get('/dashboard', 'DashboardController', 'index');

// Event Routes
$router->get('/events', 'EventController', 'index');
$router->get('/events/create', 'EventController', 'create');
$router->post('/events', 'EventController', 'store');
$router->get('/events/{id}', 'EventController', 'show');
$router->get('/events/{id}/edit', 'EventController', 'edit');
$router->put('/events/{id}', 'EventController', 'update');
$router->delete('/events/{id}', 'EventController', 'destroy');

// User Routes
$router->get('/users', 'UserController', 'index');
$router->get('/users/create', 'UserController', 'create');
$router->post('/users', 'UserController', 'store');
$router->get('/users/{id}', 'UserController', 'show');
$router->get('/users/{id}/edit', 'UserController', 'edit');
$router->put('/users/{id}', 'UserController', 'update');
$router->delete('/users/{id}', 'UserController', 'destroy');

// API Routes
$router->get('/api/events', 'Api\EventController', 'index');
$router->post('/api/events', 'Api\EventController', 'store');
$router->get('/api/events/{id}', 'Api\EventController', 'show');
$router->put('/api/events/{id}', 'Api\EventController', 'update');
$router->delete('/api/events/{id}', 'Api\EventController', 'destroy');

// Home Route (should be last)
$router->get('/', 'HomeController', 'index');

// Handle 404 - Not Found
$router->get('*', 'ErrorController', 'notFound');
