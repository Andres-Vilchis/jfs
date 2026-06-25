<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var string $fecha_formateada
 * @var int    $totalClientes
 * @var int    $totalTrainers
 * @var int    $totalClases
 * @var int    $totalPlanes
 * @var int    $vencidos
 * @var list<array{id:int,nombre:string,apellidos:string,telefono:string|null,plan_nombre:string|null,plan_precio:float|null,duracion_dias:int|null,fecha_vencimiento:string|null}> $alertasVencimiento
 * @var list<array{nombre:string,apellidos:string,correo:string|null,plan_nombre:string|null,nivel:string,fecha_vencimiento:string|null,fecha_registro:string}> $ultimosClientes
 * @var array{nombre?:string,trainer_nombre?:string|null,hora_inicio?:string,hora_fin?:string,nivel?:string} $proximaClase
 */
?>

<!-- Encabezado -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0">Dashboard</h5>
        <small class="text-muted"><?= $fecha_formateada ?></small>
    </div>
    <span class="text-muted small">
        <i class="bi bi-person-circle me-1"></i>
        <?= esc(auth()->user()->username) ?>
    </span>
</div>

<div class="row g-3">

    <!-- Próxima clase -->
    <div class="col-12 col-xl-5">
        <div class="card h-100">
            <div class="card-header border-0 pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                <h6 class="small mb-0">Próxima clase</h6>
                <a href="<?= route_to('clases.index') ?>" class="btn btn-sm btn-link text-decoration-none p-0" style="font-size:.75rem">
                    Ver todas
                </a>
            </div>
            <div class="card-body px-0 py-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-sm mb-0">
                        <thead class="small">
                            <tr>
                                <th style="font-size:.80rem">Clase</th>
                                <th style="font-size:.80rem">Horario</th>
                                <th style="font-size:.80rem">Nivel</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($proximaClase)): ?>
                                <tr>
                                    <td style="font-size:.75rem">
                                        <div class="fw-semibold">
                                            <?= esc($proximaClase['nombre'] ?? '') ?>
                                        </div>
                                        <div class="text-secondary-emphasis">
                                            Trainer: <?= esc($proximaClase['trainer_nombre'] ?? 'Sin trainer') ?>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle" style="font-size:.75rem">
                                        <?= isset($proximaClase['hora_inicio']) ? substr($proximaClase['hora_inicio'], 0, 5) : '--:--' ?>
                                        –
                                        <?= isset($proximaClase['hora_fin']) ? substr($proximaClase['hora_fin'], 0, 5) : '--:--' ?>
                                    </td>
                                    <td class="text-center align-middle" style="font-size:.75rem">
                                        <?= ucfirst($proximaClase['nivel'] ?? '-') ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4" style="font-size:.75rem">
                                        <div class="fw-semibold">Sin próxima clase</div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos clientes registrados -->
    <div class="col-12 col-xl-7">
        <div class="card h-100">
            <div class="card-header border-0 pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                <h6 class="small mb-0">Últimos clientes registrados</h6>
                <a href="<?= route_to('clientes.index') ?>" class="btn btn-sm btn-link text-decoration-none p-0" style="font-size:.75rem">
                    Ver todos
                </a>
            </div>
            <div class="card-body px-0 py-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-sm mb-0">
                        <thead class="small">
                            <tr>
                                <th style="font-size:.80rem">Cliente</th>
                                <th style="font-size:.80rem">Plan</th>
                                <th style="font-size:.80rem">Nivel</th>
                                <th style="font-size:.80rem">Registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ultimosClientes)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4 small">Sin registros aún</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($ultimosClientes as $c): ?>
                                    <tr>
                                        <td style="font-size:.75rem">
                                            <div class="fw-semibold"><?= esc($c['nombre'] . ' ' . $c['apellidos']) ?></div>
                                            <div class="text-muted"><?= esc($c['correo'] ?? '—') ?></div>
                                        </td>
                                        <td class="text-center align-middle" style="font-size:.75rem">
                                            <div class="text-secondary-emphasis"><?= esc($c['plan_nombre'] ?? '—') ?></div>
                                        </td>
                                        <td class="text-center align-middle" style="font-size:.75rem">
                                            <div class="text-secondary-emphasis"><?= ucfirst($c['nivel']) ?></div>
                                        </td>
                                        <td class="text-center align-middle" style="font-size:.75rem">
                                            <div class="text-success-emphasis"><?= esc($c['fecha_registro']) ?></div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas de vencimiento: vencidos + próximos 5 días (excluye planes por clase) -->
    <?php if (! empty($alertasVencimiento)): ?>
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0 pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="small mb-0">
                        <i class="bi bi-bell-fill me-1 text-warning"></i>
                        Alertas de vencimiento
                        <?php if ($vencidos > 0): ?>
                            <span class="badge bg-danger ms-1"><?= $vencidos ?> vencido<?= $vencidos > 1 ? 's' : '' ?></span>
                        <?php endif; ?>
                    </h6>
                    <a href="<?= route_to('pagos.index') ?>" class="btn btn-sm btn-link text-decoration-none p-0" style="font-size:.75rem">
                        Módulo pagos
                    </a>
                </div>
                <div class="card-body px-0 py-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="small">
                                <tr>
                                    <th style="font-size:.80rem">Cliente</th>
                                    <th class="text-center" style="font-size:.80rem">Plan</th>
                                    <th class="text-center" style="font-size:.80rem">Vencimiento</th>
                                    <th class="text-center" style="font-size:.80rem">Pagar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alertasVencimiento as $a):
                                    $hoy  = new DateTime();
                                    $venc = $a['fecha_vencimiento'] ? new DateTime($a['fecha_vencimiento']) : null;
                                    $diff = $venc ? (int) $hoy->diff($venc)->format('%r%a') : null;

                                    if ($diff === null || $diff < 0) {
                                        // Rojo: vencido
                                        $rowStyle   = 'background-color:rgba(220,53,69,.10);';
                                        $textClass  = 'text-danger fw-semibold';
                                        $badgeText  = 'Vencido';
                                    } elseif ($diff <= 2) {
                                        // Amarillo: hoy a 2 días
                                        $rowStyle   = 'background-color:rgba(255,193,7,.13);';
                                        $textClass  = 'text-warning fw-semibold';
                                        $badgeText  = $diff === 0 ? 'Hoy' : "En {$diff}d";
                                    } else {
                                        // Verde: 3 a 5 días
                                        $rowStyle   = 'background-color:rgba(25,135,84,.08);';
                                        $textClass  = 'text-success fw-semibold';
                                        $badgeText  = "En {$diff}d";
                                    }
                                ?>
                                    <tr style="<?= $rowStyle ?>">
                                        <td class="align-middle" style="font-size:.75rem">
                                            <div class="fw-semibold"><?= esc($a['nombre'] . ' ' . $a['apellidos']) ?></div>
                                            <div class="text-muted" style="font-size:.70rem"><?= esc($a['telefono'] ?? '—') ?></div>
                                        </td>
                                        <td class="text-center align-middle" style="font-size:.75rem">
                                            <?= esc($a['plan_nombre'] ?? '—') ?>
                                        </td>
                                        <td class="text-center align-middle" style="font-size:.75rem">
                                            <span class="<?= $textClass ?>"><?= $badgeText ?></span>
                                            <?php if ($venc): ?>
                                                <div class="text-muted" style="font-size:.70rem">
                                                    <?= date('d/m/Y', strtotime($a['fecha_vencimiento'])) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                style="font-size:.72rem"
                                                data-id="<?= $a['id'] ?>"
                                                data-nombre="<?= esc($a['nombre'] . ' ' . $a['apellidos'], 'attr') ?>"
                                                data-plan="<?= esc($a['plan_nombre'] ?? '—', 'attr') ?>"
                                                data-monto="<?= number_format((float)($a['plan_precio'] ?? 0), 2) ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalPagarDash">
                                                <i class="bi bi-currency-dollar"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<!-- ── Modal Pagar desde Dashboard ─────────────────────────────── -->
