<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserIdentityModel;
use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UsuarioModel extends Model
{
    protected ShieldUserModel $userModel;
    protected UserIdentityModel $identityModel;

    public function __construct()
    {
        parent::__construct();

        $this->userModel     = new ShieldUserModel();
        $this->identityModel = new UserIdentityModel();
    }

    public function obtenerTodos(): array
    {
        $usuarios = $this->userModel->findAll();

        foreach ($usuarios as &$usuario) {
            $usuario->grupos = $usuario->getGroups();
            $identity = $usuario->getEmailIdentity();
            $usuario->email = $identity?->secret ?? '';
        }

        return $usuarios;
    }

    public function obtenerPorId(int $id): ?User
    {
        $usuario = $this->userModel->findById($id);

        if (! $usuario) {
            return null;
        }

        $usuario->grupos = $usuario->getGroups();
        $identity = $usuario->getEmailIdentity();
        $usuario->email = $identity?->secret ?? '';

        return $usuario;
    }

    public function crear(string $username, string $email, string $password, string $grupo): void
    {
        $user = new User([
            'username' => $username,
            'active'   => 1,
        ]);

        $this->userModel->save($user);

        $user = $this->userModel->findById(
            $this->userModel->getInsertID()
        );

        $user->createEmailIdentity([
            'email'    => $email,
            'password' => $password,
        ]);

        $user->addGroup($grupo);
    }

    public function actualizar(int $id, string $username, string $email, ?string $password, string $grupo): void
    {
        $usuario = $this->userModel->findById($id);

        if (! $usuario) {
            return;
        }

        // Username
        if ($usuario->username !== $username) {
            $this->userModel->update($id, [
                'username' => $username,
            ]);
        }

        // Email
        $identity = $usuario->getEmailIdentity();

        if (
            $identity !== null
            && trim((string) $identity->secret) !== trim($email)
        ) {
            $this->identityModel->update(
                $identity->id,
                [
                    'secret' => $email,
                ]
            );
        }

        // Password
        if (! empty($password)) {
            $usuario->setPassword($password);
            $this->userModel->save($usuario);
        }

        // Grupo
        $gruposActuales = $usuario->getGroups();

        if ( count($gruposActuales) !== 1 || $gruposActuales[0] !== $grupo ) {
            $usuario->syncGroups($grupo);
        }
    }

    public function toggleActivo(int $id): void
    {
        $usuario = $this->userModel->findById($id);

        if (! $usuario) {
            return;
        }

        if ($usuario->active) {
            $usuario->deactivate();
        } else {
            $usuario->activate();
        }

        $this->userModel->save($usuario);
    }
}
