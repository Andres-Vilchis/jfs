<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\PlanModel;
use App\Models\PagoModel;

class PagosController extends BaseController
{
    protected ClienteModel $clienteModel;
    protected PlanModel    $planModel;
    protected PagoModel    $pagoModel;

    public function __construct()
    {
        $this->clienteModel = new ClienteModel();
        $this->planModel    = new PlanModel();
        $this->pagoModel    = new PagoModel();
    }

    /**
     * Vista principal: clientes activos ordenados del pago más reciente al más antiguo.
     * Clientes sin pago registrado aparecen al final.
     * GET /pagos
     */
    public function index()
    {
        $clientes = $this->clienteModel
            ->select('clientes.*, planes.nombre AS plan_nombre, planes.precio AS plan_precio, planes.duracion_dias')
            ->join('planes', 'planes.id = clientes.plan_id', 'left')
            ->where('clientes.activo', 1)
            ->orderBy('clientes.ultimo_pago IS NULL', 'ASC')  // NULLs al final
            ->orderBy('clientes.ultimo_pago', 'DESC')         // más reciente primero
            ->findAll();

        $data = [
            'clientes' => $clientes,
            'planes'   => $this->planModel->where('activo', 1)->findAll(),
        ];

        return view('pagos/index', $data);
    }

    /**
     * Registrar un pago para un cliente.
     * POST /pagos/registrar/:id
     */
    public function registrar(int $clienteId)
    {
        $cliente = $this->clienteModel->find($clienteId);
        if (! $cliente) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'fecha_pago' => 'required|valid_date',
            'monto'      => 'required|decimal',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors());
        }

        $fechaPago = $this->request->getPost('fecha_pago');
        $planId    = $cliente['plan_id'];
        $monto     = $this->request->getPost('monto');

        // Calcular nueva fecha de vencimiento según duración del plan
        $nuevaFechaVenc = null;
        if ($planId) {
            $plan = $this->planModel->find($planId);
            if ($plan && (int) $plan['duracion_dias'] > 1) {
                // Si ya tiene vencimiento futuro, sumar desde ahí; si no, desde la fecha de pago
                $base = ($cliente['fecha_vencimiento'] && $cliente['fecha_vencimiento'] >= $fechaPago)
                    ? $cliente['fecha_vencimiento']
                    : $fechaPago;
                $nuevaFechaVenc = date('Y-m-d', strtotime($base . ' +' . $plan['duracion_dias'] . ' days'));
            } elseif ($plan && (int) $plan['duracion_dias'] === 1) {
                // Plan por clase: acceso solo el día del pago
                $nuevaFechaVenc = $fechaPago;
            }
        }

        // Guardar en historial de pagos
        $this->pagoModel->save([
            'cliente_id'                 => $clienteId,
            'plan_id'                    => $planId,
            'monto'                      => $monto,
            'fecha_pago'                 => $fechaPago,
            'fecha_vencimiento_generada' => $nuevaFechaVenc,
            'notas'                      => $this->request->getPost('notas'),
            'registrado_por'             => auth()->id(),
        ]);

        // Actualizar cliente
        $updateData = ['ultimo_pago' => $fechaPago];
        if ($nuevaFechaVenc) {
            $updateData['fecha_vencimiento'] = $nuevaFechaVenc;
        }
        $this->clienteModel->update($clienteId, $updateData);

        // Redirigir según el origen del formulario
        $origen = $this->request->getPost('origen') ?? 'pagos';
        $ruta   = match ($origen) {
            'clientes'  => '/clientes',
            'dashboard' => '/dashboard',
            default     => '/pagos',
        };

        return redirect()->to($ruta)
            ->with('success', 'Pago registrado correctamente.');
    }

    /**
     * Historial de pagos de un cliente.
     * GET /pagos/historial/:id
     */
    public function historial(int $clienteId)
    {
        $cliente = $this->clienteModel->find($clienteId);
        if (! $cliente) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'cliente' => $cliente,
            'pagos'   => $this->pagoModel->historialCliente($clienteId),
        ];

        return view('pagos/historial', $data);
    }
}
