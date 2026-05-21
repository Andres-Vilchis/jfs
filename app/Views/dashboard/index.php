<?php
/**
 * Variables esperadas:
 * @var callable $fecha_formateada
 **/
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid text-center bg-body-tertiary" style="margin:0 !important">
    <h5 class="text-warning-emphasis" style="margin:0 !important">DASHBOARD</h5>
</div>

<div class="row">
    <div class="col bg-secondary-subtle text-info-emphasis">
        <span class="small m-2">¡Hola, <?= esc(auth()->user()->username) ?>!</span>
    </div>
    <div class="col text-end">
        <span class="small m-2"><?= $fecha_formateada ?></span>
    </div>
</div>

<div class="row g-3 mt-1">
    <!-- Últimos clientes -->
    <div class="col-12 col-xl-7">
        <div class="card h-100">
            <div class="card-header border-0 pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Últimos clientes registrados</h6>
                <a href="<?= site_url('clientes') ?>" class="btn btn-sm btn-link text-decoration-none p-0">Ver todos</a>
            </div>
            <div class="card-body px-0 py-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="small text-center">
                            <tr>
                                <th>Cliente</th>
                                <th>Plan</th>
                                <th>Nivel</th>
                                <th>Vencimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Sin registros aún</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Clases -->
    <div class="col-12 col-xl-5">
        <div class="card h-100">
            <div class="card-header border-0 pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Clases disponibles</h6>
                <a href="<?= site_url('clases') ?>" class="btn btn-sm btn-link text-decoration-none p-0">Ver todas</a>
            </div>
            <div class="card-body px-0 py-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Clase</th>
                                <th>Horario</th>
                                <th>Nivel</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Sin clases registradas</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>