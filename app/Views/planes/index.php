<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var list<array{id: int, nombre: string, descripcion: string|null, precio: float|string, duracion_dias: int, beneficios: string|null, activo: int}> $planes
 * @var string $fecha_formateada
 */
?>

<!-- Encabezado -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0">Planes de membresía</h5>
        <small class="text-muted"><?= $fecha_formateada ?></small>
    </div>
    <span class="text-muted small">
    <a href="<?= route_to('planes.crear') ?>" class="btn btn-sm btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Nuevo plan
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

<div class="row g-3">
    <?php if (empty($planes)): ?>
        <div class="col-12 text-center text-muted py-5">Sin planes registrados aún</div>
    <?php else: ?>
        <?php foreach ($planes as $p): ?>
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card h-100 <?= $p['activo'] ? '' : 'opacity-50' ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="card-title mb-1"><?= esc($p['nombre']) ?></h6>
                            <span class="badge <?= $p['activo'] ? 'bg-success' : 'bg-secondary' ?>">
                                <?= $p['activo'] ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </div>
                        <div class="fs-4 fw-bold text-primary my-2">
                            $<?= number_format($p['precio'], 2) ?>
                        </div>
                        <div class="text-muted small mb-2">
                            <i class="bi bi-calendar-check me-1"></i>
                            <?= $p['duracion_dias'] ?> días de vigencia
                        </div>
                        <?php if ($p['descripcion']): ?>
                            <p class="small text-muted mb-2"><?= esc($p['descripcion']) ?></p>
                        <?php endif; ?>
                        <?php if ($p['beneficios']): ?>
                            <div class="small">
                                <i class="bi bi-check-circle-fill text-success me-1"></i>
                                <?= nl2br(esc($p['beneficios'])) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <a href="<?= route_to('planes.editar', $p['id']) ?>"
                            class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                        <form action="<?= route_to('planes.toggle', $p['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm <?= $p['activo'] ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                                <i class="bi bi-<?= $p['activo'] ? 'pause-circle' : 'play-circle' ?>"></i>
                                <?= $p['activo'] ? 'Desactivar' : 'Activar' ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>