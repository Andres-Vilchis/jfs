<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClientesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'auto_increment' => true],
            'nombre'           => ['type' => 'VARCHAR', 'constraint' => 100],
            'apellidos'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'correo'           => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'telefono'         => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'fecha_nacimiento' => ['type' => 'DATE', 'null' => true],
            'genero'           => ['type' => 'ENUM', 'constraint' => ['masculino', 'femenino', 'otro'], 'null' => true],
            'fecha_registro'   => ['type' => 'DATE'],
            'plan_id'          => ['type' => 'INT', 'null' => true],
            'fecha_vencimiento' => ['type' => 'DATE', 'null' => true],
            'nivel'            => ['type' => 'ENUM', 'constraint' => ['principiante', 'intermedio', 'avanzado'], 'default' => 'principiante'],
            'foto'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'notas'            => ['type' => 'TEXT', 'null' => true],
            'activo'           => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('plan_id');    // ← índice simple, sin FK
        $this->forge->createTable('clientes');
    }

    public function down()
    {
        $this->forge->dropTable('clientes');
    }
}
