<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdStockInPengeluaran extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pengeluaran', [
            'id_stock' => [
                'type' => 'int',
                'constraint' => 11,
                'after' => 'id_total_pemesanan', // Ditempatkan setelah 'kg_po_tambahan'
            ],
        ]);
    }

    public function down()
    {
        // Hapus kolom yang ditambahkan di metode up()
        $this->forge->dropColumn('pengeluaran', 'id_stock');
    }
}
