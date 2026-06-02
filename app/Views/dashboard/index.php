<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var string $fecha_formateada
 * @var int    $totalClientes
 * @var int    $totalTrainers
 * @var int    $totalClases
 * @var int    $totalPlanes
 * @var list<array{nombre: string, apellidos: string, correo: string|null, plan_nombre: string|null, fecha_vencimiento: string}> $proximosVencer
 * @var int    $vencidos
 * @var list<array{nombre: string, apellidos: string, correo: string|null, plan_nombre: string|null, nivel: string, fecha_vencimiento: string|null}> $ultimosClientes
 * @var list<array{nombre: string, trainer_nombre: string|null, hora_inicio: string, hora_fin: string, nivel: string} > $proximaClase
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

<!-- Tarjetas de totales 
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <a class="text-decoration-none" href="<?= route_to('clientes.index') ?>">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-people-fill fs-2 text-primary"></i>
                    <div class="fs-3 fw-bold mt-1"><?= $totalClientes ?></div>
                    <div class="text-muted small">Clientes activos</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card text-center h-100 <?= $vencidos > 0 ? 'border-danger' : '' ?>">
            <div class="card-body">
                <i class="bi bi-exclamation-triangle-fill fs-2 <?= $vencidos > 0 ? 'text-danger-emphasis' : 'text-secondary' ?>"></i>
                <div class="fs-3 fw-bold mt-1"><?= $vencidos ?></div>
                <div class="text-muted small">Membresías vencidas</div>
            </div>
        </div>
    </div>
</div>
-->
<div class="row g-3">

    <!-- Clases de hoy -->
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
                            <?php if (!empty($proximaClase) && is_array($proximaClase)): ?>
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
                                        <div class="fw-semibold">
                                            Sin próxima clase
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos clientes -->
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
                                    <?php
                                    $hoy  = new DateTime();
                                    $venc = new DateTime($c['fecha_vencimiento'] ?? 'now');
                                    $diff = (int) $hoy->diff($venc)->format('%r%a');
                                    if ($diff < 0)      $badge = ['text-danger-emphasis',  'Vencido'];
                                    elseif ($diff <= 7) $badge = ['text-warning-emphasis', "Vence en {$diff}d"];
                                    else                $badge = ['text-success-emphasis', date('d/m/Y', strtotime($c['fecha_vencimiento']))];
                                    ?>
                                    <tr>
                                        <td style="font-size:.75rem">
                                            <div class="fw-semibold">
                                                <?= esc($c['nombre'] . ' ' . $c['apellidos']) ?>
                                            </div>
                                            <div class="text-muted">
                                                <?= esc($c['correo'] ?? '—') ?>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle" style="font-size:.75rem">
                                            <div class="text-secondary-emphasis">
                                                <?= esc($c['plan_nombre'] ?? '—') ?>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle" style="font-size:.75rem">
                                            <div class="text-secondary-emphasis">
                                                <?= ucfirst($c['nivel']) ?>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle" style="font-size:.75rem">
                                            <div class="text-success-emphasis">
                                                <?= esc($c['fecha_registro']) ?>
                                            </div>
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

    <!-- Próximos a vencer -->
    <?php if (! empty($proximosVencer)): ?>
        <div class="col-12">
            <div class="card border-warning-subtle">
                <div class="card-header border-0 pt-3 pb-2 px-4 bg-warning bg-opacity-10">
                    <h6 class="small mb-0 text-warning-emphasis">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        Próximos a vencer <small>(7 días)</small>
                    </h6>
                </div>
                <div class="card-body px-0 py-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="small">
                                <tr>
                                    <th style="font-size:.80rem">Cliente</th>
                                    <th style="font-size:.80rem">Plan</th>
                                    <th style="font-size:.80rem">Registro</th>
                                    <th style="font-size:.80rem">Vencimiento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($proximosVencer as $p): ?>
                                    <?php
                                    $dias = (int) (new DateTime())->diff(new DateTime($p['fecha_vencimiento']))->format('%r%a');
                                    ?>
                                    <tr>
                                        <td class="text-start align-middle" style="font-size:.75rem">
                                            <div class="fw-semibold">
                                                <?= esc($p['nombre'] . ' ' . $p['apellidos']) ?>
                                            </div>
                                            <div class="text-secondary-emphasis">
                                                <?= esc($p['telefono'] ?? '—') ?>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle" style="font-size:.75rem">
                                            <div class="text-secondary-emphasis">
                                                <?= esc($p['plan_nombre'] ?? '—') ?>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle" style="font-size:.75rem">
                                            <div class="text-success-emphasis">
                                                <?= date('d/m/Y', strtotime($p['fecha_registro'])) ?>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle" style="font-size:.75rem">
                                            <span class="fw-semibold <?= $dias <= 3 ? 'text-danger-emphasis' : 'text-warning-emphasis' ?>">
                                                <?= $dias === 0 ? 'Hoy' : "{$dias} día(s)" ?>
                                            </span>
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

<?= $this->endSection() ?>