<div class="modal fade" id="modalPagarDash" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title small">
                    <i class="bi bi-cash-coin me-1 text-primary"></i>Registrar pago
                </h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <form id="formPagarDash" method="post" action="">
                <?= csrf_field() ?>
                <input type="hidden" name="origen" value="dashboard">
                <div class="modal-body py-3 d-flex flex-column gap-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="min-width:90px">Cliente</span>
                        <input type="text" id="mpd_nombre" class="form-control form-control-sm" readonly style="font-size:.75rem">
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="min-width:90px">Plan</span>
                        <input type="text" id="mpd_plan" class="form-control form-control-sm" readonly style="font-size:.75rem">
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="min-width:90px">Costo</span>
                        <span class="input-group-text">$</span>
                        <input type="number" name="monto" id="mpd_monto" class="form-control form-control-sm"
                            step="0.01" min="0" required style="font-size:.75rem">
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="min-width:90px">Fecha pago</span>
                        <input type="date" name="fecha_pago" id="mpd_fecha"
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
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
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
        const modalDash = document.getElementById('modalPagarDash');
        if (modalDash) {
            modalDash.addEventListener('show.bs.modal', function(e) {
                const btn = e.relatedTarget;
                document.getElementById('mpd_nombre').value = btn.dataset.nombre;
                document.getElementById('mpd_plan').value = btn.dataset.plan;
                document.getElementById('mpd_monto').value = btn.dataset.monto;
                document.getElementById('formPagarDash').action =
                    '<?= base_url('pagos/registrar') ?>/' + btn.dataset.id;
            });
        }
    });
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>