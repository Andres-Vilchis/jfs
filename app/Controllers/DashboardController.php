<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $data = [
            'fecha_formateada' => strftime('%A %d de %B, %Y') 
                ?? date('d/m/Y'),
        ];

        return view('dashboard/index', $data);
    }
}