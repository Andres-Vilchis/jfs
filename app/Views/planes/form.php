<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var array|null $plan
 */
$editando = isset($plan);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?= $editando ? 'Editar plan' : 'Nuevo plan' ?></h5>
    <a href="<?= route_to('planes.index') ?>" class="btn btn-sm btn-outline-secondary">
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
            ? route_to('planes.actualizar', $plan['id'])
            : route_to('planes.guardar'); ?>

        <?= form_open($action) ?>

        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Nombre del plan *</label>
                <?= form_input([
                    'name' => 'nombre',
                    'class' => 'form-control',
                    'placeholder' => 'Ej. Plan Mensual, Plan Trimestral...',
                    'value' => set_value('nombre', $plan['nombre'] ?? '')
                ]) ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Precio (MXN) *</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <?= form_input([
                        'name' => 'precio',
                        'type' => 'number',
                        'step' => '0.01',
                        'min' => '0',
                        'class' => 'form-control',
                        'value' => set_value('precio', $plan['precio'] ?? '')
                    ]) ?>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Duración (días) *</label>
                <?= form_input([
                    'name' => 'duracion_dias',
                    'type' => 'number',
                    'min' => '1',
                    'class' => 'form-control',
                    'placeholder' => 'Ej. 30, 90, 365',
                    'value' => set_value('duracion_dias', $plan['duracion_dias'] ?? '')
                ]) ?>
            </div>
            <div class="col-12">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="2"
                    placeholder="Descripción breve del plan..."><?= esc($plan['descripcion'] ?? '') ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Beneficios</label>
                <textarea name="beneficios" class="form-control" rows="4"
                    placeholder="Un beneficio por línea...&#10;Acceso ilimitado&#10;Clases grupales&#10;Casillero incluido"><?= esc($plan['beneficios'] ?? '') ?></textarea>
                <div class="form-text">Escribe un beneficio por línea.</div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> <?= $editando ? 'Actualizar' : 'Crear plan' ?>
            </button>
            <a href="<?= route_to('planes.index') ?>" class="btn btn-outline-secondary ms-2">Cancelar</a>
        </div>

        <?= form_close() ?>
    </div>
</div>

<?= $this->endSection() ?>