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

<div class="card" style="max-width: 520px;">
    <div class="card-body">
        <?php $action = $editando
            ? route_to('usuarios.actualizar', $usuario->id)
            : route_to('usuarios.guardar'); ?>

        <?= form_open($action) ?>

        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Nombre de usuario *</label>
                <?= form_input([
                    'name' => 'username',
                    'class' => 'form-control',
                    'value' => set_value('username', $usuario->username ?? '')
                ]) ?>
            </div>
            <div class="col-12">
                <label class="form-label">Correo electrónico *</label>
                <?= form_input([
                    'name' => 'email',
                    'type' => 'email',
                    'class' => 'form-control',
                    'value' => set_value('email', $usuario->email ?? '')
                ]) ?>
            </div>
            <div class="col-12">
                <label class="form-label">
                    Contraseña <?= $editando ? '<span class="text-muted small">(dejar vacío para no cambiar)</span>' : '*' ?>
                </label>
                <?= form_input([
                    'name' => 'password',
                    'type' => 'password',
                    'class' => 'form-control',
                    'autocomplete' => 'new-password'
                ]) ?>
            </div>
            <div class="col-12">
                <label class="form-label">Rol *</label>
                <select name="grupo" class="form-select">
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

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                <?= $editando ? 'Actualizar' : 'Crear usuario' ?>
            </button>
            <a href="<?= route_to('usuarios.index') ?>" class="btn btn-outline-secondary ms-2">
                Cancelar
            </a>
        </div>

        <?= form_close() ?>
    </div>
</div>

<?= $this->endSection() ?>