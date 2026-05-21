<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var array $usuarios
 * @var string $fecha_formateada
 */
$badgeRol = [
    'admin'          => 'danger',
    'recepcionista'  => 'primary',
    'entrenador'     => 'success',
    'cliente'        => 'secondary',
];
?>

<!-- Encabezado -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0">Usuarios del sistema</h5>
        <small class="text-muted"><?= $fecha_formateada ?></small>
    </div>
    <span class="text-muted small">
    <a href="<?= route_to('usuarios.crear') ?>" class="btn btn-sm btn-primary">
        <i class="bi bi-person-plus-fill me-1"></i> Nuevo usuario
    </a>
    </span>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body px-0 py-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Sin usuarios</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $u): ?>
                            <?php $grupo = $u->grupos[0] ?? 'sin rol'; ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?= esc($u->username) ?></div>
                                    <?php if (auth()->id() == $u->id): ?>
                                        <small class="text-muted">— tú —</small>
                                    <?php endif; ?>
                                </td>
                                <td class="small"><?= esc($u->email) ?></td>
                                <td>
                                    <span class="badge bg-<?= $badgeRol[$grupo] ?? 'secondary' ?>">
                                        <?= ucfirst($grupo) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= $u->active ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= $u->active ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="<?= route_to('usuarios.editar', $u->id) ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if (auth()->id() != $u->id): ?>
                                        <form action="<?= route_to('usuarios.toggle', $u->id) ?>"
                                            method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button class="btn btn-sm <?= $u->active ? 'btn-outline-warning' : 'btn-outline-success' ?>"
                                                onclick="return confirm('¿Cambiar estado del usuario?')">
                                                <i class="bi bi-<?= $u->active ? 'pause-circle' : 'play-circle' ?>"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>