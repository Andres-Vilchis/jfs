<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClasesSeeder extends Seeder
{
    public function run()
    {
        // Obtiene IDs de trainers
        $trainers = $this->db->table('trainers')->get()->getResultArray();
        $ids = array_column($trainers, 'id', 'nombre');

        $clases = [
            [
                'nombre'        => 'Crossfit Matutino',
                'descripcion'   => 'Entrenamiento funcional de alta intensidad',
                'trainer_id'    => $ids['Carlos'] ?? null,
                'nivel'         => 'avanzado',
                'capacidad_max' => 15,
                'hora_inicio'   => '07:00:00',
                'hora_fin'      => '08:00:00',
                'dias_semana'   => 'lun,mie,vie',
                'salon'         => 'Sala A',
                'activo'        => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'        => 'Yoga Relajante',
                'descripcion'   => 'Clase de yoga para todos los niveles',
                'trainer_id'    => $ids['Sofía'] ?? null,
                'nivel'         => 'principiante',
                'capacidad_max' => 20,
                'hora_inicio'   => '09:00:00',
                'hora_fin'      => '10:00:00',
                'dias_semana'   => 'lun,mar,mie,jue,vie',
                'salon'         => 'Sala B',
                'activo'        => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'        => 'Spinning',
                'descripcion'   => 'Ciclismo indoor de alta intensidad',
                'trainer_id'    => $ids['Miguel'] ?? null,
                'nivel'         => 'intermedio',
                'capacidad_max' => 18,
                'hora_inicio'   => '18:00:00',
                'hora_fin'      => '19:00:00',
                'dias_semana'   => 'lun,mie,vie',
                'salon'         => 'Sala Spinning',
                'activo'        => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'        => 'Zumba',
                'descripcion'   => 'Baile y cardio al ritmo de la música',
                'trainer_id'    => $ids['Laura'] ?? null,
                'nivel'         => 'principiante',
                'capacidad_max' => 25,
                'hora_inicio'   => '19:00:00',
                'hora_fin'      => '20:00:00',
                'dias_semana'   => 'mar,jue,sab',
                'salon'         => 'Sala Principal',
                'activo'        => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nombre'        => 'Pilates',
                'descripcion'   => 'Fortalecimiento y flexibilidad',
                'trainer_id'    => $ids['Sofía'] ?? null,
                'nivel'         => 'intermedio',
                'capacidad_max' => 12,
                'hora_inicio'   => '11:00:00',
                'hora_fin'      => '12:00:00',
                'dias_semana'   => 'mar,jue',
                'salon'         => 'Sala B',
                'activo'        => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('clases')->insertBatch($clases);
        echo "✅ Clases insertadas.\n";
    }
}
