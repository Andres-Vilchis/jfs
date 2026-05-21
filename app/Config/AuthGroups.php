<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    public string $defaultGroup = 'user';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * An associative array of the available groups in the system, where the keys
     * are the group names and the values are arrays of the group info.
     *
     * Whatever value you assign as the key will be used to refer to the group
     * when using functions such as:
     *      $user->addGroup('superadmin');
     *
     * @var array<string, array<string, string>>
     *
     * @see https://codeigniter4.github.io/shield/quick_start_guide/using_authorization/#change-available-groups for more info
     */
    public array $groups = [
        'admin' => [
            'title'       => 'Administrador',
            'description' => 'Control total del sistema',
        ],
        'recepcionista' => [
            'title'       => 'Recepcionista',
            'description' => 'Gestión de clientes, pagos y asistencia',
        ],
        'entrenador' => [
            'title'       => 'Entrenador',
            'description' => 'Ver clientes asignados y rutinas',
        ],
        'cliente' => [
            'title'       => 'Cliente',
            'description' => 'Ver su perfil, rutinas y pagos',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system.
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissions = [
        // Usuarios del sistema
        'usuarios.crear'    => 'Crear usuarios',
        'usuarios.editar'   => 'Editar usuarios',
        'usuarios.eliminar' => 'Eliminar usuarios',
        'usuarios.ver'      => 'Ver usuarios',

        // Clientes del gimnasio
        'clientes.crear'    => 'Registrar nuevos clientes',
        'clientes.editar'   => 'Editar datos de clientes',
        'clientes.eliminar' => 'Eliminar clientes',
        'clientes.ver'      => 'Ver listado de clientes',

        // Membresías
        'membresias.crear'    => 'Crear membresías',
        'membresias.editar'   => 'Editar membresías',
        'membresias.eliminar' => 'Eliminar membresías',
        'membresias.ver'      => 'Ver membresías',

        // Pagos
        'pagos.crear'    => 'Registrar pagos',
        'pagos.ver'      => 'Ver historial de pagos',
        'pagos.eliminar' => 'Eliminar pagos',

        // Rutinas
        'rutinas.crear'    => 'Crear rutinas',
        'rutinas.editar'   => 'Editar rutinas',
        'rutinas.ver'      => 'Ver rutinas',

        // Reportes
        'reportes.ver' => 'Ver reportes y estadísticas',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     *
     * This defines group-level permissions.
     */
    public array $matrix = [
        'admin' => [
            'usuarios.*',
            'clientes.*',
            'membresias.*',
            'pagos.*',
            'rutinas.*',
            'reportes.*',
        ],
        'recepcionista' => [
            'clientes.crear',
            'clientes.editar',
            'clientes.ver',
            'membresias.ver',
            'membresias.crear',
            'pagos.crear',
            'pagos.ver',
        ],
        'entrenador' => [
            'clientes.ver',
            'rutinas.crear',
            'rutinas.editar',
            'rutinas.ver',
        ],
        'cliente' => [
            'rutinas.ver',
            'pagos.ver',
            'membresias.ver',
        ],
    ];
}
