<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MasterOrderModel;
use App\Models\MasterMaterialModel;
use App\Models\MaterialModel;
use App\Models\ReturModel;
use App\Models\PemasukanModel;
use App\Models\OutCelupModel;
use App\Models\KategoriReturModel;
use App\Models\ScheduleCelupModel;
use App\Models\ClusterModel;
use App\Models\StockModel;

class ReturController extends BaseController
{
    protected $role;
    protected $active;
    protected $filters;
    protected $request;
    protected $masterOrderModel;
    protected $masterMaterial;
    protected $materialModel;
    protected $returModel;
    protected $pemasukanModel;
    protected $outCelupModel;
    protected $kategoriReturModel;
    protected $scheduleCelupModel;
    protected $clusterModel;
    protected $stockModel;

    public function __construct()
    {
        $this->materialModel = new MaterialModel();
        $this->masterMaterial = new MasterMaterialModel();
        $this->masterOrderModel = new MasterOrderModel();
        $this->returModel = new ReturModel();
        $this->pemasukanModel = new PemasukanModel();
        $this->outCelupModel = new OutCelupModel();
        $this->kategoriReturModel = new KategoriReturModel();
        $this->scheduleCelupModel = new ScheduleCelupModel();
        $this->clusterModel = new ClusterModel();
        $this->stockModel = new StockModel();

        $this->role = session()->get('role');
        if ($this->filters   = ['role' => ['gbn']] != session()->get('role')) {
            return redirect()->to(base_url('/login'));
        }
        $this->isLogedin();
    }

    protected function isLogedin()
    {
        if (!session()->get('id_user')) {
            return redirect()->to(base_url('/login'));
        }
    }


    public function index()
    {
        // Ambil data retur
        $dataRetur = $this->returModel->findAll();
        // dd ($dataRetur);
        $getJenisBb = $this->masterMaterial->getJenisBahanBaku();
        // $urlApi = 'http://172.23.39.114/CapacityApps/public/api/getDataArea';
        $urlApi = 'http://172.23.44.14/CapacityApps/public/api/getDataArea';
        $getArea = json_decode(file_get_contents($urlApi), true);
        // dd ($getArea);
        $jenis = $this->request->getGet('jenis');
        $area = $this->request->getGet('area');
        $tgl = $this->request->getGet('tgl_retur');
        $model = $this->request->getGet('no_model');
        $kodeWarna = $this->request->getGet('kode_warna');

        // Logika untuk menentukan apakah ada filter
        $isFiltered = $jenis || $area || $tgl || $model || $kodeWarna;
        $isFiltered = urlencode($isFiltered);
        // Ambil data hanya jika ada filter
        $retur = $isFiltered ? $this->returModel->getFilteredData($this->request->getGet()) : $dataRetur;
        $data = [
            'title' => 'Retur',
            'retur' => $retur,
            'jenis' => $getJenisBb,
            'area' => $getArea,
            'active' => $this->active,
            'role' => $this->role,
            'filters' => $this->filters,
            'isFiltered' => $isFiltered
        ];

        return view($data['role'] . '/retur/index', $data);
    }

    public function approve()
    {
        $id = $this->request->getPost('id_retur');
        $approve = $this->request->getPost('catatan');
        $text = 'Approve: ' . $approve;
        $data = [
            'keterangan_gbn' => $text,
            'waktu_acc_retur' => date('Y-m-d H:i:s'),
            'admin' => session()->get('username')
        ];
        $this->returModel->update($id, $data);
        // log_message('info', 'Data update retur: ' . json_encode($data));
        $dataRetur = $this->returModel->find($id);
        $idCelup = $this->scheduleCelupModel->getIdCelups($dataRetur);
        $barcodeNew = [
            'id_retur'       => $dataRetur['id_retur'],
            'id_celup'       => $idCelup,
            'no_model'       => $dataRetur['no_model'],
            'no_karung'     => (int)$dataRetur['krg_retur'] ?? 0,
            'kgs_kirim'          => (float)$dataRetur['kgs_retur'],
            'cones_kirim'      => (int)$dataRetur['cns_retur'],
            'lot_kirim'      => $dataRetur['lot_retur'],
            'admin'      => session()->get('username')
        ];

        // log_message('info', 'Data barcodeNew: ' . json_encode($barcodeNew));
        // dd ($barcodeNew);
        $this->outCelupModel->insert($barcodeNew);

        // dd ($dataPemasukan);
        // flashdata
        session()->setFlashdata('success', 'Data berhasil di update.');
        return redirect()->to(base_url(session()->get('role') . '/retur'));
    }

