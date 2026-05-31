<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Jump Flow Studio</title>
    <link rel="icon" href="<?= base_url('favicon.ico') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Neonderthaw&family=Satisfy&display=swap">
    <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>

<body class="bg-dark">
    <!-- Alertas-->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger text-center py-1 mb-3">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    <!-- Inicio -->
    <div class="logo-circle">
        <div class="logo"><b><span>J</span>umpFlow<br>Stud<span>i</span>o</b></div>
    </div>
    <div class="login-box mt-5">
        <?= form_open('auth/login/attempt', ['class' => 'needs-validation', 'novalidate' => true]) ?>
        <p class="text-center neon fs-4">Inicia sesión</p>
        <div class="input-group input-group-sm mb-3 small" data-bs-theme="dark">
            <span class="input-group-text"><i class="bi bi-person-badge-fill"> </i></span>
            <?= form_input([
                'name'         => 'email',
                'id'           => 'email',
                'type'         => 'email',
                'class'        => 'form-control',
                'placeholder'  => 'Correo electrónico',
                'required'     => true,
                'autocomplete' => 'email',
            ]) ?>
        </div>
        <div class="input-group input-group-sm mb-4 small" data-bs-theme="dark">
            <span class="input-group-text"><i class="bi bi-lock text-muted"></i></span>
            <?= form_password([
                'name'        => 'pswd',
                'id'          => 'pswd',
                'class'       => 'form-control',
                'placeholder' => '••••••••',
                'required'    => true,
                'autocomplete' => 'current-password',
            ]) ?>
        </div>
        <div class="fluid d-grid text-center small">
            <button type="submit" class="btn btn-dark rotate-btn position-absolute top-50 start-50 translate-middle">
                Iniciar sesión
            </button>
        </div>
        <?= form_close() ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>