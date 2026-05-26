<?php

namespace App\Controllers;

use App\Models\ClaseModel;
use App\Models\ClienteModel;
use App\Models\TrainerModel;

class ClasesController extends BaseController
{
    protected ClaseModel   $claseModel;
    protected TrainerModel $trainerModel;

    // Jerarquía de niveles
    private array $niveles = ['principiante' => 1, 'intermedio' => 2, 'avanzado' => 3];

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
            'trainer_id'    => 'required',
            'hora_inicio'   => 'required',
            'hora_fin'      => 'required',
            'dias_semana'   => 'required',
            'capacidad_max' => 'required|integer|greater_than[0]',
            'nivel'         => 'required|in_list[principiante,intermedio,avanzado]',
        ];

        $messages = [
            'trainer_id' => [
                'required' => 'Debes asignar un trainer a la clase.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Validar que el nivel de la clase no supere el del trainer
        $trainerId    = $this->request->getPost('trainer_id');
        $trainer      = $this->trainerModel->find($trainerId);
        $nivelClase   = $this->niveles[$this->request->getPost('nivel')] ?? 0;
        $nivelTrainer = $this->niveles[$trainer['nivel']] ?? 0;

        if ($nivelClase > $nivelTrainer) {
            return redirect()->back()->withInput()
                ->with('errors', [
                    'nivel' => 'El nivel de la clase no puede ser superior al del trainer ('
                        . ucfirst($trainer['nivel']) . ').',
                ]);
        }

        $dias = implode(',', $this->request->getPost('dias_semana') ?? []);

        $this->claseModel->save([
            'nombre'        => $this->request->getPost('nombre'),
            'descripcion'   => $this->request->getPost('descripcion'),
            'trainer_id'    => $trainerId,
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
        $clase = $this->claseModel->find($id);
        if (! $clase) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $db        = db_connect();
        $inscritos = $db->table('clientes_clases')
            ->where('clase_id', $id)
            ->where('activo', 1)
            ->countAllResults();

        return view('clases/form', [
            'clase'     => $clase,
            'trainers'  => $this->trainerModel->where('activo', 1)->findAll(),
            'inscritos' => $inscritos,
        ]);
    }

    public function actualizar(int $id)
    {
        $rules = [
            'nombre'        => 'required|min_length[2]',
            'trainer_id'    => 'required',
            'hora_inicio'   => 'required',
            'hora_fin'      => 'required',
            'dias_semana'   => 'required',
            'capacidad_max' => 'required|integer|greater_than[0]',
            'nivel'         => 'required|in_list[principiante,intermedio,avanzado]',
        ];

        $messages = [
            'trainer_id' => [
                'required' => 'Debes asignar un trainer a la clase.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Validar que el nivel de la clase no supere el del trainer
        $trainerId    = $this->request->getPost('trainer_id');
        $trainer      = $this->trainerModel->find($trainerId);
        $nivelClase   = $this->niveles[$this->request->getPost('nivel')] ?? 0;
        $nivelTrainer = $this->niveles[$trainer['nivel']] ?? 0;

        if ($nivelClase > $nivelTrainer) {
            return redirect()->back()->withInput()
                ->with('errors', [
                    'nivel' => 'El nivel de la clase no puede ser superior al del trainer ('
                        . ucfirst($trainer['nivel']) . ').',
                ]);
        }

        $dias = implode(',', $this->request->getPost('dias_semana') ?? []);

        $this->claseModel->update($id, [
            'nombre'        => $this->request->getPost('nombre'),
            'descripcion'   => $this->request->getPost('descripcion'),
            'trainer_id'    => $trainerId,
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
        $clase = $this->claseModel->find($id);
        if (! $clase) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $nuevoEstado = $clase['activo'] ? 0 : 1;
        $this->claseModel->update($id, ['activo' => $nuevoEstado]);

        $msg = $nuevoEstado
            ? 'Clase activada correctamente.'
            : 'Clase suspendida correctamente.';

        return redirect()->to('/clases')->with('success', $msg);
    }

    // ─── Gestión de participantes ────────────────────────────────────────────

    public function participantes(int $id)
    {
        $clase = $this->claseModel->find($id);
        if (! $clase) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $db = db_connect();

        // Clientes ya inscritos en esta clase
        $inscritos = $db->table('clientes_clases cc')
            ->select('c.id, c.nombre, c.apellidos, c.nivel, cc.fecha_inscripcion')
            ->join('clientes c', 'c.id = cc.cliente_id')
            ->where('cc.clase_id', $id)
            ->where('cc.activo', 1)
            ->where('c.activo', 1)
            ->orderBy('c.nombre', 'ASC')
            ->get()->getResultArray();

        $inscritosIds = array_column($inscritos, 'id');

        // Clientes activos NO inscritos en esta clase
        $clienteModel   = new ClienteModel();
        $clientesLibres = $clienteModel->where('activo', 1)->findAll();
        $clientesLibres = array_values(array_filter($clientesLibres, function ($c) use ($inscritosIds) {
            return ! in_array($c['id'], $inscritosIds);
        }));

        $disponibles = $clase['capacidad_max'] - count($inscritos);

        return view('clases/participantes', [
            'clase'          => $clase,
            'inscritos'      => $inscritos,
            'clientesLibres' => $clientesLibres,
            'disponibles'    => $disponibles,
        ]);
    }

    public function agregarParticipante(int $id)
    {
        $clase = $this->claseModel->find($id);
        if (! $clase) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $clienteId = (int) $this->request->getPost('cliente_id');
        $db        = db_connect();

        // 1. Verificar que no esté ya inscrito
        $yaInscrito = $db->table('clientes_clases')
            ->where('clase_id', $id)
            ->where('cliente_id', $clienteId)
            ->where('activo', 1)
            ->countAllResults();

        if ($yaInscrito) {
            return redirect()->to("/clases/{$id}/participantes")
                ->with('error', 'El cliente ya está inscrito en esta clase.');
        }

        // 2. Verificar capacidad
        $totalInscritos = $db->table('clientes_clases')
            ->where('clase_id', $id)
            ->where('activo', 1)
            ->countAllResults();

        if ($totalInscritos >= $clase['capacidad_max']) {
            return redirect()->to("/clases/{$id}/participantes")
                ->with('error', 'La clase ya alcanzó su capacidad máxima (' . $clase['capacidad_max'] . ' participantes).');
        }

        // 3. Verificar conflicto de horario
        if ($this->tieneConflictoHorario($clienteId, $id, $db)) {
            return redirect()->to("/clases/{$id}/participantes")
                ->with('error', 'El cliente ya tiene otra clase en ese mismo horario.');
        }

        // Insertar inscripción
        $db->table('clientes_clases')->insert([
            'cliente_id'        => $clienteId,
            'clase_id'          => $id,
            'fecha_inscripcion' => date('Y-m-d'),
            'activo'            => 1,
        ]);

        return redirect()->to("/clases/{$id}/participantes")
            ->with('success', 'Participante agregado correctamente.');
    }

    public function quitarParticipante(int $id, int $clienteId)
    {
        $db = db_connect();
        $db->table('clientes_clases')
            ->where('clase_id', $id)
            ->where('cliente_id', $clienteId)
            ->update(['activo' => 0]);

        return redirect()->to("/clases/{$id}/participantes")
            ->with('success', 'Participante removido de la clase.');
    }

    // ─── Helper: detección de conflicto de horario ──────────────────────────

    private function tieneConflictoHorario(int $clienteId, int $claseId, $db): bool
    {
        $clase    = $this->claseModel->find($claseId);
        $diasNueva = array_map('trim', explode(',', $clase['dias_semana']));

        // Otras clases activas en las que el cliente ya está inscrito
        $otrasClases = $db->table('clientes_clases cc')
            ->select('cl.id, cl.hora_inicio, cl.hora_fin, cl.dias_semana')
            ->join('clases cl', 'cl.id = cc.clase_id')
            ->where('cc.cliente_id', $clienteId)
            ->where('cc.activo', 1)
            ->where('cc.clase_id !=', $claseId)
            ->where('cl.activo', 1)
            ->get()->getResultArray();

        foreach ($otrasClases as $otra) {
            $diasOtra = array_map('trim', explode(',', $otra['dias_semana']));

            // ¿Comparten algún día?
            if (empty(array_intersect($diasNueva, $diasOtra))) {
                continue;
            }

            // ¿Los horarios se solapan?
            if ($clase['hora_inicio'] < $otra['hora_fin'] &&
                $clase['hora_fin']    > $otra['hora_inicio']) {
                return true;
            }
        }

        return false;
    }
}