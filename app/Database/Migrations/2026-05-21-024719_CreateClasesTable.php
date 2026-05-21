<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClasesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'auto_increment' => true],
            'nombre'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'descripcion'   => ['type' => 'TEXT', 'null' => true],
            'trainer_id'    => ['type' => 'INT', 'null' => true],
            'nivel'         => ['type' => 'ENUM', 'constraint' => ['principiante', 'intermedio', 'avanzado'], 'default' => 'principiante'],
            'capacidad_max' => ['type' => 'INT', 'default' => 20],
            'hora_inicio'   => ['type' => 'TIME'],
            'hora_fin'      => ['type' => 'TIME'],
            'dias_semana'   => ['type' => 'VARCHAR', 'constraint' => 50],
            'salon'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'activo'        => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('trainer_id');   // ← índice simple, sin FK
        $this->forge->createTable('clases');
    }

    public function down()
    {
        $this->forge->dropTable('clases');
    }
}
