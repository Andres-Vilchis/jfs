<?php

namespace App\Models;

use CodeIgniter\Model;

class TrainerModel extends Model
{
    protected $table         = 'trainers';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'nombre',
        'apellidos',
        'correo',
        'telefono',
        'nivel',
        'especialidad',
        'foto',
        'activo',
    ];

    protected $validationRules = [
        'nombre'    => 'required|min_length[2]|max_length[100]',
        'apellidos' => 'required|min_length[2]|max_length[100]',
        'correo'    => 'permit_empty|valid_email|max_length[150]',
        'nivel'     => 'required|in_list[principiante,intermedio,avanzado]',
    ];

    protected $validationMessages = [
        'nombre'    => ['required' => 'El nombre es obligatorio.'],
        'apellidos' => ['required' => 'Los apellidos son obligatorios.'],
        'correo'    => ['valid_email' => 'El correo no es válido.'],
    ];

    // Trainers activos con conteo de clases asignadas
    public function conClases(): array
    {
        return $this->db->table('trainers t')
            ->select('t.*, COUNT(tc.clase_id) AS total_clases')
            ->join('trainer_clases tc', 'tc.trainer_id = t.id', 'left')
            ->where('t.activo', 1)
            ->groupBy('t.id')
            ->orderBy('t.nombre', 'ASC')
            ->get()->getResultArray();
    }
}
