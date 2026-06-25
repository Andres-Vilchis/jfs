<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\ClaseModel;
use App\Models\PlanModel;
use App\Models\TrainerModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $clienteModel = new ClienteModel();
        $claseModel   = new ClaseModel();

        // Totales
        $totalClientes = $clienteModel->where('activo', 1)->countAllResults();
        $totalTrainers = (new TrainerModel())->where('activo', 1)->countAllResults();
        $totalClases   = $claseModel->where('activo', 1)->countAllResults();
        $totalPlanes   = (new PlanModel())->where('activo', 1)->countAllResults();

        $hoy    = date('Y-m-d');
        $en5d   = date('Y-m-d', strtotime('+5 days'));

        // Alertas de vencimiento: vencidos + próximos 5 días
        // Excluye planes por clase (duracion_dias = 1) y clientes sin plan
        $alertasVencimiento = $clienteModel
            ->select('clientes.*, planes.nombre AS plan_nombre, planes.precio AS plan_precio, planes.duracion_dias')
            ->join('planes', 'planes.id = clientes.plan_id', 'left')
            ->where('clientes.activo', 1)
            ->where('planes.duracion_dias >', 1)
            ->groupStart()
            ->where('clientes.fecha_vencimiento <', $hoy)
            ->orWhere('clientes.fecha_vencimiento <=', $en5d)
            ->groupEnd()
            ->orderBy('clientes.fecha_vencimiento', 'ASC')
            ->findAll();

        // Contador de vencidos (excluye por clase)
        $vencidos = $clienteModel
            ->join('planes', 'planes.id = clientes.plan_id', 'left')
            ->where('clientes.activo', 1)
            ->where('planes.duracion_dias >', 1)
            ->where('clientes.fecha_vencimiento <', $hoy)
            ->countAllResults();

        // Últimos 3 clientes registrados
        $ultimosClientes = $clienteModel->ultimos(3);

        // Próxima clase de hoy
        $proximaClase = $claseModel->proximaClaseHoy();

        $data = [
            'fecha_formateada'   => fechaFormateada(),
            'totalClientes'      => $totalClientes,
            'totalTrainers'      => $totalTrainers,
            'totalClases'        => $totalClases,
            'totalPlanes'        => $totalPlanes,
            'alertasVencimiento' => $alertasVencimiento,
            'vencidos'           => $vencidos,
            'ultimosClientes'    => $ultimosClientes,
            'proximaClase'       => $proximaClase,
        ];

        return view('dashboard/index', $data);
    }
}