    public function reject()
    {
        $id = $this->request->getPost('id_retur');
        $reject = $this->request->getPost('catatan');
        $text = 'Reject: ' . $reject;
        $data = [
            'keterangan_gbn' => $text,
            'waktu_acc_retur' => date('Y-m-d H:i:s'),
            'admin' => session()->get('username')
        ];
        $this->returModel->update($id, $data);
        // flashdata
        session()->setFlashdata('success', 'Data berhasil di update.');
        return redirect()->to(base_url(session()->get('role') . '/retur'));
    }
    public function returArea()
    {
        $data = $this->kategoriReturModel->getKategoriRetur();
        $kategoriRetur = [];
        foreach ($data as $item) {
            $kategoriRetur[] = [
                'nama_kategori' => $item['nama_kategori'],
                'tipe_kategori' => $item['tipe_kategori']
            ];
        }
        $apiUrl  = 'http://172.23.44.14/CapacityApps/public/api/getDataArea';
        $response = file_get_contents($apiUrl);

        $area = json_decode($response, true);

        return view($this->role . '/retur/index', [
            'active'     => $this->active,
            'title'      => 'PPH',
            'role'       => $this->role,
            'area'       => $area,
            'kategori' => $kategoriRetur,
        ]);
    }

    public function listBarcodeRetur()
    {
        $listRetur = $this->returModel->listBarcodeRetur();
        $data = [
            'role' => $this->role,
            'active' => $this->active,
            'title' => "List Barcode Retur",
            'listRetur' => $listRetur,
        ];
        // dd($data);
        return view($this->role . '/retur/list-barcode-retur', $data);
    }

    public function detailBarcodeRetur($tglRetur)
    {
        $detailRetur = $this->returModel->detailBarcodeRetur($tglRetur);
        $data = [
            'role' => $this->role,
            'active' => $this->active,
            'title' => "Detail Barcode Retur",
            'detailRetur' => $detailRetur,
            'tglRetur' => $tglRetur
        ];
        return view($this->role . '/retur/detail-barcode-retur', $data);
    }

    public function reportReturArea()
    {
        $getKategori = $this->kategoriReturModel->getKategoriRetur();
        $data = [
            'role' => $this->role,
            'active' => $this->active,
            'title' => "Report Retur Area",
            'getKategori' => $getKategori
        ];
        return view($this->role . '/retur/report-retur-area', $data);
    }

    public function filterReturArea()
    {
        $area = $this->request->getGet('area');
        $kategori = $this->request->getGet('kategori');
        $tanggalAwal = $this->request->getGet('tanggal_awal');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir');

        $data = $this->returModel->getFilterReturArea($area, $kategori, $tanggalAwal, $tanggalAkhir);

        if (!empty($data)) {
            foreach ($data as $key => $dt) {
                $kirim = $this->outCelupModel->getDataKirim($dt['id_retur']);
                $data[$key]['kg_kirim'] = $kirim['kg_kirim'] ?? 0;
                $data[$key]['cns_kirim'] = $kirim['cns_kirim'] ?? 0;
                $data[$key]['krg_kirim'] = $kirim['krg_kirim'] ?? 0;
                $data[$key]['lot_out'] = $kirim['lot_out'] ?? '-';
            }
        }

        return $this->response->setJSON($data);
    }

    public function detailRetur($id)
    {
        function fetchApiData($url)
        {
            try {
                $response = file_get_contents($url);
                if ($response === false) {
                    throw new \Exception("Error fetching data from $url");
                }
                $data = json_decode($response, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception("Invalid JSON response from $url");
                }
                return $data;
            } catch (\Exception $e) {
                error_log($e->getMessage());
                return null;
            }
        }

        $detailRetur    = $this->returModel->getDetailRetur($id);
        $cluster        = $this->clusterModel->getDataCluster();

        $area       = $detailRetur['area_retur'];
        $no_model   = $detailRetur['no_model'];
        $item_type  = $detailRetur['item_type'];
        $kode_warna = $detailRetur['kode_warna'];
        $warna      = $detailRetur['warna'];

        // get komposisi, gw dan los
        $getMu = $this->materialModel->getStyleSizeByBb($no_model, $item_type, $kode_warna);
        // dd($getMu);

        $qtyPo = 0;
        foreach ($getMu as $mu) {
            $styleSize = $mu['style_size'];
            $qtyPcsUrl = 'http://172.23.39.118/CapacityApps/public/api/getQtyPcsByAreaByStyle/' . $area . '?no_model='
                . $no_model . '&style_size=' . urlencode($styleSize);
            $qtyPcs = fetchApiData($qtyPcsUrl);

            $kgKebutuhan = $qtyPcs * $mu['gw'] * ($mu['composition'] / 100) * (1 + ($mu['loss'] / 100)) / 1000;
            // dd($kgKebutuhan);
            $qtyPo += $kgKebutuhan;
        }
        dd($qtyPo);


        if (!$detailRetur) {
            return redirect()->to(base_url(session()->get('role') . '/retur'));
        }

        $data = [
            'role' => $this->role,
            'active' => $this->active,
            'title' => "Detail Retur",
            'detailRetur' => $detailRetur,
            'cluster' => $cluster
        ];
        return view($this->role . '/retur/detail-retur', $data);
    }

