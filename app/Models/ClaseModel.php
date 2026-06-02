<?php

namespace App\Models;

use CodeIgniter\Model;

class ClaseModel extends Model
{
    protected $table         = 'clases';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'nombre',
        'descripcion',
        'trainer_id',
        'nivel',
        'capacidad_max',
        'hora_inicio',
        'hora_fin',
        'dias_semana',
        'salon',
        'activo',
    ];

    protected $validationRules = [
        'nombre'        => 'required|min_length[2]|max_length[100]',
        'hora_inicio'   => 'required',
        'hora_fin'      => 'required',
        'dias_semana'   => 'required',
        'capacidad_max' => 'required|integer|greater_than[0]',
        'nivel'         => 'required|in_list[principiante,intermedio,avanzado]',
    ];

    /**
     * Convierte un string de días (posiblemente con índices numéricos viejos)
     * a su equivalente de texto. Ej: "0,1,2" → "dom,lun,mar" | "lun,mar" → "lun,mar"
     */
    public static function sanitizarDias(string $dias): string
    {
        $map = [
            '0' => 'dom',
            '1' => 'lun',
            '2' => 'mar',
            '3' => 'mie',
            '4' => 'jue',
            '5' => 'vie',
            '6' => 'sab',
        ];
        $partes = array_filter(explode(',', $dias), fn($x) => trim($x) !== '');
        $clean  = array_map(fn($d) => $map[trim($d)] ?? trim($d), $partes);
        return implode(',', $clean);
    }

    public function conTrainer(): array
    {
        $rows = $this->db
            ->table('clases c')
            ->select('c.*, CONCAT(t.nombre, " ", t.apellidos) AS trainer_nombre,
                      (SELECT COUNT(*) FROM clientes_clases cc WHERE cc.clase_id = c.id AND cc.activo = 1) AS inscritos')
            ->join('trainers t', 't.id = c.trainer_id', 'left')
            ->where('c.activo', 1)
            ->orderBy("FIELD(c.dias_semana,'dom','lun','mar','mie','jue','vie','sab')", '', false)
            ->orderBy('c.hora_inicio', 'ASC')
            ->get()
            ->getResultArray();

        // Sanitizar días por si existen registros viejos con índices numéricos
        foreach ($rows as &$row) {
            $row['dias_semana'] = self::sanitizarDias($row['dias_semana']);
        }

        return $rows;
    }

    // Clases de hoy
    public function hoy(): array
    {
        $dias = [
            '0' => 'dom',
            '1' => 'lun',
            '2' => 'mar',
            '3' => 'mie',
            '4' => 'jue',
            '5' => 'vie',
            '6' => 'sab',
        ];
        $hoy = $dias[date('w')];

        return $this->db
            ->table('clases c')
            ->select('c.*, CONCAT(t.nombre, " ", t.apellidos) AS trainer_nombre')
            ->join('trainers t', 't.id = c.trainer_id', 'left')
            ->where('c.activo', 1)
            ->like('c.dias_semana', $hoy)
            ->orderBy("FIELD(c.dias_semana,'dom','lun','mar','mie','jue','vie','sab')", '', false)
            ->orderBy('c.hora_inicio', 'ASC')
            ->get()
            ->getResultArray();
    }
    // Clases de hoy
    public function proximaClaseHoy(): array
    {
        $dias = [
            '0' => 'dom',
            '1' => 'lun',
            '2' => 'mar',
            '3' => 'mie',
            '4' => 'jue',
            '5' => 'vie',
            '6' => 'sab',
        ];

        $hoy  = $dias[date('w')];
        $hora = date('H:i:s');

        $resultado =  $this->db
            ->table('clases c')
            ->select('c.*, CONCAT(t.nombre, " ", t.apellidos) AS trainer_nombre')
            ->join('trainers t', 't.id = c.trainer_id', 'left')
            ->where('c.activo', 1)
            ->where("FIND_IN_SET('{$hoy}', c.dias_semana) >", 0, false)
            ->where('c.hora_fin >', $hora)
            ->orderBy('c.hora_inicio', 'ASC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return $resultado ?? [];
    }
}
