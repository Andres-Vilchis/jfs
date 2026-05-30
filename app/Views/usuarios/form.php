<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var \CodeIgniter\Shield\Entities\User|null $usuario
 */
$editando = isset($usuario);
$grupos   = [
    'admin'         => 'Administrador',
    'recepcionista' => 'Recepcionista',
    'entrenador'    => 'Entrenador',
    'cliente'       => 'Cliente',
];
$grupoActual = $editando ? ($usuario->grupos[0] ?? '') : '';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?= $editando ? 'Editar usuario' : 'Nuevo usuario' ?></h5>
    <a href="<?= route_to('usuarios.index') ?>" class="btn btn-sm btn-outline-secondary">
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

<div class="card" style="max-width:520px">
    <div class="card-body">
        <?php $action = $editando
            ? route_to('usuarios.actualizar', $usuario->id)
            : route_to('usuarios.guardar'); ?>

        <?= form_open($action) ?>

        <div class="row g-2">
            <div class="col-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:140px">Usuario *</span>
                    <input type="text" name="username" class="form-control"
                        placeholder="Nombre de usuario"
                        value="<?= set_value('username', $usuario->username ?? '') ?>" required>
                </div>
            </div>

            <div class="col-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:140px">Correo *</span>
                    <input type="email" name="email" class="form-control"
                        placeholder="correo@ejemplo.com"
                        value="<?= set_value('email', $usuario->email ?? '') ?>" required>
                </div>
            </div>

            <div class="col-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:140px">
                        Contraseña <?= $editando ? '<small class="text-muted">(opcional)</small>' : '*' ?>
                    </span>
                    <input type="password" name="password" class="form-control"
                        placeholder="<?= $editando ? 'Dejar vacío para no cambiar' : 'Mínimo 8 caracteres' ?>"
                        autocomplete="new-password"
                        <?= $editando ? '' : 'required' ?>>
                </div>
            </div>

            <div class="col-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:140px">Rol *</span>
                    <select name="grupo" class="form-select form-select-sm" required>
                        <option value="">— Selecciona un rol —</option>
                        <?php foreach ($grupos as $key => $label): ?>
                            <option value="<?= $key ?>"
                                <?= ($grupoActual === $key) ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-save me-1"></i> <?= $editando ? 'Actualizar' : 'Crear usuario' ?>
            </button>
            <a href="<?= route_to('usuarios.index') ?>" class="btn btn-sm btn-outline-secondary ms-2"><i class="bi bi-x-diamond-fill me-1"></i>Cancelar</a>
        </div>

        <?= form_close() ?>
    </div>
</div>

<?= $this->endSection() ?>