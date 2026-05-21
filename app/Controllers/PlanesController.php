<?php

namespace App\Controllers;

use App\Models\PlanModel;

class PlanesController extends BaseController
{
    protected PlanModel $planModel;

    public function __construct()
    {
        $this->planModel = new PlanModel();
    }

    public function index()
    {
        return view('planes/index', [
            'planes' => $this->planModel->orderBy('precio', 'ASC')->findAll(),
        ]);
    }

    public function crear()
    {
        return view('planes/form');
    }

    public function guardar()
    {
        $rules = [
            'nombre'        => 'required|min_length[2]|max_length[100]',
            'precio'        => 'required|decimal',
            'duracion_dias' => 'required|integer|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->planModel->save([
            'nombre'        => $this->request->getPost('nombre'),
            'descripcion'   => $this->request->getPost('descripcion'),
            'precio'        => $this->request->getPost('precio'),
            'duracion_dias' => $this->request->getPost('duracion_dias'),
            'beneficios'    => $this->request->getPost('beneficios'),
            'activo'        => 1,
        ]);

        return redirect()->to('/planes')
            ->with('success', 'Plan creado correctamente.');
    }

    public function editar(int $id)
    {
        return view('planes/form', [
            'plan' => $this->planModel->findOrFail($id),
        ]);
    }

    public function actualizar(int $id)
    {
        $rules = [
            'nombre'        => 'required|min_length[2]|max_length[100]',
            'precio'        => 'required|decimal',
            'duracion_dias' => 'required|integer|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->planModel->update($id, [
            'nombre'        => $this->request->getPost('nombre'),
            'descripcion'   => $this->request->getPost('descripcion'),
            'precio'        => $this->request->getPost('precio'),
            'duracion_dias' => $this->request->getPost('duracion_dias'),
            'beneficios'    => $this->request->getPost('beneficios'),
        ]);

        return redirect()->to('/planes')
            ->with('success', 'Plan actualizado correctamente.');
    }

    public function toggleActivo(int $id)
    {
        $plan = $this->planModel->findOrFail($id);
        $this->planModel->update($id, ['activo' => $plan['activo'] ? 0 : 1]);

        return redirect()->to('/planes')
            ->with('success', 'Plan actualizado.');
    }
}