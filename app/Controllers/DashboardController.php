<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        if (! auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $data = [
            'fecha_formateada' => strftime('%A %d de %B, %Y') 
                ?? date('d/m/Y'),
        ];

        return view('dashboard/index', $data);
    }
}