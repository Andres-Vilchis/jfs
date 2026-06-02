<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var array|null $clase
 * @var array      $trainers
 * @var int        $inscritos   (solo en edición)
 */
$editando      = isset($clase);
$diasGuardados = $editando ? trim(explode(',', $clase['dias_semana'])[0]) : '';
$diasOpciones  = ['dom', 'lun', 'mar', 'mie', 'jue', 'vie', 'sab'];
$diasLabels    = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?= $editando ? 'Editar clase' : 'Nueva clase' ?></h5>
    <a href="<?= route_to('clases.index') ?>" class="btn btn-sm btn-outline-secondary">
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

<div class="card" style="max-width: 650px;">
    <div class="card-body">
        <?php $action = $editando
            ? route_to('clases.actualizar', $clase['id'])
            : route_to('clases.guardar'); ?>

        <?= form_open($action) ?>

        <div class="row g-3">

            <!-- Nombre -->
            <div class="col-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Nombre de la clase &nbsp;<span class="text-danger">*</span></span>
                    <?= form_input([
                        'name'        => 'nombre',
                        'class'       => 'form-control',
                        'style'         => 'font-size:.75rem',
                        'placeholder' => 'Ej. Spinning, Yoga, Crossfit...',
                        'value'       => set_value('nombre', $clase['nombre'] ?? ''),
                    ]) ?>
                </div>
            </div>

            <!-- Trainer -->
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Trainer &nbsp;<span class="text-danger">*</span></span>
                    <select name="trainer_id" class="form-select" style="font-size:.75rem">
                        <option value="">— Selecciona —</option>
                        <?php foreach ($trainers as $t): ?>
                            <option value="<?= $t['id'] ?>"
                                <?= (set_value('trainer_id', $clase['trainer_id'] ?? '') == $t['id']) ? 'selected' : '' ?>>
                                <?= esc($t['nombre'] . ' ' . $t['apellidos']) ?>
                                (<?= ucfirst($t['nivel']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Nivel -->
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Nivel &nbsp;<span class="text-danger">*</span></span>
                    <select name="nivel" class="form-select" style="font-size:.75rem">
                        <?php foreach (['principiante', 'intermedio', 'avanzado'] as $n): ?>
                            <option value="<?= $n ?>"
                                <?= (set_value('nivel', $clase['nivel'] ?? 'principiante') === $n) ? 'selected' : '' ?>>
                                <?= ucfirst($n) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Hora inicio -->
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Hr. inicio &nbsp;<span class="text-danger">*</span></span>
                    <?= form_input([
                        'name'  => 'hora_inicio',
                        'type'  => 'time',
                        'class' => 'form-control',
                        'style' => 'font-size:.75rem',
                        'value' => set_value('hora_inicio', $clase['hora_inicio'] ?? ''),
                    ]) ?>
                </div>
            </div>

            <!-- Hora fin -->
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Hr. fin &nbsp;<span class="text-danger">*</span></span>
                    <?= form_input([
                        'name'  => 'hora_fin',
                        'type'  => 'time',
                        'class' => 'form-control',
                        'style' => 'font-size:.75rem',
                        'value' => set_value('hora_fin', $clase['hora_fin'] ?? ''),
                    ]) ?>
                </div>
            </div>

            <!-- Capacidad máx -->
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Capacidad &nbsp;<span class="text-danger">*</span></span>
                    <?= form_input([
                        'name'  => 'capacidad_max',
                        'type'  => 'number',
                        'min'   => '1',
                        'class' => 'form-control',
                        'style' => 'font-size:.75rem',
                        'value' => set_value('capacidad_max', $clase['capacidad_max'] ?? 20),
                    ]) ?>
                </div>
            </div>

            <!-- Salón -->
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Salón</span>
                    <?= form_input([
                        'name'        => 'salon',
                        'class'       => 'form-control',
                        'style' => 'font-size:.75rem',
                        'placeholder' => 'Ej. Sala A, Sala Principal...',
                        'value'       => set_value('salon', $clase['salon'] ?? ''),
                    ]) ?>
                </div>
            </div>

            <!-- Día de la semana (un solo día) -->
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Día &nbsp;<span class="text-danger">*</span></span>
                    <select name="dias_semana[]" class="form-select" style="font-size:.75rem" required>
                        <option value="">— Selecciona un día —</option>
                        <?php foreach ($diasOpciones as $dia): ?>
                            <option value="<?= $dia ?>"
                                <?= (set_value('dias_semana.0', $diasGuardados) === $dia) ? 'selected' : '' ?>>
                                <?= ucfirst($dia) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Descripción -->
            <div class="col-12">
                <textarea name="descripcion" class="form-control text-start" rows="2"><?= esc($clase['descripcion'] ?? '') ?> </textarea>
            </div>

        </div><!-- /row -->

        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-save me-1"></i>
                    <?= $editando ? 'Actualizar' : 'Registrar' ?>
                </button>
                <a href="<?= route_to('clases.index') ?>" class="btn btn-sm btn-outline-secondary ms-2">
                    <i class="bi bi-x-diamond-fill me-1"></i>Cancelar
                </a>
            </div>
            <?= form_close() ?>
            <!-- ↑ El form principal cierra aquí, ANTES del botón Eliminar -->

            <?php if ($editando): ?>
                <?php
                $confirmar = $clase['activo']
                    ? '¿Seguro que deseas eliminar esta clase? Los participantes inscritos permanecerán en el registro.'
                    : '¿Deseas activar esta clase?';
                $colorBtn = $clase['activo'] ? 'btn-outline-danger' : 'btn-success';
                $icono    = $clase['activo'] ? 'bi-trash3-fill' : 'bi-play-circle';
                $etiqueta = $clase['activo'] ? 'Eliminar' : 'Activar clase';
                ?>
                <!-- Form independiente — NO anidado en el form de edición -->
                <form action="<?= route_to('clases.toggle', $clase['id']) ?>"
                    method="post"
                    class="text-end"
                    onsubmit="return confirm('<?= addslashes($confirmar) ?>')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm <?= $colorBtn ?>">
                        <i class="bi <?= $icono ?>"></i>
                        <!--<//?= $etiqueta ?> -->
                    </button>
                </form>
            <?php endif; ?>

        </div><!-- /card-body -->
    </div><!-- /card -->

    <?= $this->endSection() ?>