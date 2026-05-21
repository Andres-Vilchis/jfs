<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var array $clases
 * @var string $fecha_formateada
 */
?>

<!-- Encabezado -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0">Clases</h5>
        <small class="text-muted"><?= $fecha_formateada ?></small>
    </div>
    <span class="text-muted small">
        <a href="<?= route_to('clases.crear') ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nueva clase
        </a>
    </span>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="row g-3">
    <?php if (empty($clases)): ?>
        <div class="col-12 text-center text-muted py-5">Sin clases registradas aún</div>
    <?php else: ?>
        <?php foreach ($clases as $c): ?>
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-0"><?= esc($c['nombre']) ?></h6>
                                <small class="text-muted">
                                    <i class="bi bi-person-arms-up me-1"></i>
                                    <?= esc($c['trainer_nombre'] ?? 'Sin trainer') ?>
                                </small>
                            </div>
                            <span class="badge bg-secondary"><?= ucfirst($c['nivel']) ?></span>
                        </div>

                        <div class="d-flex flex-wrap gap-2 small text-muted mt-3">
                            <span><i class="bi bi-clock me-1"></i>
                                <?= substr($c['hora_inicio'], 0, 5) ?> – <?= substr($c['hora_fin'], 0, 5) ?>
                            </span>
                            <span><i class="bi bi-people me-1"></i>
                                <?= $c['capacidad_max'] ?> lugares
                            </span>
                            <?php if ($c['salon']): ?>
                                <span><i class="bi bi-door-open me-1"></i>
                                    <?= esc($c['salon']) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="mt-2 small">
                            <?php foreach (explode(',', $c['dias_semana']) as $dia): ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary me-1">
                                    <?= strtoupper(trim($dia)) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>

                        <?php if ($c['descripcion']): ?>
                            <p class="small text-muted mt-2 mb-0"><?= esc($c['descripcion']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <a href="<?= route_to('clases.editar', $c['id']) ?>"
                            class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                        <form action="<?= route_to('clases.toggle', $c['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm <?= $c['activo'] ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                                <i class="bi bi-<?= $c['activo'] ? 'pause-circle' : 'play-circle' ?>"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>