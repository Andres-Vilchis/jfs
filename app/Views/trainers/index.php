<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var list<array{id: int, nombre: string, apellidos: string, correo: string|null, telefono: string|null, especialidad: string|null, nivel: string, activo: int, total_clases: int|string}> $trainers
 * @var string $fecha_formateada
 */
?>

<!-- Encabezado -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0">Trainers</h5>
        <small class="text-muted"><?= $fecha_formateada ?></small>
    </div>
    <span class="text-muted small">
        <a href="<?= route_to('trainers.crear') ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-person-plus-fill me-1"></i> Nuevo trainer
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
    <?php if (empty($trainers)): ?>
        <div class="col-12 text-center text-muted py-5">Sin trainers registrados aún</div>
    <?php else: ?>
        <?php foreach ($trainers as $t): ?>
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card h-100 <?= $t['activo'] ? '' : 'opacity-50' ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-0"><?= esc($t['nombre'] . ' ' . $t['apellidos']) ?></h6>
                                <small class="text-muted"><?= esc($t['especialidad'] ?? 'Sin especialidad') ?></small>
                            </div>
                            <span class="badge <?= $t['activo'] ? 'bg-success' : 'bg-secondary' ?>">
                                <?= $t['activo'] ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </div>

                        <div class="d-flex gap-3 small text-muted mt-3">
                            <span>
                                <i class="bi bi-bar-chart-fill me-1"></i>
                                <?= ucfirst($t['nivel']) ?>
                            </span>
                            <span>
                                <i class="bi bi-calendar-week me-1"></i>
                                <?= $t['total_clases'] ?> clase(s)
                            </span>
                        </div>

                        <?php if ($t['correo']): ?>
                            <div class="small text-muted mt-1">
                                <i class="bi bi-envelope me-1"></i><?= esc($t['correo']) ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($t['telefono']): ?>
                            <div class="small text-muted mt-1">
                                <i class="bi bi-phone me-1"></i><?= esc($t['telefono']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <a href="<?= route_to('trainers.editar', $t['id']) ?>"
                            class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                        <form action="<?= route_to('trainers.toggle', $t['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm <?= $t['activo'] ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                                <i class="bi bi-<?= $t['activo'] ? 'pause-circle' : 'play-circle' ?>"></i>
                                <?= $t['activo'] ? 'Desactivar' : 'Activar' ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>