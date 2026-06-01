<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var list<\CodeIgniter\Shield\Entities\User> $usuarios
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
    <div class="alert alert-success alert-dismissible fade show py-2">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show py-2">
        <ul class="mb-0 small">
            <?php foreach (session()->getFlashdata('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body px-0 py-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th class="text-start">Usuario</th>
                        <th class="text-center">Rol</th>
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
                                    <div class="fw-semibold" style="font-size:.75rem">
                                        <?= esc($u->username) ?>
                                    </div>
                                    <small class="text-muted">
                                        <?php if (auth()->id() == $u->id): ?>
                                            — Tú: &nbsp;
                                        <?php endif; ?>
                                        <?= esc($u->email) ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="small text-<?= $badgeRol[$grupo] ?? 'secondary' ?>-emphasis">
                                        <?= ucfirst($grupo) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="<?= route_to('usuarios.editar', $u->id) ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
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