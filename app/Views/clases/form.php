<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var array|null $clase
 * @var array      $trainers
 * @var int        $inscritos   (solo en edición)
 */
$editando      = isset($clase);
$diasGuardados = $editando ? explode(',', $clase['dias_semana']) : [];
$diasOpciones  = ['lun', 'mar', 'mie', 'jue', 'vie', 'sab', 'dom'];
$diasLabels    = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
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
            <div class="col-12" style="font-size:.75rem">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Nombre de la clase *</span>
                    <?= form_input([
                        'name'        => 'nombre',
                        'class'       => 'form-control',
                        'placeholder' => 'Ej. Spinning, Yoga, Crossfit...',
                        'value'       => set_value('nombre', $clase['nombre'] ?? ''),
                    ]) ?>
                </div>
            </div>

            <!-- Trainer -->
            <div class="col-md-6" style="font-size:.75rem">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Trainer <span class="text-danger">*</span></span>
                    <select name="trainer_id" class="form-select">
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
            <div class="col-md-6" style="font-size:.75rem">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Nivel <span class="text-danger">*</span></span>
                    <select name="nivel" class="form-select">
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
            <div class="col-md-4" style="font-size:.75rem">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-clock"></i> Hr. inicio *</span>
                    <?= form_input([
                        'name'  => 'hora_inicio',
                        'type'  => 'time',
                        'class' => 'form-control',
                        'value' => set_value('hora_inicio', $clase['hora_inicio'] ?? ''),
                    ]) ?>
                </div>
            </div>

            <!-- Hora fin -->
            <div class="col-md-4" style="font-size:.75rem">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-clock-history"></i> Hr. fin <span class="text-danger">*</span></span>
                    <?= form_input([
                        'name'  => 'hora_fin',
                        'type'  => 'time',
                        'class' => 'form-control',
                        'value' => set_value('hora_fin', $clase['hora_fin'] ?? ''),
                    ]) ?>
                </div>
            </div>

            <!-- Capacidad máx -->
            <div class="col-md-4" style="font-size:.75rem">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-people"></i> Capacidad <span class="text-danger">*</span></span>
                    <?= form_input([
                        'name'  => 'capacidad_max',
                        'type'  => 'number',
                        'min'   => '1',
                        'class' => 'form-control',
                        'value' => set_value('capacidad_max', $clase['capacidad_max'] ?? 20),
                    ]) ?>
                </div>
            </div>

            <!-- Salón -->
            <div class="col-md-6" style="font-size:.75rem">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-door-open"></i> Salón</span>
                    <?= form_input([
                        'name'        => 'salon',
                        'class'       => 'form-control',
                        'placeholder' => 'Ej. Sala A, Sala Principal...',
                        'value'       => set_value('salon', $clase['salon'] ?? ''),
                    ]) ?>
                </div>
            </div>

            <!-- Días de la semana -->
            <div class="col-md-6" style="font-size:.75rem">
                <label class="form-label d-block">Días de la semana <span class="text-danger">*</span></label>
                <div class="d-flex flex-wrap gap-2 pt-1">
                    <?php foreach ($diasOpciones as $i => $dia): ?>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox"
                                name="dias_semana[]"
                                id="dia_<?= $dia ?>"
                                value="<?= $dia ?>"
                                <?= in_array($dia, $diasGuardados) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="dia_<?= $dia ?>">
                                <?= $diasLabels[$i] ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Descripción -->
            <div class="col-12" style="font-size:.75rem">
                <textarea name="descripcion" class="form-control" rows="2"
                    placeholder="Descripción breve de la clase..."><?= esc($clase['descripcion'] ?? '') ?></textarea>
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
 