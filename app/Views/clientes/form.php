<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var list<array{id: int, nombre: string, descripcion: string|null, precio: float|string, duracion_dias: int}> $planes
 * @var array{id: int, nombre: string, apellidos: string, correo: string|null, telefono: string|null, fecha_nacimiento: string|null, genero: string|null, plan_id: int|null, fecha_vencimiento: string|null, nivel: string, notas: string|null}|null $cliente
 */
$editando = isset($cliente);
?>

<?php $editando = isset($cliente); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?= $editando ? 'Editar cliente' : 'Nuevo cliente' ?></h5>
    <a href="<?= route_to('clientes.index') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <?php
        $action = $editando
            ? route_to('clientes.actualizar', $cliente['id'])
            : route_to('clientes.guardar');
        ?>
        <?= form_open($action) ?>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre *</label>
                <?= form_input([
                    'name' => 'nombre',
                    'class' => 'form-control',
                    'value' => set_value('nombre', $cliente['nombre'] ?? '')
                ]) ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Apellidos *</label>
                <?= form_input([
                    'name' => 'apellidos',
                    'class' => 'form-control',
                    'value' => set_value('apellidos', $cliente['apellidos'] ?? '')
                ]) ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Correo</label>
                <?= form_input([
                    'name' => 'correo',
                    'type' => 'email',
                    'class' => 'form-control',
                    'value' => set_value('correo', $cliente['correo'] ?? '')
                ]) ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Teléfono</label>
                <?= form_input([
                    'name' => 'telefono',
                    'class' => 'form-control',
                    'value' => set_value('telefono', $cliente['telefono'] ?? '')
                ]) ?>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha de nacimiento</label>
                <?= form_input([
                    'name' => 'fecha_nacimiento',
                    'type' => 'date',
                    'class' => 'form-control',
                    'value' => set_value('fecha_nacimiento', $cliente['fecha_nacimiento'] ?? '')
                ]) ?>
            </div>
            <div class="col-md-4">
                <label class="form-label">Género</label>
                <select name="genero" class="form-select">
                    <option value="">— Selecciona —</option>
                    <?php foreach (['masculino', 'femenino', 'otro'] as $g): ?>
                        <option value="<?= $g ?>" <?= (($cliente['genero'] ?? '') === $g) ? 'selected' : '' ?>>
                            <?= ucfirst($g) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nivel *</label>
                <select name="nivel" class="form-select">
                    <?php foreach (['principiante', 'intermedio', 'avanzado'] as $n): ?>
                        <option value="<?= $n ?>" <?= (($cliente['nivel'] ?? 'principiante') === $n) ? 'selected' : '' ?>>
                            <?= ucfirst($n) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Plan</label>
                <select name="plan_id" class="form-select">
                    <option value="">— Sin plan —</option>
                    <?php foreach ($planes as $p): ?>
                        <option value="<?= $p['id'] ?>"
                            <?= (($cliente['plan_id'] ?? '') == $p['id']) ? 'selected' : '' ?>>
                            <?= esc($p['nombre']) ?> — $<?= $p['precio'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha de vencimiento</label>
                <?= form_input([
                    'name' => 'fecha_vencimiento',
                    'type' => 'date',
                    'class' => 'form-control',
                    'value' => set_value('fecha_vencimiento', $cliente['fecha_vencimiento'] ?? '')
                ]) ?>
            </div>
            <div class="col-12">
                <label class="form-label">Notas</label>
                <textarea name="notas" class="form-control" rows="3"><?= esc($cliente['notas'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> <?= $editando ? 'Actualizar' : 'Registrar' ?>
            </button>
            <a href="<?= route_to('clientes.index') ?>" class="btn btn-outline-secondary ms-2">Cancelar</a>
        </div>

        <?= form_close() ?>
    </div>
</div>

<?= $this->endSection() ?>