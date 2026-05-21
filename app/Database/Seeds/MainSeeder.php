<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        $this->call('AdminSeeder');
        $this->call('PlanesSeeder');
        $this->call('TrainersSeeder');
        $this->call('ClasesSeeder');
        $this->call('ClientesSeeder');

        echo "\n🎉 Base de datos lista con datos de prueba.\n";
    }
}
