<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var array{id: int, nombre: string, apellidos: string, correo: string|null, telefono: string|null, nivel: string, especialidad: string|null}|null $trainer
 */
$editando = isset($trainer);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?= $editando ? 'Editar trainer' : 'Nuevo trainer' ?></h5>
    <a href="<?= route_to('trainers.index') ?>" class="btn btn-sm btn-outline-secondary">
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

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <?php $action = $editando
            ? route_to('trainers.actualizar', $trainer['id'])
            : route_to('trainers.guardar'); ?>

        <?= form_open($action) ?>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre *</label>
                <?= form_input([
                    'name' => 'nombre',
                    'class' => 'form-control',
                    'value' => set_value('nombre', $trainer['nombre'] ?? '')
                ]) ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Apellidos *</label>
                <?= form_input([
                    'name' => 'apellidos',
                    'class' => 'form-control',
                    'value' => set_value('apellidos', $trainer['apellidos'] ?? '')
                ]) ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Correo</label>
                <?= form_input([
                    'name' => 'correo',
                    'type' => 'email',
                    'class' => 'form-control',
                    'value' => set_value('correo', $trainer['correo'] ?? '')
                ]) ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Teléfono</label>
                <?= form_input([
                    'name' => 'telefono',
                    'class' => 'form-control',
                    'value' => set_value('telefono', $trainer['telefono'] ?? '')
                ]) ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nivel *</label>
                <select name="nivel" class="form-select">
                    <?php foreach (['principiante', 'intermedio', 'avanzado'] as $n): ?>
                        <option value="<?= $n ?>"
                            <?= (($trainer['nivel'] ?? 'intermedio') === $n) ? 'selected' : '' ?>>
                            <?= ucfirst($n) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Especialidad</label>
                <?= form_input([
                    'name' => 'especialidad',
                    'class' => 'form-control',
                    'placeholder' => 'Ej. Crossfit, Yoga, Spinning...',
                    'value' => set_value('especialidad', $trainer['especialidad'] ?? '')
                ]) ?>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> <?= $editando ? 'Actualizar' : 'Registrar' ?>
            </button>
            <a href="<?= route_to('trainers.index') ?>" class="btn btn-outline-secondary ms-2">Cancelar</a>
        </div>

        <?= form_close() ?>
    </div>
</div>

<?= $this->endSection() ?>