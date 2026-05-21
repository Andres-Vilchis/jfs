<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TrainersSeeder extends Seeder
{
    public function run()
    {
        $trainers = [
            [
                'nombre'       => 'Carlos',
                'apellidos'    => 'Mendoza Ríos',
                'correo'       => 'carlos.mendoza@jfs.com',
                'telefono'     => '5551234567',
                'nivel'        => 'avanzado',
                'especialidad' => 'Crossfit y Funcional',
                'activo'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'       => 'Sofía',
                'apellidos'    => 'Ramírez Torres',
                'correo'       => 'sofia.ramirez@jfs.com',
                'telefono'     => '5559876543',
                'nivel'        => 'avanzado',
                'especialidad' => 'Yoga y Pilates',
                'activo'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'       => 'Miguel',
                'apellidos'    => 'López Herrera',
                'correo'       => 'miguel.lopez@jfs.com',
                'telefono'     => '5554561230',
                'nivel'        => 'intermedio',
                'especialidad' => 'Spinning y Cardio',
                'activo'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'       => 'Laura',
                'apellidos'    => 'García Vega',
                'correo'       => 'laura.garcia@jfs.com',
                'telefono'     => '5557890123',
                'nivel'        => 'intermedio',
                'especialidad' => 'Zumba y Baile',
                'activo'       => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('trainers')->insertBatch($trainers);
        echo "✅ Trainers insertados.\n";
    }
}