<?php

namespace App\Models;

use CodeIgniter\Model;

class PagoModel extends Model
{
    protected $table         = 'pagos';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'cliente_id',
        'plan_id',
        'monto',
        'fecha_pago',
        'fecha_vencimiento_generada',
        'notas',
        'registrado_por',
    ];

    protected $validationRules = [
        'cliente_id' => 'required|integer',
        'monto'      => 'required|decimal',
        'fecha_pago' => 'required|valid_date',   // ← corregido: sin formato entre corchetes
    ];

    /**
     * Historial de pagos de un cliente con nombre del plan.
     */
    public function historialCliente(int $clienteId): array
    {
        return $this->select('pagos.*, planes.nombre AS plan_nombre')
            ->join('planes', 'planes.id = pagos.plan_id', 'left')
            ->where('pagos.cliente_id', $clienteId)
            ->orderBy('pagos.fecha_pago', 'DESC')
            ->findAll();
    }
}
