<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $users = model(UserModel::class);

        $admin = new User([
            'username' => 'admin',
            'active'   => true,
        ]);

        // Contraseña y correo
        $admin->setPassword('Admin1234*');

        $users->save($admin);

        // Obtener el usuario guardado
        $admin = $users->findById($users->getInsertID());

        // Asignar correo
        $admin->createEmailIdentity([
            'email'    => 'andresvilchis@gmail.com',
            'password' => 'Admin1234*',
        ]);

        // Asignar rol administrador
        $admin->addGroup('admin');

        echo "Usuario administrador creado correctamente.\n";
        echo "   Email:    andresvilchis@gmail.com\n";
        echo "   Password: Admin1234*\n";
    }
}