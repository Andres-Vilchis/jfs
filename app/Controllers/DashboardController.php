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
        $totalClientes  = $clienteModel->where('activo', 1)->countAllResults();
        $totalTrainers  = (new TrainerModel())->where('activo', 1)->countAllResults();
        $totalClases    = $claseModel->where('activo', 1)->countAllResults();
        $totalPlanes    = (new PlanModel())->where('activo', 1)->countAllResults();

        // Clientes próximos a vencer (7 días)
        $proximosVencer = $clienteModel
            ->where('activo', 1)
            ->where('fecha_vencimiento >=', date('Y-m-d'))
            ->where('fecha_vencimiento <=', date('Y-m-d', strtotime('+7 days')))
            ->orderBy('fecha_vencimiento', 'ASC')
            ->findAll();

        // Clientes vencidos
        $vencidos = $clienteModel
            ->where('activo', 1)
            ->where('fecha_vencimiento <', date('Y-m-d'))
            ->countAllResults();

        // Últimos 5 clientes
        $ultimosClientes = $clienteModel->ultimos(5);

        // Clases de hoy
        $clasesHoy = $claseModel->hoy();

        $data = [
            'fecha_formateada' => fechaFormateada(),
            'totalClientes'    => $totalClientes,
            'totalTrainers'    => $totalTrainers,
            'totalClases'      => $totalClases,
            'totalPlanes'      => $totalPlanes,
            'proximosVencer'   => $proximosVencer,
            'vencidos'         => $vencidos,
            'ultimosClientes'  => $ultimosClientes,
            'clasesHoy'        => $clasesHoy,
        ];

        return view('dashboard/index', $data);
    }
}