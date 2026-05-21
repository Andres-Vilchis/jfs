<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClientesSeeder extends Seeder
{
    public function run()
    {
        $planes = $this->db->table('planes')->get()->getResultArray();
        $planIds = array_column($planes, 'id');

        $clientes = [
            ['nombre' => 'Ana',      'apellidos' => 'Torres Vega',      'correo' => 'ana.torres@email.com',     'telefono' => '5551110001', 'genero' => 'femenino',   'nivel' => 'principiante', 'plan_idx' => 0, 'dias' => 20],
            ['nombre' => 'Roberto',  'apellidos' => 'Sánchez Lima',     'correo' => 'roberto.s@email.com',      'telefono' => '5551110002', 'genero' => 'masculino',  'nivel' => 'intermedio',   'plan_idx' => 1, 'dias' => 60],
            ['nombre' => 'Daniela',  'apellidos' => 'Flores Ortiz',     'correo' => 'daniela.f@email.com',      'telefono' => '5551110003', 'genero' => 'femenino',   'nivel' => 'avanzado',     'plan_idx' => 2, 'dias' => 200],
            ['nombre' => 'Jorge',    'apellidos' => 'Martínez Ruiz',    'correo' => 'jorge.m@email.com',        'telefono' => '5551110004', 'genero' => 'masculino',  'nivel' => 'principiante', 'plan_idx' => 3, 'dias' => 10],
            ['nombre' => 'Valeria',  'apellidos' => 'Gómez Castro',     'correo' => 'valeria.g@email.com',      'telefono' => '5551110005', 'genero' => 'femenino',   'nivel' => 'intermedio',   'plan_idx' => 0, 'dias' => -5],
            ['nombre' => 'Fernando', 'apellidos' => 'Díaz Morales',     'correo' => 'fernando.d@email.com',     'telefono' => '5551110006', 'genero' => 'masculino',  'nivel' => 'avanzado',     'plan_idx' => 1, 'dias' => 45],
            ['nombre' => 'Gabriela', 'apellidos' => 'Pérez Núñez',      'correo' => 'gabriela.p@email.com',     'telefono' => '5551110007', 'genero' => 'femenino',   'nivel' => 'principiante', 'plan_idx' => 2, 'dias' => 180],
            ['nombre' => 'Héctor',   'apellidos' => 'Reyes Blanco',     'correo' => 'hector.r@email.com',       'telefono' => '5551110008', 'genero' => 'masculino',  'nivel' => 'intermedio',   'plan_idx' => 3, 'dias' => -15],
            ['nombre' => 'Paola',    'apellidos' => 'Jiménez Salinas',  'correo' => 'paola.j@email.com',        'telefono' => '5551110009', 'genero' => 'femenino',   'nivel' => 'avanzado',     'plan_idx' => 0, 'dias' => 90],
            ['nombre' => 'Luis',     'apellidos' => 'Herrera Campos',   'correo' => 'luis.h@email.com',         'telefono' => '5551110010', 'genero' => 'masculino',  'nivel' => 'principiante', 'plan_idx' => 1, 'dias' => 3],
            ['nombre' => 'Carmen',   'apellidos' => 'Vargas Espinoza',  'correo' => 'carmen.v@email.com',       'telefono' => '5551110011', 'genero' => 'femenino',   'nivel' => 'intermedio',   'plan_idx' => 2, 'dias' => 120],
            ['nombre' => 'Alejandro', 'apellidos' => 'Cruz Mendoza',     'correo' => 'alejandro.c@email.com',    'telefono' => '5551110012', 'genero' => 'masculino',  'nivel' => 'avanzado',     'plan_idx' => 3, 'dias' => -2],
        ];

        $rows = [];
        foreach ($clientes as $c) {
            $planId     = $planIds[$c['plan_idx']] ?? null;
            $vencimiento = date('Y-m-d', strtotime("+{$c['dias']} days"));
            $rows[] = [
                'nombre'           => $c['nombre'],
                'apellidos'        => $c['apellidos'],
                'correo'           => $c['correo'],
                'telefono'         => $c['telefono'],
                'genero'           => $c['genero'],
                'nivel'            => $c['nivel'],
                'fecha_registro'   => date('Y-m-d'),
                'plan_id'          => $planId,
                'fecha_vencimiento' => $vencimiento,
                'activo'           => 1,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('clientes')->insertBatch($rows);
        echo "✅ Clientes insertados.\n";
    }
}
