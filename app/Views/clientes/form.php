<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var array       $planes
 * @var array|null  $cliente
 */
$editando = isset($cliente);
$fechaInscripcion = set_value('fecha_inscripcion', $cliente['fecha_vencimiento'] ?? '');
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?= $editando ? 'Editar cliente' : 'Nuevo cliente' ?></h5>
    <a href="<?= route_to('clientes.index') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

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
    <div class="card-body">
        <?php
        $action = $editando
            ? route_to('clientes.actualizar', $cliente['id'])
            : route_to('clientes.guardar');
        ?>
        <?= form_open($action) ?>

        <div class="row g-2">

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Nombre &nbsp;<span class="text-danger">*</span></span>
                    <input type="text" name="nombre" class="form-control" style="font-size:.75rem"
                        value="<?= set_value('nombre', $cliente['nombre'] ?? '') ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Apellidos &nbsp;<span class="text-danger">*</span></span>
                    <input type="text" name="apellidos" class="form-control" style="font-size:.75rem"
                        value="<?= set_value('apellidos', $cliente['apellidos'] ?? '') ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Correo</span>
                    <input type="email" name="correo" class="form-control" style="font-size:.75rem"
                        value="<?= set_value('correo', $cliente['correo'] ?? '') ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Teléfono</span>
                    <input type="text" name="telefono" class="form-control" style="font-size:.75rem"
                        value="<?= set_value('telefono', $cliente['telefono'] ?? '') ?>">
                </div>
            </div>

            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Nacimiento</span>
                    <input type="date" name="fecha_nacimiento" class="form-control" style="font-size:.75rem"
                        value="<?= set_value('fecha_nacimiento', $cliente['fecha_nacimiento'] ?? '') ?>">
                </div>
            </div>

            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Género</span>
                    <select name="genero" class="form-select form-select-sm" style="font-size:.75rem">
                        <option value="">— Selecciona —</option>
                        <?php foreach (['masculino', 'femenino', 'otro'] as $g): ?>
                            <option value="<?= $g ?>" <?= (($cliente['genero'] ?? '') === $g) ? 'selected' : '' ?>>
                                <?= ucfirst($g) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Nivel &nbsp;<span class="text-danger">*</span></span>
                    <select name="nivel" class="form-select form-select-sm" style="font-size:.75rem">
                        <?php foreach (['principiante', 'intermedio', 'avanzado'] as $n): ?>
                            <option value="<?= $n ?>" <?= (($cliente['nivel'] ?? 'principiante') === $n) ? 'selected' : '' ?>>
                                <?= ucfirst($n) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Plan &nbsp;<span class="text-danger">*</span></span>
                    <select name="plan_id" id="plan_id" class="form-select form-select-sm" style="font-size:.75rem">
                        <option value="">— Sin plan —</option>
                        <?php foreach ($planes as $p): ?>
                            <option value="<?= $p['id'] ?>"
                                data-dias="<?= $p['duracion_dias'] ?>"
                                <?= (($cliente['plan_id'] ?? '') == $p['id']) ? 'selected' : '' ?>>
                                <?= esc($p['nombre']) ?> — $<?= number_format($p['precio'], 2) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Inscripción</span>
                    <input type="date" name="fecha_inscripcion" id="fecha_inscripcion" class="form-control" style="font-size:.75rem"
                        value="<?= $fechaInscripcion ?>">
                </div>
            </div>

            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Vence</span>
                    <input type="text" id="fecha_vencimiento_preview" class="form-control text-success fw-semibold" style="font-size:.75rem" readonly value="">
                </div>
            </div>

            <div class="col-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Notas</span>
                    <textarea name="notas" class="form-control" rows="3" style="font-size:.75rem"><?= esc($cliente['notas'] ?? '') ?></textarea>
                </div>
            </div>

        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-save me-1"></i>
                <?= $editando ? 'Actualizar' : 'Registrar' ?>
            </button>

            <a href="<?= route_to('clientes.index') ?>" class="btn btn-sm btn-outline-secondary ms-2">
                <i class="bi bi-x-diamond-fill me-1"></i>Cancelar
            </a>
        </div>

        <?= form_close() ?>

    </div>
</div>

<?= $this->section('scripts') ?>
<script>
    (function() {
        const planSelect = document.getElementById('plan_id');
        const fechaInput = document.getElementById('fecha_inscripcion');
        const preview = document.getElementById('fecha_vencimiento_preview');

        function calcularVencimiento() {
            const opt = planSelect.options[planSelect.selectedIndex];
            const dias = parseInt(opt?.dataset?.dias || '0', 10);
            const fecha = fechaInput.value;

            if (!dias || !fecha) {
                preview.value = '—';
                return;
            }

            const d = new Date(fecha + 'T00:00:00');
            d.setDate(d.getDate() + dias);

            const yyyy = d.getFullYear();
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            preview.value = `${dd}/${mm}/${yyyy}`;
        }

        planSelect.addEventListener('change', calcularVencimiento);
        fechaInput.addEventListener('change', calcularVencimiento);

        // Calcular al cargar si hay valores previos
        calcularVencimiento();
    })();
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>