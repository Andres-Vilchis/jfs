<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Authentication\Authenticators\Session;

class LoginController extends BaseController
{
    public function index()
    {
        // Si ya está logueado, redirige al dashboard
        if (auth()->loggedIn()) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function attempt()
    {
        $credentials = [
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('pswd'),
        ];

        $result = auth()->attempt($credentials);

        if (! $result->isOK()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Correo o contraseña incorrectos.');
        }

        // Redirige según el rol
        $user = auth()->user();

        if ($user->inGroup('admin')) {
            return redirect()->to('/admin/dashboard');
        } elseif ($user->inGroup('recepcionista')) {
            return redirect()->to('/recepcion/dashboard');
        } elseif ($user->inGroup('entrenador')) {
            return redirect()->to('/entrenador/dashboard');
        }

        return redirect()->to('/dashboard');
    }
}