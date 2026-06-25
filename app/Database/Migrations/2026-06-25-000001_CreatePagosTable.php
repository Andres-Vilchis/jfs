<?php
// app/Database/Migrations/2026-06-25-000001_CreatePagosTable.php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePagosTable extends Migration
{
    public function up()
    {
        // Agregar campo ultimo_pago a clientes (si no existe)
        $fields = [
            'ultimo_pago' => [
                'type'    => 'DATE',
                'null'    => true,
                'after'   => 'fecha_vencimiento',
            ],
        ];
        $this->forge->addColumn('clientes', $fields);

        // Tabla historial de pagos
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'auto_increment' => true],
            'cliente_id'      => ['type' => 'INT'],
            'plan_id'         => ['type' => 'INT', 'null' => true],
            'monto'           => ['type' => 'DECIMAL', 'constraint' => '8,2'],
            'fecha_pago'      => ['type' => 'DATE'],
            'fecha_vencimiento_generada' => ['type' => 'DATE', 'null' => true],
            'notas'           => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'registrado_por'  => ['type' => 'INT', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('cliente_id');
        $this->forge->createTable('pagos');
    }

    public function down()
    {
        $this->forge->dropTable('pagos');
        $this->forge->dropColumn('clientes', 'ultimo_pago');
    }
}
