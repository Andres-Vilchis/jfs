<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var list<array{id: int, nombre: string, apellidos: string, correo: string|null, telefono: string|null, plan_nombre: string|null, nivel: string, fecha_vencimiento: string|null}> $clientes
 * @var string $fecha_formateada
 */
?>

<!-- Encabezado -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0">Clientes</h5>
        <small class="text-muted"><?= $fecha_formateada ?></small>
    </div>
    <span class="text-muted small">
        <a href="<?= route_to('clientes.crear') ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-person-plus-fill me-1"></i> Nuevo cliente
        </a>
    </span>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body px-0 py-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-sm mb-0">
                <thead class="small">
                    <tr>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Plan</th>
                        <th>Nivel</th>
                        <th>Vencimiento</th>
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
                                    <div class="fw-semibold" style="font-size:.87rem"><?= esc($c['nombre'] . ' ' . $c['apellidos']) ?></div>
                                    <div class="text-muted small"><?= esc($c['correo'] ?? '—') ?></div>
                                </td>
                                <td class="small text-secondary-emphasis text-center align-middle"><?= esc($c['telefono'] ?? '—') ?></td>
                                <td class="small text-secondary-emphasis text-center align-middle"><?= esc($c['plan_nombre'] ?? '—') ?></td>
                                <td class="small text-secondary-emphasis text-center align-middle"><span><?= esc($c['nivel']) ?></span></td>
                                <td class="small text-secondary-emphasis text-center align-middle"><?= $c['fecha_vencimiento'] ?? '—' ?></td>
                                <td class="text-end">
                                    <a href="<?= route_to('clientes.editar', $c['id']) ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?= route_to('clientes.desactivar', $c['id']) ?>"
                                        method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('¿Desactivar cliente?')">
                                            <i class="bi bi-trash3-fill"></i>
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