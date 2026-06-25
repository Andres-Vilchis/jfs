<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\PlanModel;
use App\Models\PagoModel;

class ClientesController extends BaseController
{
    protected ClienteModel $clienteModel;

    public function __construct()
    {
        $this->clienteModel = new ClienteModel();
    }

    // Listado
    public function index()
    {
        $data = [
            'clientes' => $this->clienteModel->conPlanYClases(),
            'planes'   => (new PlanModel())->where('activo', 1)->findAll(),
        ];

        return view('clientes/index', $data);
    }

    // Formulario nuevo cliente
    public function crear()
    {
        $data = [
            'planes' => (new PlanModel())->where('activo', 1)->findAll(),
        ];

        return view('clientes/form', $data);
    }

    // Guardar nuevo cliente
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

        $planId           = $this->request->getPost('plan_id') ?: null;
        $fechaInscripcion = $this->request->getPost('fecha_inscripcion') ?: date('Y-m-d');
        $fechaVencimiento = $this->calcularVencimiento($planId, $fechaInscripcion);

        $this->clienteModel->save([
            'nombre'            => $this->request->getPost('nombre'),
            'apellidos'         => $this->request->getPost('apellidos'),
            'correo'            => $this->request->getPost('correo'),
            'telefono'          => $this->request->getPost('telefono'),
            'fecha_nacimiento'  => $this->request->getPost('fecha_nacimiento') ?: null,
            'genero'            => $this->request->getPost('genero'),
            'fecha_registro'    => date('Y-m-d'),
            'plan_id'           => $planId,
            'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento') ?: null,
            'nivel'             => $this->request->getPost('nivel'),
            'notas'             => $this->request->getPost('notas'),
            'activo'            => 1,
        ]);

        return redirect()->to('/clientes')
            ->with('success', 'Cliente registrado correctamente.');
    }

    // Formulario editar
    public function editar(int $id)
    {
        $cliente = $this->clienteModel->find($id);
        if (! $cliente) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'cliente' => $cliente,
            'planes'  => (new PlanModel())->where('activo', 1)->findAll(),
        ];

        return view('clientes/form', $data);
    }

    // Actualizar cliente
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

    // Desactivar cliente (soft delete)
    public function desactivar(int $id)
    {
        $this->clienteModel->update($id, ['activo' => 0]);
        return redirect()->to('/clientes')
            ->with('success', 'Cliente desactivado.');
    }

    // Registrar pago desde la vista Clientes (delega a PagosController)
    public function pagar(int $id)
    {
        return (new \App\Controllers\PagosController())->registrar($id);
    }

    private function calcularVencimiento(?int $planId, string $fechaInscripcion): ?string
    {
        if (! $planId || ! $fechaInscripcion) {
            return null;
        }

        $plan = (new PlanModel())->find($planId);

        if (! $plan) {
            return null;
        }

        return date('Y-m-d', strtotime($fechaInscripcion . ' +' . $plan['duracion_dias'] . ' days'));
    }
}
