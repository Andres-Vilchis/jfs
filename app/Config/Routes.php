<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rutas públicas
$routes->get('/',      'Auth\LoginController::index');
$routes->get('/login', 'Auth\LoginController::index', ['as' => 'login']);
$routes->post('/auth/login/attempt', 'Auth\LoginController::attempt');
$routes->post('/logout', '\CodeIgniter\Shield\Controllers\LoginController::logoutAction', ['as' => 'logout']);


// ── Rutas protegidas: cualquier usuario autenticado ──────────────
$routes->group('', ['filter' => 'session'], function ($routes) {

    $routes->get('/dashboard', 'DashboardController::index', ['as' => 'dashboard']);

    // ── Solo admin ───────────────────────────────────────────────
    $routes->group('', ['filter' => 'group:admin'], function ($routes) {

        // Usuarios
        $routes->get('/usuarios',                    'UsuariosController::index',           ['as' => 'usuarios.index']);
        $routes->get('/usuarios/crear',              'UsuariosController::crear',           ['as' => 'usuarios.crear']);
        $routes->post('/usuarios/guardar',           'UsuariosController::guardar',         ['as' => 'usuarios.guardar']);
        $routes->get('/usuarios/editar/(:num)',      'UsuariosController::editar/$1',       ['as' => 'usuarios.editar']);
        $routes->post('/usuarios/actualizar/(:num)', 'UsuariosController::actualizar/$1',   ['as' => 'usuarios.actualizar']);
        $routes->post('/usuarios/toggle/(:num)',     'UsuariosController::toggleActivo/$1', ['as' => 'usuarios.toggle']);

        // Planes
        $routes->get('/planes',                    'PlanesController::index',           ['as' => 'planes.index']);
        $routes->get('/planes/crear',              'PlanesController::crear',           ['as' => 'planes.crear']);
        $routes->post('/planes/guardar',           'PlanesController::guardar',         ['as' => 'planes.guardar']);
        $routes->get('/planes/editar/(:num)',      'PlanesController::editar/$1',       ['as' => 'planes.editar']);
        $routes->post('/planes/actualizar/(:num)', 'PlanesController::actualizar/$1',   ['as' => 'planes.actualizar']);
        $routes->post('/planes/toggle/(:num)',     'PlanesController::toggleActivo/$1', ['as' => 'planes.toggle']);
    });

    // ── Admin y Recepcionista ─────────────────────────────────────
    $routes->group('', ['filter' => 'group:admin,recepcionista'], function ($routes) {

        // Clientes
        $routes->get('/clientes',                    'ClientesController::index',          ['as' => 'clientes.index']);
        $routes->get('/clientes/crear',              'ClientesController::crear',          ['as' => 'clientes.crear']);
        $routes->post('/clientes/guardar',           'ClientesController::guardar',        ['as' => 'clientes.guardar']);
        $routes->get('/clientes/editar/(:num)',      'ClientesController::editar/$1',      ['as' => 'clientes.editar']);
        $routes->post('/clientes/actualizar/(:num)', 'ClientesController::actualizar/$1',  ['as' => 'clientes.actualizar']);
        $routes->post('/clientes/desactivar/(:num)', 'ClientesController::desactivar/$1', ['as' => 'clientes.desactivar']);
        $routes->post('/clientes/pagar/(:num)',       'ClientesController::pagar/$1',      ['as' => 'clientes.pagar']);

        // Pagos
        $routes->get('/pagos',                      'PagosController::index',            ['as' => 'pagos.index']);
        $routes->post('/pagos/registrar/(:num)',     'PagosController::registrar/$1',     ['as' => 'pagos.registrar']);
        $routes->get('/pagos/historial/(:num)',      'PagosController::historial/$1',     ['as' => 'pagos.historial']);
    });

    // ── Admin, Recepcionista y Entrenador ─────────────────────────
    $routes->group('', ['filter' => 'group:admin,recepcionista,entrenador'], function ($routes) {

        // Clases
        $routes->get('/clases',                    'ClasesController::index',           ['as' => 'clases.index']);
        $routes->get('/clases/crear',              'ClasesController::crear',           ['as' => 'clases.crear']);
        $routes->post('/clases/guardar',           'ClasesController::guardar',         ['as' => 'clases.guardar']);
        $routes->get('/clases/editar/(:num)',      'ClasesController::editar/$1',       ['as' => 'clases.editar']);
        $routes->post('/clases/actualizar/(:num)', 'ClasesController::actualizar/$1',   ['as' => 'clases.actualizar']);
        $routes->post('/clases/toggle/(:num)',     'ClasesController::toggleActivo/$1', ['as' => 'clases.toggle']);

        // Participantes de clase
        $routes->get('/clases/(:num)/participantes',                  'ClasesController::participantes/$1',         ['as' => 'clases.participantes']);
        $routes->post('/clases/(:num)/participantes/agregar',         'ClasesController::agregarParticipante/$1',   ['as' => 'clases.agregarParticipante']);
        $routes->post('/clases/(:num)/participantes/quitar/(:num)',   'ClasesController::quitarParticipante/$1/$2', ['as' => 'clases.quitarParticipante']);

        // Trainers
        $routes->get('/trainers',                    'TrainersController::index',           ['as' => 'trainers.index']);
        $routes->get('/trainers/crear',              'TrainersController::crear',           ['as' => 'trainers.crear']);
        $routes->post('/trainers/guardar',           'TrainersController::guardar',         ['as' => 'trainers.guardar']);
        $routes->get('/trainers/editar/(:num)',      'TrainersController::editar/$1',       ['as' => 'trainers.editar']);
        $routes->post('/trainers/actualizar/(:num)', 'TrainersController::actualizar/$1',   ['as' => 'trainers.actualizar']);
        $routes->post('/trainers/toggle/(:num)',     'TrainersController::toggleActivo/$1', ['as' => 'trainers.toggle']);
    });
});

service('auth')->routes($routes);
