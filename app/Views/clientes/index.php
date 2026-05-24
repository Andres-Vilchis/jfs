<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var array $clientes
 */
?>

<!-- Toast de contacto (reutilizable) -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
    <div id="contactoToast" class="toast align-items-center border-0 shadow" role="alert"
        aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
        <div class="toast-header">
            <i class="bi bi-person-circle me-2 text-primary"></i>
            <strong class="me-auto" id="toast-nombre">—</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Cerrar"></button>
        </div>
        <div class="toast-body d-flex flex-column gap-2">
            <a id="toast-email" href="#" class="btn btn-sm btn-outline-primary w-100 text-start">
                <i class="bi bi-envelope-fill me-2"></i>
                <span id="toast-email-txt">—</span>
            </a>
            <a id="toast-tel" href="#" class="btn btn-sm btn-outline-success w-100 text-start">
                <i class="bi bi-telephone-fill me-2"></i>
                <span id="toast-tel-txt">—</span>
            </a>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Clientes</h5>
    <a href="<?= route_to('clientes.crear') ?>" class="btn btn-sm btn-primary">
        <i class="bi bi-person-plus-fill me-1"></i> Nuevo cliente
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body px-0 py-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Plan</th>
                        <th>Nivel</th>
                        <th>Vencimiento</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Sin clientes registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $c):
                            $primerApellido = explode(' ', trim($c['apellidos']))[0];
                            $correo   = $c['correo']   ?? '';
                            $telefono = $c['telefono'] ?? '';
                            $nombreCompleto = esc($c['nombre'] . ' ' . $c['apellidos']);
                        ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold d-flex align-items-center gap-1">
                                        <button type="button"
                                            class="btn btn-link btn-sm p-0 text-secondary"
                                            title="Contacto"
                                            onclick="abrirToastContacto(
                                                '<?= addslashes($nombreCompleto) ?>',
                                                '<?= addslashes($correo) ?>',
                                                '<?= addslashes($telefono) ?>'
                                            )">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <?= esc($c['nombre'] . ' ' . $primerApellido) ?>
                                    </div>
                                </td>
                                <td><?= esc($c['plan_nombre'] ?? '—') ?></td>
                                <td><span class="badge bg-secondary"><?= esc($c['nivel']) ?></span></td>
                                <td><?= $c['fecha_vencimiento'] ?? '—' ?></td>
                                <td class="text-end">
                                    <a href="<?= route_to('clientes.editar', $c['id']) ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?= route_to('clientes.desactivar', $c['id']) ?>"
                                        method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('¿Desactivar cliente?')">
                                            <i class="bi bi-person-x"></i>
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

<?= $this->section('scripts') ?>
<script>
function abrirToastContacto(nombre, correo, telefono) {
    document.getElementById('toast-nombre').textContent    = nombre;
    document.getElementById('toast-email-txt').textContent = correo  || 'Sin correo';
    document.getElementById('toast-tel-txt').textContent   = telefono || 'Sin teléfono';

    const emailBtn = document.getElementById('toast-email');
    const telBtn   = document.getElementById('toast-tel');

    emailBtn.href = correo   ? 'mailto:' + correo   : '#';
    telBtn.href   = telefono ? 'tel:'    + telefono  : '#';

    emailBtn.classList.toggle('disabled', !correo);
    telBtn.classList.toggle('disabled',   !telefono);

    const toastEl = document.getElementById('contactoToast');
    bootstrap.Toast.getOrCreateInstance(toastEl).show();
}
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>