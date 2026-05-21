<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->get('/login',          'Auth\LoginController::index');
$routes->post('/auth/login/attempt', 'Auth\LoginController::attempt');
$routes->get('/dashboard', 'DashboardController::index', ['as' => 'dashboard']);

service('auth')->routes($routes);
