<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\Shield\Entities\User;

class UsuariosController extends BaseController
{
    protected UsuarioModel $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    private function soloAdmin()
    {
        if (! auth()->loggedIn() || ! auth()->user()->inGroup('admin')) {
            return redirect()->to('/dashboard');
        }
        return null;
    }

    public function index()
    {
        if ($r = $this->soloAdmin()) {
            return $r;
        }

        return view('usuarios/index', [
            'fecha_formateada' => fechaFormateada(),
            'usuarios'         => $this->usuarioModel->obtenerTodos(),
        ]);
    }

    public function crear()
    {
        if ($r = $this->soloAdmin()) {
            return $r;
        }

        return view('usuarios/form');
    }

    public function guardar()
    {
        if ($r = $this->soloAdmin()) return $r;

        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[auth_identities.secret]',
            'password' => 'required|min_length[8]',
            'grupo'    => 'required|in_list[admin,recepcionista,entrenador,cliente]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->usuarioModel->crear(
            $this->request->getPost('username'),
            $this->request->getPost('email'),
            $this->request->getPost('password'),
            $this->request->getPost('grupo')
        );

        return redirect()->to('/usuarios')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function editar(int $id)
    {
        if ($r = $this->soloAdmin()) {
            return $r;
        }

        $usuario = $this->usuarioModel->obtenerPorId($id);

        if (! $usuario) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('usuarios/form', [
            'usuario' => $usuario,
        ]);
    }

    public function actualizar(int $id)
    {
        if ($r = $this->soloAdmin()) {
            return $r;
        }

        $rules = [
            'username' => "required|min_length[3]|is_unique[users.username,id,{$id}]",
            'email'    => 'required|valid_email',
            'grupo'    => 'required|in_list[admin,recepcionista,entrenador,cliente]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->usuarioModel->actualizar(
            $id,
            $this->request->getPost('username'),
            $this->request->getPost('email'),
            $this->request->getPost('password'),
            $this->request->getPost('grupo')
        );

        return redirect()->to('/usuarios')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function toggleActivo(int $id)
    {
        if ($r = $this->soloAdmin()) {
            return $r;
        }

        if (auth()->id() === $id) {
            return redirect()->to('/usuarios')
                ->with('error', 'No puedes desactivarte a ti mismo.');
        }

        $this->usuarioModel->toggleActivo($id);

        return redirect()->to('/usuarios')
            ->with('success', 'Usuario actualizado.');
    }
}
