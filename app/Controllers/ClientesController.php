<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\PlanModel;

class ClientesController extends BaseController
{
    protected ClienteModel $clienteModel;

    public function __construct()
    {
        $this->clienteModel = new ClienteModel();
    }

    public function index()
    {
        return view('clientes/index', [
            'fecha_formateada' => fechaFormateada(),
            'clientes' => $this->clienteModel->conPlan()->findAll(),
        ]);
    }

    public function crear()
    {
        return view('clientes/form', [
            'planes' => (new PlanModel())->where('activo', 1)->findAll(),
        ]);
    }

    public function guardar()
    {
        $rules = [
            'nombre'    => 'required|min_length[2]',
            'apellidos' => 'required|min_length[2]',
            'correo'    => 'permit_empty|valid_email',
            'nivel'     => 'required|in_list[principiante,intermedio,avanzado]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->clienteModel->save([
            'nombre'            => $this->request->getPost('nombre'),
            'apellidos'         => $this->request->getPost('apellidos'),
            'correo'            => $this->request->getPost('correo'),
            'telefono'          => $this->request->getPost('telefono'),
            'fecha_nacimiento'  => $this->request->getPost('fecha_nacimiento') ?: null,
            'genero'            => $this->request->getPost('genero'),
            'fecha_registro'    => date('Y-m-d'),
            'plan_id'           => $this->request->getPost('plan_id') ?: null,
            'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento') ?: null,
            'nivel'             => $this->request->getPost('nivel'),
            'notas'             => $this->request->getPost('notas'),
            'activo'            => 1,
        ]);

        return redirect()->to('/clientes')
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function editar(int $id)
    {
        $cliente = $this->clienteModel->find($id);

        if (! $cliente) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Cliente #{$id} no encontrado.");
        }

        return view('clientes/form', [
            'cliente' => $cliente,
            'planes'  => (new PlanModel())->where('activo', 1)->findAll(),
        ]);
    }

    public function actualizar(int $id)
    {
        $rules = [
            'nombre'    => 'required|min_length[2]',
            'apellidos' => 'required|min_length[2]',
            'correo'    => 'permit_empty|valid_email',
            'nivel'     => 'required|in_list[principiante,intermedio,avanzado]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->clienteModel->update($id, [
            'nombre'            => $this->request->getPost('nombre'),
            'apellidos'         => $this->request->getPost('apellidos'),
            'correo'            => $this->request->getPost('correo'),
            'telefono'          => $this->request->getPost('telefono'),
            'fecha_nacimiento'  => $this->request->getPost('fecha_nacimiento') ?: null,
            'genero'            => $this->request->getPost('genero'),
            'plan_id'           => $this->request->getPost('plan_id') ?: null,
            'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento') ?: null,
            'nivel'             => $this->request->getPost('nivel'),
            'notas'             => $this->request->getPost('notas'),
        ]);

        return redirect()->to('/clientes')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function desactivar(int $id)
    {
        $this->clienteModel->update($id, ['activo' => 0]);

        return redirect()->to('/clientes')
            ->with('success', 'Cliente desactivado.');
    }
}