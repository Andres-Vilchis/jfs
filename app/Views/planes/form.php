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

<div class="card" style="max-width:600px">
    <div class="card-body">
        <?php $action = $editando
            ? route_to('planes.actualizar', $plan['id'])
            : route_to('planes.guardar'); ?>

        <?= form_open($action) ?>

        <div class="row g-2">

            <div class="col-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Nombre &nbsp;<span class="text-danger">*</span></span>
                    <input type="text" name="nombre" class="form-control" style="font-size:.75rem" placeholder="Ej. Plan Mensual, Plan Trimestral..."
                        value="<?= set_value('nombre', $plan['nombre'] ?? '') ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Precio MXN$ &nbsp;<span class="text-danger">*</span></span>
                    <input type="number" name="precio" step="0.01" min="0" class="form-control" style="font-size:.75rem" placeholder="0.00"
                        value="<?= set_value('precio', $plan['precio'] ?? '') ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Duración (días) &nbsp;<span class="text-danger">*</span></span>
                    <input type="number" name="duracion_dias" min="1" class="form-control" style="font-size:.75rem" placeholder="Ej. 30, 90, 365"
                        value="<?= set_value('duracion_dias', $plan['duracion_dias'] ?? '') ?>" required>
                </div>
            </div>

            <div class="col-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Descripción</span>
                    <textarea name="descripcion" class="form-control" rows="2"><?= esc($plan['descripcion'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="col-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Beneficios</span>
                    <textarea name="beneficios" class="form-control" rows="4"><?= esc($plan['beneficios'] ?? '') ?></textarea>
                </div>
                <div class="text-muted fst-italic form-text ps-1" style="font-size:.75rem">Escribe un beneficio por línea.</div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-save me-1"></i>
                    <?= $editando ? 'Actualizar' : 'Crear plan' ?>
                </button>
                <a href="<?= route_to('planes.index') ?>" class="btn btn-sm btn-outline-secondary ms-2">
                    <i class="bi bi-x-diamond-fill me-1"></i>Cancelar
                </a>
            </div>

            <?= form_close() ?>
            <!-- ↑ form principal cierra aquí -->

            <?php if ($editando && ($plan['activo'] ?? 0)): ?>
                <!-- Form independiente de eliminación — NO anidado en el form de edición -->
                <form action="<?= route_to('planes.toggle', $plan['id']) ?>"
                    method="post"
                    class="text-end"
                    onsubmit="return confirm('¿Seguro que deseas eliminar el plan &quot;<?= addslashes(esc($plan['nombre'])) ?>&quot;?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>