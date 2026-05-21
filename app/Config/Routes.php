<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->get('/login',          'Auth\LoginController::index');
$routes->post('/auth/login/attempt', 'Auth\LoginController::attempt');
$routes->get('/dashboard', 'DashboardController::index',  ['as' => 'dashboard']);
$routes->get('/clientes',  'ClientesController::index',   ['as' => 'clientes.index']);
$routes->get('/trainers',  'TrainersController::index',   ['as' => 'trainers.index']);
$routes->get('/clases',    'ClasesController::index',     ['as' => 'clases.index']);
$routes->get('/usuarios',  'UsuariosController::index',   ['as' => 'usuarios.index']);

service('auth')->routes($routes);
