<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PlanesSeeder extends Seeder
{
    public function run()
    {
        $planes = [
            [
                'nombre'        => 'Plan Mensual',
                'descripcion'   => 'Acceso ilimitado por un mes',
                'precio'        => 350.00,
                'duracion_dias' => 30,
                'beneficios'    => "Acceso ilimitado al gimnasio\nClases grupales incluidas\nUso de casillero",
                'activo'        => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'        => 'Plan Trimestral',
                'descripcion'   => 'Acceso ilimitado por tres meses',
                'precio'        => 900.00,
                'duracion_dias' => 90,
                'beneficios'    => "Acceso ilimitado al gimnasio\nClases grupales incluidas\nUso de casillero\n1 sesión con trainer",
                'activo'        => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'        => 'Plan Anual',
                'descripcion'   => 'Acceso ilimitado por un año',
                'precio'        => 2800.00,
                'duracion_dias' => 365,
                'beneficios'    => "Acceso ilimitado al gimnasio\nClases grupales incluidas\nUso de casillero\nSesiones mensuales con trainer\nDescuento en productos",
                'activo'        => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'        => 'Plan Estudiante',
                'descripcion'   => 'Tarifa especial para estudiantes',
                'precio'        => 250.00,
                'duracion_dias' => 30,
                'beneficios'    => "Acceso de lunes a viernes\nClases grupales incluidas",
                'activo'        => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('planes')->insertBatch($planes);
        echo "✅ Planes insertados.\n";
    }
}
