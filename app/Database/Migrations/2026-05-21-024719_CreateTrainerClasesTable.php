<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrainerClasesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'trainer_id'     => ['type' => 'INT'],
            'clase_id'       => ['type' => 'INT'],
            'asignado_desde' => ['type' => 'DATE'],
        ]);
        $this->forge->addPrimaryKey(['trainer_id', 'clase_id']);
        $this->forge->addKey('trainer_id');
        $this->forge->addKey('clase_id');
        $this->forge->createTable('trainer_clases');
    }

    public function down()
    {
        $this->forge->dropTable('trainer_clases');
    }
}
