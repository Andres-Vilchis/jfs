<?php

namespace App\Controllers;

use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Entities\User;

class UsuariosController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Solo admin puede acceder
    private function soloAdmin()
    {
        if (! auth()->loggedIn() || ! auth()->user()->inGroup('admin')) {
            return redirect()->to('/dashboard');
        }
        return null;
    }

    public function index()
    {
        if ($r = $this->soloAdmin()) return $r;

        $usuarios = $this->userModel->findAll();

        // Agrega grupos y email a cada usuario
        foreach ($usuarios as &$u) {
            $u->grupos = $u->getGroups();
            $identity  = $u->getEmailIdentity();
            $u->email  = $identity?->secret ?? '—';
        }

        return view('usuarios/index', [
            'fecha_formateada' => fechaFormateada(),
            'usuarios' => $usuarios
        ]);
    }

    public function crear()
    {
        if ($r = $this->soloAdmin()) return $r;
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

        $user = new User([
            'username' => $this->request->getPost('username'),
            'active'   => 1,
        ]);

        $this->userModel->save($user);  // Guardar SIN password primero

        $user = $this->userModel->findById($this->userModel->getInsertID());

        // Crear identidad de email (esto también guarda la contraseña hasheada)
        $user->createEmailIdentity([
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ]);

        $user->addGroup($this->request->getPost('grupo'));

        return redirect()->to('/usuarios')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function editar(int $id)
    {
        if ($r = $this->soloAdmin()) return $r;

        $usuario          = $this->userModel->findById($id);
        $usuario->grupos  = $usuario->getGroups();
        $identity         = $usuario->getEmailIdentity();
        $usuario->email   = $identity?->secret ?? '';

        return view('usuarios/form', ['usuario' => $usuario]);
    }

    public function actualizar(int $id)
    {
        if ($r = $this->soloAdmin()) return $r;

        $rules = [
            'username' => "required|min_length[3]|is_unique[users.username,id,{$id}]",
            'email'    => "required|valid_email",
            'grupo'    => 'required|in_list[admin,recepcionista,entrenador,cliente]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $usuario = $this->userModel->findById($id);

        // Actualiza username
        $this->userModel->save([
            'id'       => $id,
            'username' => $this->request->getPost('username'),
        ]);

        $identity = $usuario->getEmailIdentity();
        if ($identity) {
            $identity->secret = $this->request->getPost('email');
            model(\CodeIgniter\Shield\Models\UserIdentityModel::class)->save($identity);
        }

        $nuevaPassword = $this->request->getPost('password');
        if (! empty($nuevaPassword)) {
            $usuario->setPassword($nuevaPassword);
            $this->userModel->save($usuario);
        }

        $usuario->syncGroups($this->request->getPost('grupo'));

        return redirect()->to('/usuarios')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function toggleActivo(int $id)
    {
        if ($r = $this->soloAdmin()) return $r;

        // No desactivarse a sí mismo
        if (auth()->id() == $id) {
            return redirect()->to('/usuarios')
                ->with('error', 'No puedes desactivarte a ti mismo.');
        }

        $usuario = $this->userModel->findById($id);
        if ($usuario->active) {
            $usuario->deactivate();
        } else {
            $usuario->activate();
        }
        $this->userModel->save($usuario);

        return redirect()->to('/usuarios')
            ->with('success', 'Usuario actualizado.');
    }
}
