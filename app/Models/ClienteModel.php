<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table            = 'clientes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'nombre', 'apellidos', 'correo', 'telefono',
        'fecha_nacimiento', 'genero', 'fecha_registro',
        'plan_id', 'fecha_vencimiento', 'nivel',
        'foto', 'notas', 'activo',
    ];

    protected $validationRules = [
        'nombre'   => 'required|min_length[2]|max_length[100]',
        'apellidos'=> 'required|min_length[2]|max_length[100]',
        'correo'   => 'permit_empty|valid_email|max_length[150]',
        'telefono' => 'permit_empty|max_length[20]',
        'genero'   => 'permit_empty|in_list[masculino,femenino,otro]',
        'nivel'    => 'required|in_list[principiante,intermedio,avanzado]',
    ];

    protected $validationMessages = [
        'nombre'   => ['required' => 'El nombre es obligatorio.'],
        'apellidos'=> ['required' => 'Los apellidos son obligatorios.'],
        'correo'   => ['valid_email' => 'El correo no es válido.'],
    ];

    // Clientes activos con datos de su plan
    public function conPlan()
    {
        return $this->select('clientes.*, planes.nombre AS plan_nombre')
                    ->join('planes', 'planes.id = clientes.plan_id', 'left')
                    ->where('clientes.activo', 1)
                    ->orderBy('clientes.created_at', 'DESC');
    }

    // Últimos N clientes registrados
    public function ultimos(int $limit = 5): array
    {
        return $this->conPlan()->limit($limit)->findAll();
    }
}