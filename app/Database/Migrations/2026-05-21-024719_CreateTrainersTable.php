<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrainersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'nombre'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'apellidos'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'correo'      => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'telefono'    => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'nivel'       => ['type' => 'ENUM', 'constraint' => ['principiante', 'intermedio', 'avanzado'], 'default' => 'intermedio'],
            'especialidad'=> ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'foto'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'activo'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('trainers');
    }

    public function down()
    {
        $this->forge->dropTable('trainers');
    }
}