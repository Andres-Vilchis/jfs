<?php

namespace App\Models;

use CodeIgniter\Model;

class ClaseModel extends Model
{
    protected $table         = 'clases';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'nombre', 'descripcion', 'trainer_id', 'nivel',
        'capacidad_max', 'hora_inicio', 'hora_fin',
        'dias_semana', 'salon', 'activo',
    ];

    protected $validationRules = [
        'nombre'        => 'required|min_length[2]|max_length[100]',
        'hora_inicio'   => 'required',
        'hora_fin'      => 'required',
        'dias_semana'   => 'required',
        'capacidad_max' => 'required|integer|greater_than[0]',
        'nivel'         => 'required|in_list[principiante,intermedio,avanzado]',
    ];

    // Clases con nombre del trainer
    public function conTrainer(): array
    {
        return $this->db->table('clases c')
            ->select('c.*, CONCAT(t.nombre, " ", t.apellidos) AS trainer_nombre')
            ->join('trainers t', 't.id = c.trainer_id', 'left')
            ->where('c.activo', 1)
            ->orderBy('c.hora_inicio', 'ASC')
            ->get()->getResultArray();
    }

    // Clases de hoy
    public function hoy(): array
    {
        $dias = [
            1 => 'lun', 2 => 'mar', 3 => 'mie',
            4 => 'jue', 5 => 'vie', 6 => 'sab', 0 => 'dom',
        ];
        $hoy = $dias[date('w')];

        return $this->db->table('clases c')
            ->select('c.*, CONCAT(t.nombre, " ", t.apellidos) AS trainer_nombre')
            ->join('trainers t', 't.id = c.trainer_id', 'left')
            ->where('c.activo', 1)
            ->like('c.dias_semana', $hoy)
            ->orderBy('c.hora_inicio', 'ASC')
            ->get()->getResultArray();
    }
}