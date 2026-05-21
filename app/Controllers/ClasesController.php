<?php

namespace App\Controllers;

use App\Models\ClaseModel;
use App\Models\TrainerModel;

class ClasesController extends BaseController
{
    protected ClaseModel   $claseModel;
    protected TrainerModel $trainerModel;

    public function __construct()
    {
        $this->claseModel   = new ClaseModel();
        $this->trainerModel = new TrainerModel();
    }

    public function index()
    {
        return view('clases/index', [
            'clases' => $this->claseModel->conTrainer(),
        ]);
    }

    public function crear()
    {
        return view('clases/form', [
            'trainers' => $this->trainerModel->where('activo', 1)->findAll(),
        ]);
    }

    public function guardar()
    {
        $rules = [
            'nombre'        => 'required|min_length[2]',
            'hora_inicio'   => 'required',
            'hora_fin'      => 'required',
            'dias_semana'   => 'required',
            'capacidad_max' => 'required|integer|greater_than[0]',
            'nivel'         => 'required|in_list[principiante,intermedio,avanzado]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $dias = implode(',', $this->request->getPost('dias_semana') ?? []);

        $this->claseModel->save([
            'nombre'        => $this->request->getPost('nombre'),
            'descripcion'   => $this->request->getPost('descripcion'),
            'trainer_id'    => $this->request->getPost('trainer_id') ?: null,
            'nivel'         => $this->request->getPost('nivel'),
            'capacidad_max' => $this->request->getPost('capacidad_max'),
            'hora_inicio'   => $this->request->getPost('hora_inicio'),
            'hora_fin'      => $this->request->getPost('hora_fin'),
            'dias_semana'   => $dias,
            'salon'         => $this->request->getPost('salon'),
            'activo'        => 1,
        ]);

        return redirect()->to('/clases')
            ->with('success', 'Clase registrada correctamente.');
    }

    public function editar(int $id)
    {
        return view('clases/form', [
            'clase'    => $this->claseModel->findOrFail($id),
            'trainers' => $this->trainerModel->where('activo', 1)->findAll(),
        ]);
    }

    public function actualizar(int $id)
    {
        $rules = [
            'nombre'        => 'required|min_length[2]',
            'hora_inicio'   => 'required',
            'hora_fin'      => 'required',
            'dias_semana'   => 'required',
            'capacidad_max' => 'required|integer|greater_than[0]',
            'nivel'         => 'required|in_list[principiante,intermedio,avanzado]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $dias = implode(',', $this->request->getPost('dias_semana') ?? []);

        $this->claseModel->update($id, [
            'nombre'        => $this->request->getPost('nombre'),
            'descripcion'   => $this->request->getPost('descripcion'),
            'trainer_id'    => $this->request->getPost('trainer_id') ?: null,
            'nivel'         => $this->request->getPost('nivel'),
            'capacidad_max' => $this->request->getPost('capacidad_max'),
            'hora_inicio'   => $this->request->getPost('hora_inicio'),
            'hora_fin'      => $this->request->getPost('hora_fin'),
            'dias_semana'   => $dias,
            'salon'         => $this->request->getPost('salon'),
        ]);

        return redirect()->to('/clases')
            ->with('success', 'Clase actualizada correctamente.');
    }

    public function toggleActivo(int $id)
    {
        $clase = $this->claseModel->findOrFail($id);
        $this->claseModel->update($id, ['activo' => $clase['activo'] ? 0 : 1]);

        return redirect()->to('/clases')
            ->with('success', 'Clase actualizada.');
    }
}