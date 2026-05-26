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
$errors        = session()->getFlashdata('errors') ?? [];
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?= $editando ? 'Editar clase' : 'Nueva clase' ?></h5>
    <a href="<?= route_to('clases.index') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

<?php if (! empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card" style="max-width: 650px;">
    <div class="card-body">
        <?php $action = $editando
            ? route_to('clases.actualizar', $clase['id'])
            : route_to('clases.guardar'); ?>

        <?= form_open($action) ?>

        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Nombre de la clase *</label>
                <?= form_input([
                    'name'  => 'nombre',
                    'class' => 'form-control' . (isset($errors['nombre']) ? ' is-invalid' : ''),
                    'placeholder' => 'Ej. Spinning, Yoga, Crossfit...',
                    'value' => set_value('nombre', $clase['nombre'] ?? ''),
                ]) ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">Trainer <span class="text-danger">*</span></label>
                <select name="trainer_id"
                    class="form-select <?= isset($errors['trainer_id']) ? 'is-invalid' : '' ?>">
                    <option value="">— Selecciona un trainer —</option>
                    <?php foreach ($trainers as $t): ?>
                        <option value="<?= $t['id'] ?>"
                            <?= ((set_value('trainer_id', $clase['trainer_id'] ?? '')) == $t['id']) ? 'selected' : '' ?>>
                            <?= esc($t['nombre'] . ' ' . $t['apellidos']) ?>
                            (<?= ucfirst($t['nivel']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['trainer_id'])): ?>
                    <div class="invalid-feedback d-block">
                        <?= esc($errors['trainer_id']) ?>
                    </div>
                <?php endif; ?>
                <div class="form-text">El nivel de la clase no puede superar el nivel del trainer.</div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Nivel *</label>
                <select name="nivel"
                    class="form-select <?= isset($errors['nivel']) ? 'is-invalid' : '' ?>">
                    <?php foreach (['principiante', 'intermedio', 'avanzado'] as $n): ?>
                        <option value="<?= $n ?>"
                            <?= ((set_value('nivel', $clase['nivel'] ?? 'principiante')) === $n) ? 'selected' : '' ?>>
                            <?= ucfirst($n) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['nivel'])): ?>
                    <div class="invalid-feedback d-block">
                        <?= esc($errors['nivel']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-4">
                <label class="form-label">Hora inicio *</label>
                <?= form_input([
                    'name'  => 'hora_inicio',
                    'type'  => 'time',
                    'class' => 'form-control',
                    'value' => set_value('hora_inicio', $clase['hora_inicio'] ?? ''),
                ]) ?>
            </div>
            <div class="col-md-4">
                <label class="form-label">Hora fin *</label>
                <?= form_input([
                    'name'  => 'hora_fin',
                    'type'  => 'time',
                    'class' => 'form-control',
                    'value' => set_value('hora_fin', $clase['hora_fin'] ?? ''),
                ]) ?>
            </div>
            <div class="col-md-4">
                <label class="form-label">Capacidad máx. *</label>
                <?= form_input([
                    'name'  => 'capacidad_max',
                    'type'  => 'number',
                    'min'   => '1',
                    'class' => 'form-control',
                    'value' => set_value('capacidad_max', $clase['capacidad_max'] ?? 20),
                ]) ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Salón</label>
                <?= form_input([
                    'name'        => 'salon',
                    'class'       => 'form-control',
                    'placeholder' => 'Ej. Sala A, Sala Principal...',
                    'value'       => set_value('salon', $clase['salon'] ?? ''),
                ]) ?>
            </div>
            <div class="col-md-6">
                <label class="form-label d-block">Días de la semana *</label>
                <div class="d-flex flex-wrap gap-2">
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
            <div class="col-12">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="2"
                    placeholder="Descripción breve de la clase..."><?= esc($clase['descripcion'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> <?= $editando ? 'Actualizar' : 'Registrar' ?>
                </button>
                <a href="<?= route_to('clases.index') ?>" class="btn btn-outline-secondary ms-2">Cancelar</a>
            </div>

            <?php if ($editando): ?>
                <?php
                $inscritos = $inscritos ?? 0;
                $accion    = $clase['activo'] ? 'suspender' : 'activar';
                $icono     = $clase['activo'] ? 'bi-trash3-fill' : 'bi-play-circle';
                $colorBtn  = $clase['activo'] ? 'btn-danger' : 'btn-success';
                $confirmar = $clase['activo']
                    ? "¿Seguro que deseas SUSPENDER esta clase? Los participantes inscritos permanecerán en el registro."
                    : "¿Deseas ACTIVAR esta clase?";
                ?>
                <form action="<?= route_to('clases.toggle', $clase['id']) ?>" method="post"
                      onsubmit="return confirm('<?= addslashes($confirmar) ?>')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn <?= $colorBtn ?>">
                        <i class="bi <?= $icono ?> me-1"></i>
                        <?= ucfirst($accion) ?> clase
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <?= form_close() ?>
    </div>
</div>

<?= $this->endSection() ?>