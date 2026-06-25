<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
/**
 * @var list<array{id:int,nombre:string,apellidos:string,telefono:string|null,correo:string|null,plan_id:int|null,plan_nombre:string|null,plan_precio:float|null,duracion_dias:int|null,fecha_vencimiento:string|null,nivel:string,notas:string|null,dias_clases:string[]}> $clientes
 * @var list<array{id:int,nombre:string,precio:float,duracion_dias:int}> $planes
 */
?>

<!-- ── Toast de contacto ──────────────────────────────────────── -->
<div class="toast-container" style="position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); z-index:1200;">
    <div id="contactoToast" class="toast align-items-center border shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false" style="opacity:1 !important; min-width:260px; background:var(--bs-body-bg, #1a1a2e);">
        <div class="toast-header border-bottom">
            <i class="bi bi-person-lines-fill me-2 text-primary"></i>
            <strong class="me-auto" id="toast-nombre">—</strong>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="toast" aria-label="Cerrar"></button>
        </div>
        <div class="toast-body d-flex flex-column gap-2 py-3">
            <a id="toast-tel" href="#" class="btn btn-sm btn-outline-success w-100 text-start">
                <i class="bi bi-telephone-outbound-fill me-2"></i>
                <span id="toast-tel-txt">—</span>
            </a>
            <a id="toast-wa" href="#" target="_blank" class="btn btn-sm btn-outline-success w-100 text-start">
                <i class="bi bi-whatsapp me-2"></i>
                <span id="toast-wa-txt">—</span>
            </a>
            <a id="toast-email" href="#" class="btn btn-sm btn-outline-primary w-100 text-start">
                <i class="bi bi-envelope-fill me-2"></i>
                <span id="toast-email-txt">—</span>
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
    <div class="alert alert-success alert-dismissible fade show py-2">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show py-2">
        <ul class="mb-0 small">
            <?php foreach ((array) session()->getFlashdata('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body px-0 py-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead>
                    <tr>
                        <th class="text-start" style="font-size:.80rem">Cliente</th>
                        <th class="text-center" style="font-size:.80rem">Plan</th>
                        <th class="text-center" style="font-size:.80rem">Nivel</th>
                        <th class="text-center" style="font-size:.80rem">Clases</th>
                        <th class="text-center" style="font-size:.80rem">Pagar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Sin clientes registrados</td>
                            <!-- ↑ corregido: era colspan="3", ahora 5 para cubrir todas las columnas -->
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $c):
                            $primerApellido = explode(' ', trim($c['apellidos']))[0];

                            // ↓ corregido: null-check explícito antes de instanciar DateTime
                            $hoy  = new DateTime();
                            $venc = $c['fecha_vencimiento'] ? new DateTime($c['fecha_vencimiento']) : null;
                            $diff = $venc ? (int) $hoy->diff($venc)->format('%r%a') : null;

                            if ($diff === null) {
                                $badgeClass = 'text-muted';
                                $badgeText  = '—';
                            } elseif ($diff < 0) {
                                $badgeClass = 'text-danger-emphasis';
                                $badgeText  = 'Vencido';
                            } elseif ($diff <= 5) {
                                $badgeClass = 'text-warning-emphasis';
                                $badgeText  = "Vence en {$diff}d";
                            } else {
                                $badgeClass = 'text-success-emphasis';
                                $badgeText  = date('d/m/Y', strtotime($c['fecha_vencimiento']));
                            }
                        ?>
                            <tr>
                                <td style="width:1%; white-space:nowrap;">
                                    <div class="d-flex align-items-center gap-1 small">
                                        <!-- Tres puntos → Toast contacto -->
                                        <button type="button"
                                            class="btn btn-link btn-sm p-0 lh-1 text-info border-0 small"
                                            title="Contacto"
                                            onclick="abrirContacto(
                                                '<?= esc(addslashes($c['nombre'] . ' ' . $c['apellidos']), 'js') ?>',
                                                '<?= esc(addslashes($c['telefono'] ?? ''), 'js') ?>',
                                                '<?= esc(addslashes($c['correo']   ?? ''), 'js') ?>'
                                            )">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <!-- Nombre → abre Modal ficha -->
                                        <a href="#"
                                            class="text-decoration-none link-body-emphasis abrir-ficha small"
                                            data-bs-toggle="modal"
                                            data-bs-target="#fichaModal"
                                            data-cliente='<?= esc(json_encode($c), 'attr') ?>'>
                                            <?= esc($c['nombre'] . ' ' . $primerApellido) ?>
                                        </a>
                                    </div>
                                </td>
                                <td class="text-center align-middle small text-muted">
                                    <div class="fw-light small"><?= esc($c['plan_nombre'] ?? '—') ?></div>
                                    <!-- Badge de vencimiento bajo el nombre del plan -->
                                    <div class="<?= $badgeClass ?>" style="font-size:.70rem"><?= $badgeText ?></div>
                                </td>
                                <td class="text-center align-middle small text-muted">
                                    <div class="fw-light small"><?= esc(ucfirst($c['nivel'])) ?></div>
                                </td>
                                <td class="text-center align-middle small">
                                    <?php if (! empty($c['dias_clases'])): ?>
                                        <?php foreach ($c['dias_clases'] as $dia): ?>
                                            <span class="badge bg-primary bg-opacity-10 text-primary small me-1">
                                                <?= strtoupper(trim($dia)) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">- -</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        style="font-size:.72rem"
                                        data-id="<?= $c['id'] ?>"
                                        data-nombre="<?= esc($c['nombre'] . ' ' . $c['apellidos'], 'attr') ?>"
                                        data-plan="<?= esc($c['plan_nombre'] ?? '—', 'attr') ?>"
                                        data-monto="<?= number_format((float)($c['plan_precio'] ?? 0), 2) ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalPagarClientes">
                                        <i class="bi bi-currency-dollar"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ── Modal Ficha del Cliente ─────────────────────────────────── -->
