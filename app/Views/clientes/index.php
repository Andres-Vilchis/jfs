<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var list<array{id: int, nombre: string, apellidos: string, correo: string|null, telefono: string|null, plan_nombre: string|null, nivel: string, fecha_vencimiento: string|null}> $clientes
 * @var string $fecha_formateada
 */
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Clientes</h5>
    <a href="<?= route_to('clientes.crear') ?>" class="btn btn-sm btn-primary">
        <i class="bi bi-person-plus-fill me-1"></i> Nuevo cliente
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
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


<!-- ── Modal Ficha del Cliente ────────────────────────────────────── -->
<div class="modal fade" id="fichaModal" tabindex="-1" aria-labelledby="fichaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title fw-semibold" id="fichaModalLabel">
                    <i class="bi bi-person-vcard me-2"></i>Ficha del cliente
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <!-- Formulario de edición -->
            <form id="formFicha" method="post" action="">
                <?= csrf_field() ?>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Nombre *</label>
                            <input type="text" name="nombre" id="m_nombre" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Apellidos *</label>
                            <input type="text" name="apellidos" id="m_apellidos" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Correo</label>
                            <input type="email" name="correo" id="m_correo" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Teléfono</label>
                            <input type="text" name="telefono" id="m_telefono" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-sm">Fecha de nacimiento</label>
                            <input type="date" name="fecha_nacimiento" id="m_fecha_nacimiento" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-sm">Género</label>
                            <select name="genero" id="m_genero" class="form-select form-select-sm">
                                <option value="">— Selecciona —</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-sm">Nivel *</label>
                            <select name="nivel" id="m_nivel" class="form-select form-select-sm">
                                <option value="principiante">Principiante</option>
                                <option value="intermedio">Intermedio</option>
                                <option value="avanzado">Avanzado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Plan</label>
                            <select name="plan_id" id="m_plan_id" class="form-select form-select-sm">
                                <option value="">— Sin plan —</option>
                                <?php foreach ($planes as $p): ?>
                                    <option value="<?= $p['id'] ?>">
                                        <?= esc($p['nombre']) ?> — $<?= number_format($p['precio'], 2) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Fecha de vencimiento</label>
                            <input type="date" name="fecha_vencimiento" id="m_fecha_vencimiento" class="form-control form-control-sm">
                        </div>
                        <div class="col-12">
                            <label class="form-label form-label-sm">Notas</label>
                            <textarea name="notas" id="m_notas" class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <!-- Baja del cliente -->
                    <form id="formBaja" method="post" action="" class="m-0">
                        <?= csrf_field() ?>
                        <button type="submit"
                            class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('¿Dar de baja a este cliente?')">
                            <i class="bi bi-person-x me-1"></i> Dar de baja
                        </button>
                    </form>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-save me-1"></i> Guardar cambios
                        </button>
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
            const cliente = JSON.parse(trigger.getAttribute('data-cliente'));

            // Título
            document.getElementById('fichaModalLabel').innerHTML =
                '<i class="bi bi-person-vcard me-2"></i>' +
                cliente.nombre + ' ' + cliente.apellidos;

            // Campos editables
            document.getElementById('m_nombre').value = cliente.nombre ?? '';
            document.getElementById('m_apellidos').value = cliente.apellidos ?? '';
            document.getElementById('m_correo').value = cliente.correo ?? '';
            document.getElementById('m_telefono').value = cliente.telefono ?? '';
            document.getElementById('m_fecha_nacimiento').value = cliente.fecha_nacimiento ?? '';
            document.getElementById('m_fecha_vencimiento').value = cliente.fecha_vencimiento ?? '';
            document.getElementById('m_notas').value = cliente.notas ?? '';

            // Selects
            setSelect('m_genero', cliente.genero);
            setSelect('m_nivel', cliente.nivel);
            setSelect('m_plan_id', cliente.plan_id);

            // Acciones de formularios
            document.getElementById('formFicha').action =
                '<?= base_url('clientes/actualizar') ?>/' + cliente.id;

            document.getElementById('formBaja').action =
                '<?= base_url('clientes/desactivar') ?>/' + cliente.id;
        });

        function setSelect(id, value) {
            const el = document.getElementById(id);
            if (!el || value === null || value === undefined) return;
            for (let opt of el.options) {
                opt.selected = (opt.value == value);
            }
        }
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>