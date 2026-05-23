<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var array $clientes
 * @var array $planes
 */
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Clientes</h5>
    <a href="<?= route_to('clientes.crear') ?>" class="btn btn-sm btn-primary">
        <i class="bi bi-person-plus-fill me-1"></i> Nuevo cliente
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible py-2">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger py-2">
        <ul class="mb-0 small">
            <?php foreach (session()->getFlashdata('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body px-0 py-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th class="text-center">Plan</th>
                        <th class="text-center">Vencimiento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Sin clientes registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $c): ?>
                            <?php
                            $hoy  = new DateTime();
                            $venc = new DateTime($c['fecha_vencimiento'] ?? 'now');
                            $diff = (int) $hoy->diff($venc)->format('%r%a');
                            if ($diff < 0) {
                                $badgeClass = 'text-danger-emphasis';
                                $badgeText = 'Vencido';
                            } elseif ($diff <= 7) {
                                $badgeClass = 'text-warning-emphasis';
                                $badgeText = "Vence en {$diff}d";
                            } else {
                                $badgeClass = 'text-success-emphasis';
                                $badgeText = date('d/m/Y', strtotime($c['fecha_vencimiento']));
                            }
                            ?>
                            <tr>
                                <td>
                                    <a href="#"
                                        class="fw-semibold text-decoration-none link-info abrir-ficha"
                                        data-bs-toggle="modal"
                                        data-bs-target="#fichaModal"
                                        data-cliente='<?= esc(json_encode($c), 'attr') ?>'>
                                        <?= esc($c['nombre'] . ' ' . $c['apellidos']) ?>
                                    </a>
                                    <?php if ($c['correo']): ?>
                                        <div class="text-muted small"><?= esc($c['correo']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center align-middle small text-muted">
                                    <?= esc($c['plan_nombre'] ?? '—') ?>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="small <?= $badgeClass ?>"><?= $badgeText ?></span>
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

                        <!-- Nombre -->
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" style="min-width:110px">Nombre</span>
                            <input type="text" name="nombre" id="m_nombre"
                                class="form-control" placeholder="Nombre" required>
                        </div>

                        <!-- Apellidos -->
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" style="min-width:110px">Apellidos</span>
                            <input type="text" name="apellidos" id="m_apellidos"
                                class="form-control" placeholder="Apellidos" required>
                        </div>

                        <!-- Correo -->
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" style="min-width:110px">
                                <i class="bi bi-envelope me-1"></i>Correo
                            </span>
                            <input type="email" name="correo" id="m_correo"
                                class="form-control" placeholder="correo@ejemplo.com">
                        </div>

                        <!-- Teléfono -->
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" style="min-width:110px">
                                <i class="bi bi-phone me-1"></i>Teléfono
                            </span>
                            <input type="text" name="telefono" id="m_telefono"
                                class="form-control" placeholder="10 dígitos">
                        </div>

                        <!-- Fecha nacimiento -->
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" style="min-width:110px">
                                <i class="bi bi-calendar3 me-1"></i>Nacimiento
                            </span>
                            <input type="date" name="fecha_nacimiento" id="m_fecha_nacimiento"
                                class="form-control">
                        </div>

                        <!-- Fecha nacimiento -->
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" style="min-width:110px">Género</span>
                            <select name="genero" id="m_genero" class="form-select">
                                <option value="">— —</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>

                        <!-- Nivel -->
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" style="min-width:110px">
                                <i class="bi bi-bar-chart me-1"></i>Nivel
                            </span>
                            <select name="nivel" id="m_nivel" class="form-select">
                                <option value="principiante">Principiante</option>
                                <option value="intermedio">Intermedio</option>
                                <option value="avanzado">Avanzado</option>
                            </select>
                        </div>

                        <!-- Plan -->
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" style="min-width:110px">
                                <i class="bi bi-credit-card me-1"></i>Plan
                            </span>
                            <select name="plan_id" id="m_plan_id" class="form-select">
                                <option value="">— Sin plan —</option>
                                <?php foreach ($planes as $p): ?>
                                    <option value="<?= $p['id'] ?>">
                                        <?= esc($p['nombre']) ?> — $<?= number_format($p['precio'], 2) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Vencimiento -->
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" style="min-width:110px">
                                <i class="bi bi-calendar-check me-1"></i>Vencimiento
                            </span>
                            <input type="date" name="fecha_vencimiento" id="m_fecha_vencimiento"
                                class="form-control">
                        </div>

                        <!-- Notas -->
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" style="min-width:110px">
                                <i class="bi bi-sticky me-1"></i>Notas
                            </span>
                            <textarea name="notas" id="m_notas"
                                class="form-control" rows="2"
                                placeholder="Observaciones..."></textarea>
                        </div>

                    </div><!-- /gap-2 -->
                </div><!-- /modal-body -->


                <div class="modal-footer py-2 justify-content-between">

                    <div class="container-fluid gap-2">

                        <div class="row">
                            <div class="d-grid col-sm-4 p-3 bg-primary text-white">
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                            </div>

                            <div class="d-grid col-sm-4 p-3 bg-dark text-white"><button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-save me-1"></i> Guardar
                                </button></div>

                            <div class="d-grid col-sm-4 p-3 bg-primary text-white">
                                <form id="formBaja" method="post" action="" class="m-0">
                                    <?= csrf_field() ?>
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('¿Dar de baja a este cliente? Esta acción lo desactivará del sistema.')">
                                        <i class="bi bi-person-x me-1"></i> Baja
                                    </button>
                                </form>
                            </div>
                        </div>



                    </div>
                </div>

            </form><!-- /formFicha -->

        </div>
    </div>
</div>


<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {

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

        function setSelect(id, value) {
            const el = document.getElementById(id);
            if (!el) return;
            for (const opt of el.options) {
                opt.selected = (String(opt.value) === String(value ?? ''));
            }
        }

        function escHtml(str) {
            return String(str ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>