<div class="modal fade" id="fichaModal" tabindex="-1" aria-labelledby="fichaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title" id="fichaModalLabel">
                    <i class="bi bi-person-vcard me-2"></i>Ficha del cliente
                </h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formFicha" method="post" action="">
                <?= csrf_field() ?>
                <div class="modal-body py-3">
                    <div class="d-flex flex-column gap-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Nombre</span>
                            <input type="text" name="nombre" id="m_nombre" class="form-control small" style="font-size:.75rem" placeholder="Nombre" required>
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Apellidos</span>
                            <input type="text" name="apellidos" id="m_apellidos" class="form-control small" style="font-size:.75rem" placeholder="Apellidos" required>
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Correo</span>
                            <input type="email" name="correo" id="m_correo" class="form-control small" style="font-size:.75rem" placeholder="correo@ejemplo.com">
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Teléfono</span>
                            <input type="text" name="telefono" id="m_telefono" class="form-control small" style="font-size:.75rem" placeholder="10 dígitos">
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Nacimiento</span>
                            <input type="date" name="fecha_nacimiento" id="m_fecha_nacimiento" class="form-control small" style="font-size:.75rem">
                        </div>
                        <div class="input-group input-group-sm small">
                            <span class="input-group-text">Género</span>
                            <select name="genero" id="m_genero" class="form-select small" style="font-size:.75rem">
                                <option value="">— —</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Nivel</span>
                            <select name="nivel" id="m_nivel" class="form-select" style="font-size:.75rem">
                                <option value="principiante">Principiante</option>
                                <option value="intermedio">Intermedio</option>
                                <option value="avanzado">Avanzado</option>
                            </select>
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Plan</span>
                            <select name="plan_id" id="m_plan_id" class="form-select small" style="font-size:.75rem">
                                <option value="">— Sin plan —</option>
                                <?php foreach ($planes as $p): ?>
                                    <option value="<?= $p['id'] ?>">
                                        <?= esc($p['nombre']) ?> — $<?= number_format($p['precio'], 2) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Periodo</span>
                            <input type="date" name="fecha_vencimiento" id="m_fecha_vencimiento" class="form-control small" style="font-size:.75rem">
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Notas</span>
                            <textarea name="notas" id="m_notas" class="form-control small" style="font-size:.75rem" rows="2" placeholder="Observaciones..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2 justify-content-between">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-floppy-fill me-1"></i> Guardar
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-diamond-fill me-1"></i>Cancelar
                        </button>
                    </div>
            </form>
            <form id="formBaja" method="post" action="" class="m-0">
                <?= csrf_field() ?>
                <button type="submit"
                    class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('Eliminar a este cliente? Esta acción lo eliminará del sistema.')">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </form>
        </div>
    </div>
</div>
</div>

