<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var array $clientes
 */
$ordenDias = ['lun', 'mar', 'mie', 'jue', 'vie', 'sab', 'dom'];
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Clientes</h5>
    <a href="<?= route_to('clientes.crear') ?>" class="btn btn-sm btn-primary">
        <i class="bi bi-person-plus-fill me-1"></i> Nuevo cliente
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body px-0 py-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Plan</th>
                        <th>Nivel</th>
                        <th>Clases</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Sin clientes registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $c): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?= esc($c['nombre'] . ' ' . $c['apellidos']) ?></div>
                                    <div class="text-muted small"><?= esc($c['correo'] ?? '—') ?></div>
                                </td>
                                <td><?= esc($c['telefono'] ?? '—') ?></td>
                                <td><?= esc($c['plan_nombre'] ?? '—') ?></td>
                                <td><span class="badge bg-secondary"><?= esc(ucfirst($c['nivel'])) ?></span></td>
                                <td>
                                    <?php if (!empty($c['dias_clases'])): ?>
                                        <?php foreach ($c['dias_clases'] as $dia): ?>
                                            <span class="badge bg-primary bg-opacity-10 text-primary me-1">
                                                <?= strtoupper($dia) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <a href="<?= route_to('clientes.editar', $c['id']) ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?= route_to('clientes.desactivar', $c['id']) ?>"
                                        method="post" class="d-inline"
                                        onsubmit="return confirm('¿Desactivar a <?= esc($c['nombre'], 'attr') ?>?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-person-x"></i>
                                        </button>
                                    </form>
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