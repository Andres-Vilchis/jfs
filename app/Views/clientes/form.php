<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var array       $planes
 * @var array|null  $cliente
 */
$editando = isset($cliente);
?>

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

        <div class="row g-2">

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Nombre *</span>
                    <input type="text" name="nombre" class="form-control"
                        placeholder="Nombre"
                        value="<?= set_value('nombre', $cliente['nombre'] ?? '') ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Apellidos *</span>
                    <input type="text" name="apellidos" class="form-control"
                        placeholder="Apellidos"
                        value="<?= set_value('apellidos', $cliente['apellidos'] ?? '') ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Correo</span>
                    <input type="email" name="correo" class="form-control"
                        placeholder="correo@ejemplo.com"
                        value="<?= set_value('correo', $cliente['correo'] ?? '') ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Teléfono</span>
                    <input type="text" name="telefono" class="form-control"
                        placeholder="10 dígitos"
                        value="<?= set_value('telefono', $cliente['telefono'] ?? '') ?>">
                </div>
            </div>

            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Fecha nacimiento</span>
                    <input type="date" name="fecha_nacimiento" class="form-control"
                        value="<?= set_value('fecha_nacimiento', $cliente['fecha_nacimiento'] ?? '') ?>">
                </div>
            </div>

            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Género</span>
                    <select name="genero" class="form-select form-select-sm">
                        <option value="">— Selecciona —</option>
                        <?php foreach (['masculino', 'femenino', 'otro'] as $g): ?>
                            <option value="<?= $g ?>" <?= (($cliente['genero'] ?? '') === $g) ? 'selected' : '' ?>>
                                <?= ucfirst($g) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Nivel *</span>
                    <select name="nivel" class="form-select form-select-sm">
                        <?php foreach (['principiante', 'intermedio', 'avanzado'] as $n): ?>
                            <option value="<?= $n ?>" <?= (($cliente['nivel'] ?? 'principiante') === $n) ? 'selected' : '' ?>>
                                <?= ucfirst($n) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Plan</span>
                    <select name="plan_id" class="form-select form-select-sm">
                        <option value="">— Sin plan —</option>
                        <?php foreach ($planes as $p): ?>
                            <option value="<?= $p['id'] ?>"
                                <?= (($cliente['plan_id'] ?? '') == $p['id']) ? 'selected' : '' ?>>
                                <?= esc($p['nombre']) ?> — $<?= $p['precio'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Vencimiento</span>
                    <input type="date" name="fecha_vencimiento" class="form-control"
                        value="<?= set_value('fecha_vencimiento', $cliente['fecha_vencimiento'] ?? '') ?>">
                </div>
            </div>

            <div class="col-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Notas</span>
                    <textarea name="notas" class="form-control" rows="3"
                        placeholder="Observaciones..."><?= esc($cliente['notas'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                <?= $editando ? 'Actualizar' : 'Registrar' ?>
            </button>
            <a href="<?= route_to('clientes.index') ?>" class="btn btn-outline-secondary ms-2">
                Cancelar
            </a>
        </div>

        <?= form_close() ?>
        <!-- ↑ form principal cierra aquí -->

        <?php if ($editando && ($cliente['activo'] ?? 0)): ?>
            <!-- Form independiente de eliminación — NO anidado en el form de edición -->
            <form action="<?= route_to('clientes.desactivar', $cliente['id']) ?>"
                method="post"
                class="mt-3 text-end"
                onsubmit="return confirm('¿Seguro que deseas eliminar a <?= addslashes(esc($cliente['nombre'] . ' ' . $cliente['apellidos'])) ?>? Esta acción lo desactivará del sistema.')">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash3-fill me-1"></i>
                </button>
            </form>
        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>