<!-- ── Modal Pagar desde Clientes ──────────────────────────────── -->
<div class="modal fade" id="modalPagarClientes" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title small">
                    <i class="bi bi-cash-coin me-1 text-primary"></i>Registrar pago
                </h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <form id="formPagarClientes" method="post" action="">
                <?= csrf_field() ?>
                <input type="hidden" name="origen" value="clientes">
                <div class="modal-body py-3 d-flex flex-column gap-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="min-width:90px">Cliente</span>
                        <input type="text" id="mpc_nombre" class="form-control form-control-sm" readonly style="font-size:.75rem">
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="min-width:90px">Plan</span>
                        <input type="text" id="mpc_plan" class="form-control form-control-sm" readonly style="font-size:.75rem">
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="min-width:90px">Costo</span>
                        <span class="input-group-text">$</span>
                        <input type="number" name="monto" id="mpc_monto" class="form-control form-control-sm"
                            step="0.01" min="0" required style="font-size:.75rem">
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="min-width:90px">Fecha pago</span>
                        <input type="date" name="fecha_pago" id="mpc_fecha"
                            class="form-control form-control-sm" required
                            value="<?= date('Y-m-d') ?>" style="font-size:.75rem">
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="min-width:90px">Notas</span>
                        <input type="text" name="notas" class="form-control form-control-sm"
                            placeholder="Opcional" style="font-size:.75rem">
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-floppy-fill me-1"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
    /* ── Toast contacto ─────────────────────────────────────────── */
    function abrirContacto(nombre, telefono, correo) {
        document.getElementById('toast-nombre').textContent = nombre;
        document.getElementById('toast-tel-txt').textContent = telefono || 'Sin teléfono';
        document.getElementById('toast-wa-txt').textContent = telefono || 'Sin WhatsApp';
        document.getElementById('toast-email-txt').textContent = correo || 'Sin correo';

        const telBtn = document.getElementById('toast-tel');
        const waBtn = document.getElementById('toast-wa');
        const emailBtn = document.getElementById('toast-email');

        telBtn.href = telefono ? 'tel:' + telefono : '#';
        waBtn.href = telefono ? 'https://wa.me/+52' + telefono : '#';
        emailBtn.href = correo ? 'mailto:' + correo : '#';

        telBtn.classList.toggle('disabled', !telefono);
        waBtn.classList.toggle('disabled', !telefono);
        emailBtn.classList.toggle('disabled', !correo);

        bootstrap.Toast.getOrCreateInstance(
            document.getElementById('contactoToast')
        ).show();
    }

    document.addEventListener('DOMContentLoaded', function() {

        /* ── Modal ficha ────────────────────────────────────────── */
        const fichaModal = document.getElementById('fichaModal');
        fichaModal.addEventListener('show.bs.modal', function(event) {
            const trigger = event.relatedTarget;
            const c = JSON.parse(trigger.getAttribute('data-cliente'));
            document.getElementById('fichaModalLabel').innerHTML =
                '<i class="bi bi-person-vcard me-2"></i>' +
                escHtml(c.nombre) + ' ' + escHtml(c.apellidos);
            document.getElementById('m_nombre').value = c.nombre ?? '';
            document.getElementById('m_apellidos').value = c.apellidos ?? '';
            document.getElementById('m_correo').value = c.correo ?? '';
            document.getElementById('m_telefono').value = c.telefono ?? '';
            document.getElementById('m_fecha_nacimiento').value = c.fecha_nacimiento ?? '';
            document.getElementById('m_fecha_vencimiento').value = c.fecha_vencimiento ?? '';
            document.getElementById('m_notas').value = c.notas ?? '';
            setSelect('m_genero', c.genero);
            setSelect('m_nivel', c.nivel);
            setSelect('m_plan_id', c.plan_id);
            document.getElementById('formFicha').action =
                '<?= base_url('clientes/actualizar') ?>/' + c.id;
            document.getElementById('formBaja').action =
                '<?= base_url('clientes/desactivar') ?>/' + c.id;
        });

        /* ── Modal pagar desde Clientes ─────────────────────────── */
        const modalPagarC = document.getElementById('modalPagarClientes');
        modalPagarC.addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            document.getElementById('mpc_nombre').value = btn.dataset.nombre;
            document.getElementById('mpc_plan').value = btn.dataset.plan;
            document.getElementById('mpc_monto').value = btn.dataset.monto;
            document.getElementById('formPagarClientes').action =
                '<?= base_url('pagos/registrar') ?>/' + btn.dataset.id;
        });

        function setSelect(id, value) {
            const el = document.getElementById(id);
            if (!el) return;
            for (const opt of el.options) {
                opt.selected = (String(opt.value) === String(value ?? ''));
            }
        }

        function escHtml(str) {
            return String(str ?? '')
                .replace(/&/g, '&amp;').replace(/</g, '&lt;')
                .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }
    });
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>