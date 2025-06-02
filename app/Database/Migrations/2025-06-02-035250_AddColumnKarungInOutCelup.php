<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnKarungInOutCelup extends Migration
{
    public function up()
    {
        $this->forge->addColumn('out_celup', [
            'karung_kirim' => [
                'type' => 'int',
                'constraint' => 11,
                'after' => 'cones_kirim', // Ditempatkan setelah 'kg_po_tambahan'
            ],
        ]);
    }

    public function down()
    {
        // Hapus kolom yang ditambahkan di metode up()
        $this->forge->dropColumn('out_celup', 'karung_kirim');
    }
}
