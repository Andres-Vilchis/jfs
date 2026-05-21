<?php

namespace App\Models;

use CodeIgniter\Model;

class PlanModel extends Model
{
    protected $table         = 'planes';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'nombre', 'descripcion', 'precio',
        'duracion_dias', 'beneficios', 'activo',
    ];

    protected $validationRules = [
        'nombre'        => 'required|min_length[2]|max_length[100]',
        'precio'        => 'required|decimal',
        'duracion_dias' => 'required|integer|greater_than[0]',
    ];
}