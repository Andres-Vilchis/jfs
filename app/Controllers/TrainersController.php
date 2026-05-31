<?php

namespace App\Controllers;

use App\Models\ClaseModel;
use App\Models\TrainerModel;

class TrainersController extends BaseController
{
    protected TrainerModel $trainerModel;

    public function __construct()
    {
        $this->trainerModel = new TrainerModel();
    }

    public function index()
    {
        return view('trainers/index', [
            'fecha_formateada' => fechaFormateada(),
            'trainers' => $this->trainerModel->conClases(),
        ]);
    }

    public function crear()
    {
        return view('trainers/form');
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

        $this->trainerModel->save([
            'nombre'       => $this->request->getPost('nombre'),
            'apellidos'    => $this->request->getPost('apellidos'),
            'correo'       => $this->request->getPost('correo'),
            'telefono'     => $this->request->getPost('telefono'),
            'nivel'        => $this->request->getPost('nivel'),
            'especialidad' => $this->request->getPost('especialidad'),
            'activo'       => 1,
        ]);

        return redirect()->to('/trainers')
            ->with('success', 'Trainer registrado correctamente.');
    }

    public function editar(int $id)
    {
        return view('trainers/form', [
            'trainer' => $this->trainerModel->find($id),
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

        $this->trainerModel->update($id, [
            'nombre'       => $this->request->getPost('nombre'),
            'apellidos'    => $this->request->getPost('apellidos'),
            'correo'       => $this->request->getPost('correo'),
            'telefono'     => $this->request->getPost('telefono'),
            'nivel'        => $this->request->getPost('nivel'),
            'especialidad' => $this->request->getPost('especialidad'),
        ]);

        return redirect()->to('/trainers')
            ->with('success', 'Trainer actualizado correctamente.');
    }

    public function toggleActivo(int $id)
    {
        $trainer     = $this->trainerModel->find($id);
        if (! $trainer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $nuevoEstado = $trainer['activo'] ? 0 : 1;
        $this->trainerModel->update($id, ['activo' => $nuevoEstado]);

        if ($nuevoEstado === 0) {
            // Suspender también las clases activas del trainer
            $db            = db_connect();
            $clasesActivas = $db->table('clases')
                ->where('trainer_id', $id)
                ->where('activo', 1)
                ->countAllResults();

            if ($clasesActivas > 0) {
                $db->table('clases')
                    ->where('trainer_id', $id)
                    ->where('activo', 1)
                    ->update(['activo' => 0]);

                $msg = "Trainer suspendido. Se suspendieron automáticamente {$clasesActivas} clase(s) asignadas a este trainer.";
            } else {
                $msg = 'Trainer suspendido. No tenía clases activas asignadas.';
            }
        } else {
            $msg = 'Trainer activado correctamente.';
        }

        return redirect()->to('/trainers')->with('success', $msg);
    }
}