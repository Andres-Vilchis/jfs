<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
/**
 * @var array $cliente
 * @var list<array{fecha_pago:string,plan_nombre:string|null,monto:float,fecha_vencimiento_generada:string|null,notas:string|null}> $pagos
 */
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">
        Historial de pagos
        <small class="text-muted fw-normal">— <?= esc($cliente['nombre'] . ' ' . $cliente['apellidos']) ?></small>
    </h5>
    <a href="<?= route_to('pagos.index') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body px-0 py-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="small">
                    <tr>
                        <th style="font-size:.80rem">Fecha pago</th>
                        <th style="font-size:.80rem">Plan</th>
                        <th class="text-end" style="font-size:.80rem">Monto</th>
                        <th class="text-center" style="font-size:.80rem">Vencimiento generado</th>
                        <th style="font-size:.80rem">Notas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pagos)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4 small">Sin pagos registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pagos as $p): ?>
                            <tr>
                                <td class="align-middle" style="font-size:.75rem">
                                    <?= date('d/m/Y', strtotime($p['fecha_pago'])) ?>
                                </td>
                                <td class="align-middle" style="font-size:.75rem">
                                    <?= esc($p['plan_nombre'] ?? '—') ?>
                                </td>
                                <td class="text-end align-middle" style="font-size:.75rem">
                                    $<?= number_format((float)$p['monto'], 2) ?>
                                </td>
                                <td class="text-center align-middle" style="font-size:.75rem">
                                    <?= $p['fecha_vencimiento_generada']
                                        ? date('d/m/Y', strtotime($p['fecha_vencimiento_generada']))
                                        : '<span class="text-muted">—</span>' ?>
                                </td>
                                <td class="align-middle" style="font-size:.75rem">
                                    <?= esc($p['notas'] ?? '—') ?>
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