<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
/**
 * @var list<array{id:int,nombre:string,apellidos:string,plan_nombre:string|null,plan_precio:float|null,duracion_dias:int|null,fecha_vencimiento:string|null,ultimo_pago:string|null}> $clientes
 * @var list<array{id:int,nombre:string,precio:float,duracion_dias:int}> $planes
 */
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Pagos</h5>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show py-2 small">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show py-2">
        <ul class="mb-0 small">
            <?php foreach ((array) session()->getFlashdata('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body px-0 py-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="small">
                    <tr>
                        <th style="font-size:.80rem">Cliente</th>
                        <th class="text-center" style="font-size:.80rem">Plan</th>
                        <th class="text-center" style="font-size:.80rem">Próximo corte</th>
                        <th class="text-center" style="font-size:.80rem">Último pago</th>
                        <th class="text-center" style="font-size:.80rem">Pagar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4 small">Sin clientes activos</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $c):
                            $esPorClase = isset($c['duracion_dias']) && (int)$c['duracion_dias'] === 1;
                            $hoy        = new DateTime();
                            $venc       = $c['fecha_vencimiento'] ? new DateTime($c['fecha_vencimiento']) : null;
                            $diff       = $venc ? (int) $hoy->diff($venc)->format('%r%a') : null;

                            if ($esPorClase) {
                                $corteHtml = '<span class="text-muted">Por clase</span>';
                            } elseif ($venc === null) {
                                $corteHtml = '<span class="text-muted">—</span>';
                            } elseif ($diff < 0) {
                                $corteHtml = '<span class="text-danger">Vencido (' . date('d/m/Y', strtotime($c['fecha_vencimiento'])) . ')</span>';
                            } elseif ($diff <= 2) {
                                $corteHtml = '<span class="text-warning">' . date('d/m/Y', strtotime($c['fecha_vencimiento'])) . ' (' . $diff . 'd)</span>';
                            } elseif ($diff <= 5) {
                                $corteHtml = '<span class="text-success">' . date('d/m/Y', strtotime($c['fecha_vencimiento'])) . ' (' . $diff . 'd)</span>';
                            } else {
                                $corteHtml = '<span class="text-success-emphasis">' . date('d/m/Y', strtotime($c['fecha_vencimiento'])) . '</span>';
                            }
                        ?>
                            <tr>
                                <td class="align-middle" style="font-size:.70rem">
                                    <a href="<?= route_to('clientes.editar', $c['id']) ?>"
                                        class="fw-semibold text-decoration-none link-body-emphasis">
                                        <?= esc($c['nombre'] . ' ' . $c['apellidos']) ?>
                                    </a>
                                </td>
                                <td class="text-center align-middle" style="font-size:.70rem">
                                    <?= esc($c['plan_nombre'] ?? '—') ?>
                                    <?php if (isset($c['plan_precio'])): ?>
                                        <div class="text-muted" style="font-size:.70rem">$<?= number_format((float)$c['plan_precio'], 2) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center align-middle" style="font-size:.70rem"><?= $corteHtml ?></td>
                                <td class="text-center align-middle" style="font-size:.70rem">
                                    <?= $c['ultimo_pago'] ? date('d/m/Y', strtotime($c['ultimo_pago'])) : '<span class="text-muted">—</span>' ?>
                                </td>
                                <td class="text-center align-middle small">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary btn-pagar"
                                        style="font-size:.72rem"
                                        data-id="<?= $c['id'] ?>"
                                        data-nombre="<?= esc($c['nombre'] . ' ' . $c['apellidos'], 'attr') ?>"
                                        data-plan="<?= esc($c['plan_nombre'] ?? '—', 'attr') ?>"
                                        data-monto="<?= number_format((float)($c['plan_precio'] ?? 0), 2) ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalPagar">
                                        <i class="bi bi-currency-dollar"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ── Modal Pagar ──────────────────────────────────────────────── -->
<div class="modal fade" id="modalPagar" tabindex="-1" aria-labelledby="modalPagarLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title small" id="modalPagarLabel">
                    <i class="bi bi-cash-coin me-1 text-primary"></i>Registrar pago
                </h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <form id="formPagar" method="post" action="">
                <?= csrf_field() ?>
                <input type="hidden" name="origen" value="pagos">
                <div class="modal-body py-3 d-flex flex-column gap-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="min-width:90px">Cliente</span>
                        <input type="text" id="mp_nombre" class="form-control form-control-sm" readonly style="font-size:.75rem">
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="min-width:90px">Plan</span>
                        <input type="text" id="mp_plan" class="form-control form-control-sm" readonly style="font-size:.75rem">
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="min-width:90px">Costo</span>
                        <span class="input-group-text">$</span>
                        <input type="number" name="monto" id="mp_monto" class="form-control form-control-sm"
                            step="0.01" min="0" required style="font-size:.75rem">
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="min-width:90px">Fecha pago</span>
                        <input type="date" name="fecha_pago" id="mp_fecha"
                            class="form-control form-control-sm" required
                            value="<?= date('Y-m-d') ?>" style="font-size:.75rem">
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="min-width:90px">Notas</span>
                        <input type="text" name="notas" class="form-control form-control-sm"
                            placeholder="Opcional" style="font-size:.75rem">
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-floppy-fill me-1"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalPagar = document.getElementById('modalPagar');
        modalPagar.addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            document.getElementById('mp_nombre').value = btn.dataset.nombre;
            document.getElementById('mp_plan').value = btn.dataset.plan;
            document.getElementById('mp_monto').value = btn.dataset.monto;
            document.getElementById('formPagar').action =
                '<?= base_url('pagos/registrar') ?>/' + btn.dataset.id;
        });
    });
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>