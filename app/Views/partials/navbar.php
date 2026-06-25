<?php

/**
 * Variables esperadas:
 * @var callable $esActivo
 **/
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-semibold fs-6" href="<?= route_to('dashboard') ?>">
            <img src="<?= base_url('./assets/img/jfsgrbglg.png') ?>" class="rounded me-1" alt="JFS" height="30"> JF System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarMain" aria-controls="navbarMain"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="font-size:.87rem">
                <li class="nav-item">
                    <a class="nav-link <?= esActivo('dashboard') ?>" href="<?= route_to('dashboard') ?>">
                        <i class="bi bi-house-fill me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= esActivo('clientes') ?>" href="<?= route_to('clientes.index') ?>">
                        <i class="bi bi-people-fill me-1"></i> Clientes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= esActivo('trainers') ?>" href="<?= route_to('trainers.index') ?>">
                        <i class="bi bi-person-arms-up me-1"></i> Trainers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= esActivo('clases') ?>" href="<?= route_to('clases.index') ?>">
                        <i class="bi bi-calendar-event-fill me-1"></i> Clases
                    </a>
                </li>
                <?php if (auth()->user()->inGroup('admin')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= esActivo('planes') ?>" href="<?= route_to('planes.index') ?>">
                            <i class="bi bi-bag-dash-fill me-1"></i> Planes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= esActivo('usuarios') ?>" href="<?= route_to('usuarios.index') ?>">
                            <i class="bi bi-shield-lock-fill me-1"></i> Usuarios
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (auth()->user()->inGroup('admin', 'recepcionista')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= esActivo('pagos') ?>" href="<?= route_to('pagos.index') ?>">
                            <i class="bi bi-cash-coin me-1"></i> Pagos
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <form action="<?= route_to('logout') ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="small btn btn-outline-secondary btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>