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
        'nombre',
        'apellidos',
        'correo',
        'telefono',
        'fecha_nacimiento',
        'genero',
        'fecha_registro',
        'plan_id',
        'fecha_vencimiento',
        'nivel',
        'foto',
        'notas',
        'activo',
    ];

    protected $validationRules = [
        'nombre'    => 'required|min_length[2]|max_length[100]',
        'apellidos' => 'required|min_length[2]|max_length[100]',
        'correo'    => 'permit_empty|valid_email|max_length[150]',
        'telefono'  => 'permit_empty|max_length[20]',
        'genero'    => 'permit_empty|in_list[masculino,femenino,otro]',
        'nivel'     => 'required|in_list[principiante,intermedio,avanzado]',
    ];

    protected $validationMessages = [
        'nombre'    => ['required' => 'El nombre es obligatorio.'],
        'apellidos' => ['required' => 'Los apellidos son obligatorios.'],
        'correo'    => ['valid_email' => 'El correo no es válido.'],
    ];

    // Clientes activos con datos de su plan
    public function conPlan()
    {
        return $this->select('clientes.*, planes.nombre AS plan_nombre, planes.duracion_dias')
            ->join('planes', 'planes.id = clientes.plan_id', 'left')
            ->where('clientes.activo', 1)
            ->orderBy('clientes.created_at', 'DESC');
    }

    // Clientes activos con plan + días de clases inscritas
    public function conPlanYClases(): array
    {
        $clientes = $this->conPlan()->findAll();

        $ordenDias = ['lun', 'mar', 'mie', 'jue', 'vie', 'sab', 'dom'];

        foreach ($clientes as &$c) {
            $rows = $this->db
                ->table('clientes_clases cc')
                ->select('cl.dias_semana')
                ->join('clases cl', 'cl.id = cc.clase_id')
                ->where('cc.cliente_id', $c['id'])
                ->where('cc.activo', 1)
                ->where('cl.activo', 1)
                ->get()
                ->getResultArray();

            // Recopilar días únicos de todas las clases del cliente
            $dias = [];
            foreach ($rows as $row) {
                foreach (explode(',', $row['dias_semana']) as $dia) {
                    $d = trim($dia);
                    if ($d !== '' && ! in_array($d, $dias)) {
                        $dias[] = $d;
                    }
                }
            }

            // Ordenar según día de la semana
            usort(
                $dias,
                fn($a, $b) =>
                array_search($a, $ordenDias) - array_search($b, $ordenDias)
            );

            $c['dias_clases'] = $dias;
        }

        return $clientes;
    }

    // Últimos N clientes registrados
    public function ultimos(int $limit = 5): array
    {
        return $this->conPlan()->limit($limit)->findAll();
    }
}
