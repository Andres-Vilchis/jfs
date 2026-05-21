<?php
$esActivo = function (string $ruta) {
    $segmento = service('request')->getUri()->getSegment(1);
    return $segmento === $ruta ? 'active fw-semibold' : '';
};
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Jump Flow Studio</title>
    <link rel="icon" href="<?= base_url('favicon.ico') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Neonderthaw&family=Satisfy&display=swap">
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
    <?= $this->renderSection('styles') ?>
</head>
<body>

    <?= $this->include('partials/navbar') ?>

    <main class="py-3 px-2 px-md-4">
        <?= $this->renderSection('content') ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>