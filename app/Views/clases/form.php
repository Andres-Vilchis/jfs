<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/**
 * @var array|null $clase
 * @var array      $trainers
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

<div class="card" style="max-width:650px">
    <div class="card-body">
        <?php $action = $editando
            ? route_to('clases.actualizar', $clase['id'])
            : route_to('clases.guardar'); ?>

        <?= form_open($action) ?>

        <div class="row g-2">

            <div class="col-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Nombre *</span>
                    <input type="text" name="nombre" class="form-control"
                        placeholder="Ej. Spinning, Yoga, Crossfit..."
                        value="<?= set_value('nombre', $clase['nombre'] ?? '') ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Trainer</span>
                    <select name="trainer_id" class="form-select form-select-sm">
                        <option value="">— Sin trainer —</option>
                        <?php foreach ($trainers as $t): ?>
                            <option value="<?= $t['id'] ?>"
                                <?= (($clase['trainer_id'] ?? '') == $t['id']) ? 'selected' : '' ?>>
                                <?= esc($t['nombre'] . ' ' . $t['apellidos']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Nivel *</span>
                    <select name="nivel" class="form-select form-select-sm">
                        <?php foreach (['principiante', 'intermedio', 'avanzado'] as $n): ?>
                            <option value="<?= $n ?>"
                                <?= (($clase['nivel'] ?? 'principiante') === $n) ? 'selected' : '' ?>>
                                <?= ucfirst($n) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Hora inicio *</span>
                    <input type="time" name="hora_inicio" class="form-control"
                        value="<?= set_value('hora_inicio', $clase['hora_inicio'] ?? '') ?>" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Hora fin *</span>
                    <input type="time" name="hora_fin" class="form-control"
                        value="<?= set_value('hora_fin', $clase['hora_fin'] ?? '') ?>" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Capacidad máx. *</span>
                    <input type="number" name="capacidad_max" min="1" class="form-control"
                        value="<?= set_value('capacidad_max', $clase['capacidad_max'] ?? 20) ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Salón</span>
                    <input type="text" name="salon" class="form-control"
                        placeholder="Ej. Sala A, Sala Principal..."
                        value="<?= set_value('salon', $clase['salon'] ?? '') ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group input-group-sm align-items-center">
                    <span class="input-group-text" style="min-width:130px">Días *</span>
                    <div class="form-control d-flex flex-wrap gap-2 py-1" style="height:auto">
                        <?php foreach ($diasOpciones as $i => $dia): ?>
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="checkbox"
                                    name="dias_semana[]"
                                    id="dia_<?= $dia ?>"
                                    value="<?= $dia ?>"
                                    <?= in_array($dia, $diasGuardados) ? 'checked' : '' ?>>
                                <label class="form-check-label small" for="dia_<?= $dia ?>">
                                    <?= $diasLabels[$i] ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="min-width:130px">Descripción</span>
                    <textarea name="descripcion" class="form-control" rows="2"
                        placeholder="Descripción breve de la clase..."><?= esc($clase['descripcion'] ?? '') ?></textarea>
                </div>
            </div>

        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-save me-1"></i> <?= $editando ? 'Actualizar' : 'Registrar' ?>
            </button>
            <a href="<?= route_to('clases.index') ?>" class="btn btn-sm btn-outline-secondary ms-2">Cancelar</a>
        </div>

        <?= form_close() ?>
    </div>
</div>

<?= $this->endSection() ?>