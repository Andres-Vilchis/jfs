<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var array|null $trainer
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

<div class="card" style="max-width:600px">
    <div class="card-body">
        <?php $action = $editando
            ? route_to('trainers.actualizar', $trainer['id'])
            : route_to('trainers.guardar'); ?>

        <?= form_open($action) ?>

        <div class="row g-2">

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:120px">Nombre *</span>
                    <input type="text" name="nombre" class="form-control"
                        placeholder="Nombre"
                        value="<?= set_value('nombre', $trainer['nombre'] ?? '') ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:120px">Apellidos *</span>
                    <input type="text" name="apellidos" class="form-control"
                        placeholder="Apellidos"
                        value="<?= set_value('apellidos', $trainer['apellidos'] ?? '') ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:120px">Correo</span>
                    <input type="email" name="correo" class="form-control"
                        placeholder="correo@ejemplo.com"
                        value="<?= set_value('correo', $trainer['correo'] ?? '') ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:120px">Teléfono</span>
                    <input type="text" name="telefono" class="form-control"
                        placeholder="10 dígitos"
                        value="<?= set_value('telefono', $trainer['telefono'] ?? '') ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:120px">Nivel *</span>
                    <select name="nivel" class="form-select form-select-sm">
                        <?php foreach (['principiante', 'intermedio', 'avanzado'] as $n): ?>
                            <option value="<?= $n ?>"
                                <?= (($trainer['nivel'] ?? 'principiante') === $n) ? 'selected' : '' ?>>
                                <?= ucfirst($n) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:120px">Especialidad</span>
                    <input type="text" name="especialidad" class="form-control"
                        placeholder="Ej. Crossfit, Yoga..."
                        value="<?= set_value('especialidad', $trainer['especialidad'] ?? '') ?>">
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                <?= $editando ? 'Actualizar' : 'Registrar' ?>
            </button>
            <a href="<?= route_to('trainers.index') ?>" class="btn btn-outline-secondary ms-2">
                Cancelar
            </a>
        </div>

        <?= form_close() ?>
        <!-- ↑ form principal cierra aquí -->

        <?php if ($editando && ($trainer['activo'] ?? 0)): ?>
            <!-- Form independiente de eliminación — NO anidado en el form de edición -->
            <form action="<?= route_to('trainers.toggle', $trainer['id']) ?>"
                method="post"
                class="mt-3 text-end"
                onsubmit="return confirm('¿Seguro que deseas eliminar al trainer <?= addslashes(esc($trainer['nombre'] . ' ' . $trainer['apellidos'])) ?>? También se suspenderán sus clases activas.')">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash3-fill me-1"></i> Eliminar
                </button>
            </form>
        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>