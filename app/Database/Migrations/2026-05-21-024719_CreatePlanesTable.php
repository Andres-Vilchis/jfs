<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePlanesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'auto_increment' => true],
            'nombre'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'descripcion'  => ['type' => 'TEXT', 'null' => true],
            'precio'       => ['type' => 'DECIMAL', 'constraint' => '8,2'],
            'duracion_dias'=> ['type' => 'INT'],
            'beneficios'   => ['type' => 'TEXT', 'null' => true],
            'activo'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('planes');
    }

    public function down()
    {
        $this->forge->dropTable('planes');
    }
}