<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var string $fecha_formateada
 * @var int    $totalClientes
 * @var int    $totalTrainers
 * @var int    $totalClases
 * @var int    $totalPlanes
 * @var array  $proximosVencer
 * @var int    $vencidos
 * @var array  $ultimosClientes
 * @var array  $clasesHoy
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

<!-- Tarjetas de totales -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="bi bi-people-fill fs-2 text-primary"></i>
                <div class="fs-3 fw-bold mt-1"><?= $totalClientes ?></div>
                <div class="text-muted small">Clientes activos</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="bi bi-person-arms-up fs-2 text-success"></i>
                <div class="fs-3 fw-bold mt-1"><?= $totalTrainers ?></div>
                <div class="text-muted small">Trainers</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="bi bi-calendar-event-fill fs-2 text-warning"></i>
                <div class="fs-3 fw-bold mt-1"><?= $totalClases ?></div>
                <div class="text-muted small">Clases activas</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card text-center h-100 <?= $vencidos > 0 ? 'border-danger' : '' ?>">
            <div class="card-body">
                <i class="bi bi-exclamation-triangle-fill fs-2 <?= $vencidos > 0 ? 'text-danger' : 'text-secondary' ?>"></i>
                <div class="fs-3 fw-bold mt-1"><?= $vencidos ?></div>
                <div class="text-muted small">Membresías vencidas</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">

    <!-- Últimos clientes -->
    <div class="col-12 col-xl-7">
        <div class="card h-100">
            <div class="card-header border-0 pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Últimos clientes registrados</h6>
                <a href="<?= route_to('clientes.index') ?>" class="btn btn-sm btn-link text-decoration-none p-0">
                    Ver todos
                </a>
            </div>
            <div class="card-body px-0 py-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="small">
                            <tr>
                                <th>Cliente</th>
                                <th>Plan</th>
                                <th>Nivel</th>
                                <th>Vencimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ultimosClientes)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Sin registros aún</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($ultimosClientes as $c): ?>
                            <?php
                                $hoy  = new DateTime();
                                $venc = new DateTime($c['fecha_vencimiento'] ?? 'now');
                                $diff = (int) $hoy->diff($venc)->format('%r%a');
                                if ($diff < 0)      $badge = ['bg-danger',  'Vencido'];
                                elseif ($diff <= 7) $badge = ['bg-warning text-dark', "Vence en {$diff}d"];
                                else                $badge = ['bg-success', date('d/m/Y', strtotime($c['fecha_vencimiento']))];
                            ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold" style="font-size:.88rem">
                                        <?= esc($c['nombre'] . ' ' . $c['apellidos']) ?>
                                    </div>
                                    <div class="text-muted" style="font-size:.75rem">
                                        <?= esc($c['correo'] ?? '—') ?>
                                    </div>
                                </td>
                                <td class="small"><?= esc($c['plan_nombre'] ?? '—') ?></td>
                                <td>
                                    <span class="badge bg-secondary"><?= ucfirst($c['nivel']) ?></span>
                                </td>
                                <td>
                                    <span class="badge <?= $badge[0] ?>"><?= $badge[1] ?></span>
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

    <!-- Clases de hoy -->
    <div class="col-12 col-xl-5">
        <div class="card h-100">
            <div class="card-header border-0 pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Clases de hoy</h6>
                <a href="<?= route_to('clases.index') ?>" class="btn btn-sm btn-link text-decoration-none p-0">
                    Ver todas
                </a>
            </div>
            <div class="card-body px-0 py-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="small">
                            <tr>
                                <th>Clase</th>
                                <th>Horario</th>
                                <th>Nivel</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($clasesHoy)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    Sin clases hoy
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($clasesHoy as $cl): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold" style="font-size:.87rem">
                                        <?= esc($cl['nombre']) ?>
                                    </div>
                                    <div class="text-muted" style="font-size:.75rem">
                                        <?= esc($cl['trainer_nombre'] ?? 'Sin trainer') ?>
                                    </div>
                                </td>
                                <td class="small">
                                    <?= substr($cl['hora_inicio'], 0, 5) ?> – <?= substr($cl['hora_fin'], 0, 5) ?>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?= ucfirst($cl['nivel']) ?></span>
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
        <div class="card border-warning">
            <div class="card-header border-0 pt-3 pb-2 px-4 bg-warning bg-opacity-10">
                <h6 class="mb-0 text-warning">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    Membresías próximas a vencer (7 días)
                </h6>
            </div>
            <div class="card-body px-0 py-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="small">
                            <tr>
                                <th>Cliente</th>
                                <th>Plan</th>
                                <th>Vence</th>
                                <th>Días restantes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proximosVencer as $p): ?>
                            <?php
                                $dias = (int) (new DateTime())->diff(new DateTime($p['fecha_vencimiento']))->format('%r%a');
                            ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold" style="font-size:.88rem">
                                        <?= esc($p['nombre'] . ' ' . $p['apellidos']) ?>
                                    </div>
                                    <div class="text-muted" style="font-size:.75rem">
                                        <?= esc($p['correo'] ?? '—') ?>
                                    </div>
                                </td>
                                <td class="small"><?= esc($p['plan_nombre'] ?? '—') ?></td>
                                <td class="small"><?= date('d/m/Y', strtotime($p['fecha_vencimiento'])) ?></td>
                                <td>
                                    <span class="badge <?= $dias <= 3 ? 'bg-danger' : 'bg-warning text-dark' ?>">
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