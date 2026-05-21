<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClientesClasesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'auto_increment' => true],
            'cliente_id'       => ['type' => 'INT'],
            'clase_id'         => ['type' => 'INT'],
            'fecha_inscripcion' => ['type' => 'DATE'],
            'activo'           => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('cliente_id');
        $this->forge->addKey('clase_id');
        $this->forge->createTable('clientes_clases');
    }

    public function down()
    {
        $this->forge->dropTable('clientes_clases');
    }
}
