<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var array  $clase
 * @var array  $inscritos
 * @var array  $clientesLibres
 * @var int    $disponibles
 */
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0">Participantes — <?= esc($clase['nombre']) ?></h5>
        <small class="text-muted">
            <i class="bi bi-clock me-1"></i>
            <?= substr($clase['hora_inicio'], 0, 5) ?> – <?= substr($clase['hora_fin'], 0, 5) ?>
            &nbsp;|&nbsp;
            <?php foreach (explode(',', $clase['dias_semana']) as $dia): ?>
                <span class="badge bg-primary bg-opacity-10 text-primary"><?= strtoupper(trim($dia)) ?></span>
            <?php endforeach; ?>
        </small>
    </div>
    <a href="<?= route_to('clases.index') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<!-- Capacidad -->
<?php
$totalInscritos = count($inscritos);
$pct = $clase['capacidad_max'] > 0 ? round(($totalInscritos / $clase['capacidad_max']) * 100) : 0;
$barColor = $disponibles <= 0 ? 'bg-danger' : ($disponibles <= 3 ? 'bg-warning' : 'bg-success');
?>
<div class="card mb-4">
    <div class="card-body py-2">
        <div class="d-flex justify-content-between align-items-center mb-1 small">
            <span>Ocupación: <strong><?= $totalInscritos ?>/<?= $clase['capacidad_max'] ?></strong></span>
            <span class="<?= $disponibles <= 0 ? 'text-danger fw-semibold' : 'text-muted' ?>">
                <?= $disponibles <= 0 ? 'Clase llena' : "{$disponibles} lugar(es) disponible(s)" ?>
            </span>
        </div>
        <div class="progress" style="height: 8px;">
            <div class="progress-bar <?= $barColor ?>" style="width: <?= min($pct, 100) ?>%"></div>
        </div>
    </div>
</div>

<div class="row g-3">

    <!-- Participantes inscritos -->
    <div class="col-12 col-lg-6">
        <div class="card h-100">
            <div class="card-header border-0 pt-3 pb-2 px-4">
                <h6 class="small mb-0">
                    <i class="bi bi-people-fill me-1 text-primary"></i>
                    Inscritos (<?= $totalInscritos ?>)
                </h6>
            </div>
            <div class="card-body px-0 py-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="small">
                            <tr>
                                <th>Cliente</th>
                                <th>Nivel</th>
                                <th>Inscrito</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($inscritos)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Sin participantes inscritos
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($inscritos as $p): ?>
                                    <tr>
                                        <td class="fw-semibold" style="font-size:.85rem">
                                            <?= esc($p['nombre'] . ' ' . $p['apellidos']) ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge bg-secondary small">
                                                <?= ucfirst($p['nivel']) ?>
                                            </span>
                                        </td>
                                        <td class="text-muted align-middle" style="font-size:.78rem">
                                            <?= date('d/m/Y', strtotime($p['fecha_inscripcion'])) ?>
                                        </td>
                                        <td class="text-end align-middle">
                                            <form action="<?= route_to('clases.quitarParticipante', $clase['id'], $p['id']) ?>"
                                                method="post" class="d-inline"
                                                onsubmit="return confirm('¿Quitar a <?= addslashes(esc($p['nombre'] . ' ' . $p['apellidos'])) ?> de esta clase?')">
                                                <?= csrf_field() ?>
                                                <button class="btn btn-sm btn-outline-danger" title="Quitar">
                                                    <i class="bi bi-x-lg"></i>
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
    </div>

    <!-- Agregar participantes -->
    <div class="col-12 col-lg-6">
        <div class="card h-100">
            <div class="card-header border-0 pt-3 pb-2 px-4">
                <h6 class="small mb-0">
                    <i class="bi bi-person-plus-fill me-1 text-success"></i>
                    Agregar participante
                </h6>
            </div>
            <div class="card-body px-0 py-0">
                <?php if ($disponibles <= 0): ?>
                    <div class="alert alert-danger mx-3 mt-3">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        La clase alcanzó su capacidad máxima (<?= $clase['capacidad_max'] ?> participantes).
                    </div>
                <?php elseif (empty($clientesLibres)): ?>
                    <p class="text-muted text-center py-4 small">
                        Todos los clientes activos ya están inscritos en esta clase.
                    </p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="small">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Nivel</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clientesLibres as $cl): ?>
                                    <tr>
                                        <td style="font-size:.85rem">
                                            <div class="fw-semibold">
                                                <?= esc($cl['nombre'] . ' ' . $cl['apellidos']) ?>
                                            </div>
                                            <div class="text-muted" style="font-size:.75rem">
                                                <?= esc($cl['correo'] ?? '') ?>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge bg-secondary small">
                                                <?= ucfirst($cl['nivel']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end align-middle">
                                            <form action="<?= route_to('clases.agregarParticipante', $clase['id']) ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="cliente_id" value="<?= $cl['id'] ?>">
                                                <button class="btn btn-sm btn-outline-success" title="Agregar">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>