    public function saveRetur()
    {
        $getData = $this->request->getPost();
        $data = [];
        $createdAt = date('Y-m-d H:i:s');
        $user     = session()->get('username');

        $this->returModel->update($getData['id_retur'], [
            'keterangan_gbn' => 'Approve: ' . $getData['keterangan_gbn'],
            'waktu_acc_retur' => $createdAt,
            'admin' => $user,
            'updated_at' => $createdAt,
        ]);

        // 1) Siapkan data untuk out_celup
        if (isset($getData['nama_cluster']) && count($getData['nama_cluster']) >= 2) {
            $total = count($getData['nama_cluster']);
            for ($i = 0; $i < $total; $i++) {
                $data[] = [
                    'id_retur'     => $getData['id_retur'],
                    'no_model'     => $getData['no_model'],
                    'kgs_kirim'    => $getData['kgs'][$i],
                    'cones_kirim'  => $getData['cones'][$i],
                    'karung_kirim' => $getData['krg'][$i],
                    'lot_kirim'    => $getData['lot'],
                    'admin'        => $user,
                    'l_m_d'        => '',
                    'created_at'   => $createdAt,
                    'updated_at'   => $createdAt,
                ];
            }
        } else {
            $data[] = [
                'id_retur'     => $getData['id_retur'],
                'no_model'     => $getData['no_model'],
                'kgs_kirim'    => is_array($getData['kgs']) ? $getData['kgs'][0] : $getData['kgs'],
                'cones_kirim'  => is_array($getData['cones']) ? $getData['cones'][0] : $getData['cones'],
                'karung_kirim' => is_array($getData['krg']) ? $getData['krg'][0] : $getData['krg'],
                'lot_kirim'    => is_array($getData['lot']) ? $getData['lot'][0] : $getData['lot'],
                'admin'        => $user,
                'l_m_d'        => '',
                'created_at'   => $createdAt,
                'updated_at'   => $createdAt,
            ];
        }

        // 2) Simpan ke out_celup
        $insert = count($data) > 1
            ? $this->outCelupModel->insertBatch($data)
            : $this->outCelupModel->insert($data[0]);

        if (! $insert) {
            session()->setFlashdata('error', 'Gagal menyimpan data retur.');
            return redirect()->to(base_url($this->role . '/retur'));
        }

        // 3) Ambil kembali baris yang baru
        $inserted = $this->outCelupModel
            ->where('id_retur', $getData['id_retur'])
            ->where('no_model', $getData['no_model'])
            ->where('created_at', $createdAt)
            ->findAll();

        $tglMasuk       = date('Y-m-d');

        foreach ($inserted as $idx => $row) {
            // 4) Simpan/Update ke stock
            $cluster = $getData['nama_cluster'][$idx] ?? $getData['nama_cluster'][0];
            $stock   = $this->stockModel
                ->where('nama_cluster', $cluster)
                ->where('no_model', $row['no_model'])
                ->where('item_type', $getData['item_type'])
                ->where('kode_warna', $getData['kode_warna'])
                ->where('warna', $getData['warna'])
                ->first();

            $inKgs   = $row['kgs_kirim'];
            $inCns   = $row['cones_kirim'];
            $inKrg   = $row['karung_kirim'];
            $lotNew  = $row['lot_kirim'];

            if ($stock) {
                // Update stok
                $this->stockModel->update($stock['id_stock'], [
                    'kgs_stock_awal' => $stock['kgs_stock_awal'] + $inKgs,
                    'cns_stock_awal' => $stock['cns_stock_awal'] + $inCns,
                    'krg_stock_awal' => $stock['krg_stock_awal'] + $inKrg,
                    'lot_awal'       => $lotNew,
                    'updated_at'     => $createdAt,
                ]);
                $idStock = $stock['id_stock'];
            } else {
                // Insert baru
                $idStock = $this->stockModel->insert([
                    'no_model'        => $row['no_model'],
                    'item_type'       => $getData['item_type'],
                    'kode_warna'      => $getData['kode_warna'],
                    'warna'           => $getData['warna'],
                    'kgs_stock_awal'  => $inKgs,
                    'cns_stock_awal'  => $inCns,
                    'krg_stock_awal'  => $inKrg,
                    'lot_awal'        => $lotNew,
                    'kgs_in_out'      => 0,
                    'cns_in_out'      => 0,
                    'krg_in_out'      => 0,
                    'lot_stock'       => $lotNew,
                    'nama_cluster'    => $cluster,
                    'admin'           => $user,
                    'created_at'      => $createdAt,
                    'updated_at'      => $createdAt,
                ]);
            }
            // 5) Simpan ke pemasukan
            $this->pemasukanModel->insert([
                'id_out_celup' => $row['id_out_celup'],
                'tgl_masuk'    => $tglMasuk,
                'nama_cluster' => $getData['nama_cluster'][$idx] ?? $getData['nama_cluster'][0],
                'out_jalur'    => '0',
                'admin'        => $user,
                'id_stock'     => $idStock,
                'created_at'   => $createdAt,
                'updated_at'   => $createdAt,
            ]);
        }

        session()->setFlashdata('success', 'Data retur berhasil disimpan.');
        return redirect()->to(base_url($this->role . '/retur'));
    }
}
