<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->get('/login',          'Auth\LoginController::index');
$routes->post('/auth/login/attempt', 'Auth\LoginController::attempt');
$routes->get('/dashboard', 'DashboardController::index',  ['as' => 'dashboard']);
$routes->get('/trainers',  'TrainersController::index',   ['as' => 'trainers.index']);
$routes->get('/clases',    'ClasesController::index',     ['as' => 'clases.index']);
$routes->get('/usuarios',  'UsuariosController::index',   ['as' => 'usuarios.index']);

// Clientes
$routes->get('/clientes',              'ClientesController::index',      ['as' => 'clientes.index']);
$routes->get('/clientes/crear',        'ClientesController::crear',      ['as' => 'clientes.crear']);
$routes->post('/clientes/guardar',     'ClientesController::guardar',    ['as' => 'clientes.guardar']);
$routes->get('/clientes/editar/(:num)', 'ClientesController::editar/$1', ['as' => 'clientes.editar']);
$routes->post('/clientes/actualizar/(:num)', 'ClientesController::actualizar/$1', ['as' => 'clientes.actualizar']);
$routes->post('/clientes/desactivar/(:num)', 'ClientesController::desactivar/$1', ['as' => 'clientes.desactivar']);

// Planes
$routes->get('/planes',                    'PlanesController::index',       ['as' => 'planes.index']);
$routes->get('/planes/crear',              'PlanesController::crear',       ['as' => 'planes.crear']);
$routes->post('/planes/guardar',           'PlanesController::guardar',     ['as' => 'planes.guardar']);
$routes->get('/planes/editar/(:num)',      'PlanesController::editar/$1',   ['as' => 'planes.editar']);
$routes->post('/planes/actualizar/(:num)', 'PlanesController::actualizar/$1', ['as' => 'planes.actualizar']);
$routes->post('/planes/toggle/(:num)',     'PlanesController::toggleActivo/$1', ['as' => 'planes.toggle']);

service('auth')->routes($routes);
