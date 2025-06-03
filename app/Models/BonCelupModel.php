<?php

namespace App\Models;

use CodeIgniter\Model;

class BonCelupModel extends Model
{
    protected $table            = 'bon_celup';
    protected $primaryKey       = 'id_bon';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_bon',
        'tgl_datang',
        'no_surat_jalan',
        'detail_sj',
        'admin',
        'created_at',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getData()
    {
        return $this->select('bon_celup.id_bon, bon_celup.tgl_datang, bon_celup.no_surat_jalan, bon_celup.detail_sj')
            ->findAll();
    }

    public function saveBon($saveDataBon, $saveDataOutCelup)
    {
        $this->db->table('bon_celup')->insert($saveDataBon);

        $this->db->table('out_celup')->insert($saveDataOutCelup);
    }

    public function getDataById($id)
    {
        return $this->select('bon_celup.*')
            ->first();
        //     return $this->select('bon_celup.*, out_celup.*, scheule_celup.no_model, schedule_celup.item_type, schedule_celup.kode_warna, schedule_celup.warna')
        // ->join('out_celup', 'out_celup.id_bon=bon_celup.id_bon', 'left')
        // ->join('schedule_celup', 'out_celup.id_bon=bon_celup.id_bon')
        //     ->();
    }
    public function getDataPemasukan($idBon)
    {
        $builder = $this->select([
            'schedule_celup.id_celup',
            'bon_celup.id_bon',
            'out_celup.id_out_celup',
            'pemasukan.id_pemasukan',
            'schedule_celup.no_model',
            'schedule_celup.item_type',
            'schedule_celup.kode_warna',
            'schedule_celup.warna',
            'bon_celup.tgl_datang',
            'bon_celup.no_surat_jalan',
            'bon_celup.detail_sj',
            'bon_celup.admin',
            'out_celup.l_m_d',
            'out_celup.harga',
            'out_celup.gw_kirim',
            'out_celup.kgs_kirim',
            'out_celup.cones_kirim',
            'out_celup.karung_kirim',
            'out_celup.lot_kirim',
            'out_celup.ganti_retur',
            'pemasukan.nama_cluster',
            'pemasukan.out_jalur',
            'pemasukan.id_stock'
        ])
            ->join('out_celup', 'out_celup.id_bon = bon_celup.id_bon', 'left')
            ->join('schedule_celup', 'out_celup.id_celup = schedule_celup.id_celup', 'left')
            ->join('pemasukan', 'pemasukan.id_out_celup = out_celup.id_out_celup', 'left')
            ->where('bon_celup.id_bon', $idBon)
            ->groupBy('pemasukan.id_pemasukan');

        return $builder->get()->getResultArray(); // Mengembalikan data dalam bentuk array
    }
}
