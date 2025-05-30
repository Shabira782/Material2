<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\MasterOrderModel;
use App\Models\MaterialModel;
use App\Models\MasterMaterialModel;
use App\Models\OpenPoModel;
use App\Models\BonCelupModel;
use App\Models\OutCelupModel;
use App\Models\OtherBonModel;
use FPDF;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\Models\PemesananSpandexKaretModel;

class PdfController extends BaseController
{
    protected $role;
    protected $active;
    protected $filters;
    protected $request;
    protected $masterOrderModel;
    protected $materialModel;
    protected $masterMaterialModel;
    protected $openPoModel;
    protected $bonCelupModel;
    protected $outCelupModel;
    protected $otherBonModel;
    protected $pemesananSpandexKaretModel;

    public function __construct()
    {
        $this->masterOrderModel = new MasterOrderModel();
        $this->materialModel = new MaterialModel();
        $this->masterMaterialModel = new MasterMaterialModel();
        $this->openPoModel = new OpenPoModel();
        $this->bonCelupModel = new BonCelupModel();
        $this->outCelupModel = new OutCelupModel();
        $this->otherBonModel = new OtherBonModel();
        $this->pemesananSpandexKaretModel = new PemesananSpandexKaretModel();


        $this->role = session()->get('role');
        $this->active = '/index.php/' . session()->get('role');
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
    public function generateOpenPO($no_model)
    {
        $tujuan = $this->request->getGet('tujuan');
        $jenis = $this->request->getGet('jenis');
        $jenis2 = $this->request->getGet('jenis2');
        $season = $this->request->getGet('season');
        $materialType = $this->request->getGet('material_type');

        if ($tujuan == 'CELUP') {
            $penerima = 'Retno';
        } else {
            $penerima = 'Paryanti';
        }

        $result = $this->openPoModel->getData($no_model, $jenis, $jenis2);
        $unit = $this->masterOrderModel->getUnit($no_model);

        // Inisialisasi FPDF
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();

        // Garis margin luar (lebih tebal)
        $pdf->SetDrawColor(0, 0, 0); // Warna hitam
        $pdf->SetLineWidth(0.4); // Lebih tebal
        $pdf->Rect(9, 9, 279, 192); // Sedikit lebih besar dari margin dalam

        // Garis margin dalam (lebih tipis)
        $pdf->SetLineWidth(0.2); // Lebih tipis
        $pdf->Rect(10, 10, 277, 190); // Ukuran aslinya

        // Masukkan gambar di dalam kolom
        $x = $pdf->GetX(); // Simpan posisi X saat ini
        $y = $pdf->GetY(); // Simpan posisi Y saat ini

        // Menambahkan gambar
        $pdf->Image('assets/img/logo-kahatex.png', $x + 16, $y + 1, 10, 8); // Lokasi X, Y, lebar, tinggi

        // Header
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(43, 13, '', 1, 0, 'C'); // Tetap di baris yang sama
        // Set warna latar belakang menjadi biru telur asin (RGB: 170, 255, 255)
        $pdf->SetFillColor(170, 255, 255);
        $pdf->Cell(234, 4, 'FORMULIR', 1, 1, 'C', 1); // Pindah ke baris berikutnya setelah ini

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(43, 5, '', 0, 0, 'L'); // Tetap di baris yang sama
        $pdf->Cell(234, 5, 'DEPARTMEN CELUP CONES', 0, 1, 'C'); // Pindah ke baris berikutnya setelah ini

        $pdf->SetFont('Arial', '', 5);
        $pdf->Cell(43, 4, 'PT KAHATEX', 0, 0, 'C'); // Tetap di baris yang sama
        $pdf->Cell(234, 4, 'FORMULIR PO', 0, 1, 'C'); // Pindah ke baris berikutnya setelah ini

        // Tabel Header Atas
        $pdf->SetFont('Arial', '', 5);
        $pdf->Cell(43, 4, 'No. Dokumen', 1, 0, 'L');
        $pdf->Cell(162, 4, 'FOR-CC-087/REV_01/HAL_1/1', 1, 0, 'L');
        $pdf->Cell(31, 4, 'Tanggal Revisi', 1, 0, 'L');
        $pdf->Cell(41, 4, '04 Desember 2019', 1, 1, 'L');

        $pdf->Cell(205, 4, '', 1, 0, 'L');
        $pdf->Cell(31, 4, 'Klasifikasi', 1, 0, 'L');
        $pdf->Cell(41, 4, 'Internal', 1, 1, 'L');

        $pdf->SetFont('Arial', '', 7);

        $pdf->Cell(43, 5, 'PO', 0, 0, 'L');
        $pdf->Cell(30, 5, ': ' . $no_model, 0, 1, 'L');

        $cellW1 = 20;  // lebar season
        $cellW2 = 30;  // lebar materialType
        $lineH  = 4;   // tinggi tiap baris wrap

        $seasonText = $season ?? '';
        $mtText     = $materialType ?? '';

        // Hitung tinggi masing-masing
        $nb1   = ceil($pdf->GetStringWidth($seasonText) / $cellW1);
        $nb1   = max(1, $nb1);
        $rowH1 = $nb1 * $lineH;

        $nb2   = ceil($pdf->GetStringWidth($mtText) / $cellW2);
        $nb2   = max(1, $nb2);
        $rowH2 = $nb2 * $lineH;

        $rowH = max($rowH1, $rowH2);

        $startX = $pdf->GetX();
        $startY = $pdf->GetY();

        $rawUnit = $unit['unit'];
        $rawUnit = strtoupper(trim($rawUnit));

        $pemesanan = 'KAOS KAKI';
        if ($rawUnit === 'MAJALAYA') {
            $pemesanan .= ' / ' . $rawUnit;
        }

        $pdf->Cell(43, $rowH, 'Pemesanan', 0, 0, 'L');
        $pdf->Cell(50, $rowH, ': ' . $pemesanan, 0, 0, 'L');

        //Simpan posisi awal Season & MaterialType
        $x = $pdf->GetX();
        $y = $pdf->GetY();

        //Season
        $pdf->MultiCell($cellW1, $lineH, $seasonText, 0, 'C');
        $pdf->SetXY($x + $cellW1, $y);

        //Material Type
        $pdf->SetFont('Arial', 'U', 7);
        $pdf->MultiCell($cellW2, $lineH, $mtText, 0, 'C');
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetXY($startX, $startY + $rowH);

        $pdf->Cell(43, 5, 'Tgl', 0, 0, 'L');
        if (!empty($result)) {
            $pdf->Cell(234, 5, ': ' . $result[0]['tgl_po'], 0, 1, 'L');
        } else {
            $pdf->Cell(234, 5, ': No delivery date available', 0, 1, 'L');
        }

        // Tabel Header Baris Pertama
        $pdf->SetFont('Arial', '', 9);
        // Merge cells untuk kolom No, Bentuk Celup, Warna, Kode Warna, Buyer, Nomor Order, Delivery, Untuk Produksi, Contoh Warna, Keterangan Celup
        $pdf->Cell(6, 16, 'No', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(37, 8, 'Benang', 1, 0, 'C'); // Merge 2 kolom ke samping untuk baris pertama
        $pdf->MultiCell(17, 8, 'Bentuk Celup', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 16);
        $pdf->Cell(60, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(20, 16, 'Warna', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(20, 16, 'Kode Warna', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(10, 16, 'Buyer', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(25, 16, 'Nomor Order', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(16, 16, 'Delivery', 1, 0, 'C'); // Merge 2 baris
        $pdf->MultiCell(15, 4, 'Qty Pesanan', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 8);
        $pdf->Cell(166, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(52, 8, 'Permintaan Kelos', 1, 0, 'C'); // Merge 4 kolom
        $pdf->MultiCell(18, 8, 'Untuk Produksi', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 16);
        $pdf->Cell(236, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->MultiCell(18, 8, 'Contoh Warna', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 16);
        $pdf->Cell(254, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->MultiCell(23, 8, 'Keterangan Celup', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 16);
        $pdf->Cell(277, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(23, 16, '', 0, 1, 'C'); // Merge 2 baris

        // Sub-header untuk kolom "Benang" dan "Permintaan Kelos"
        $pdf->Cell(6, -8, '', 0, 0); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(25, -8, 'Jenis', 1, 0, 'C');
        $pdf->Cell(12, -8, 'Kode', 1, 0, 'C');
        $pdf->Cell(108, -8, '', 0, 0); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(15, -8, 'Kg', 1, 0, 'C'); // Merge 4 kolom untuk Permintaan Kelos
        $pdf->Cell(13, -8, 'Kg', 1, 0, 'C');
        $pdf->Cell(13, -8, 'Yard', 1, 0, 'C');
        $pdf->MultiCell(13, -4, 'Cones Total', 1, 'C', false);
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 8);
        $pdf->Cell(205, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->MultiCell(13, -4, 'Cones Jenis', 1, 'C', false);
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 8);
        $pdf->Cell(218, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(87, -8, '', 0, 2, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(87, 8, '', 0, 1, 'C'); // Kosong untuk menyesuaikan posisi


        $lineHeight = 3;
        $pdf->SetFont('Arial', '', 7);
        $no = 1;
        $yLimit = 180;

        foreach ($result as $row) {
            $rowHeight = 5;
            $heights = [];

            // Kolom item_type dengan center vertikal dan horizontal
            $itemType = $row['spesifikasi_benang']
                ? $row['item_type'] . ' (' . $row['spesifikasi_benang'] . ')'
                : $row['item_type'];

            // hitung jumlah baris per kolom
            $heights = [
                'item_type'      => ceil($pdf->GetStringWidth($itemType) / 25) * $rowHeight,
                'ukuran'         => ceil($pdf->GetStringWidth($row['ukuran']) / 12) * $rowHeight,
                'bentuk_celup'   => ceil($pdf->GetStringWidth($row['bentuk_celup']) / 17) * $rowHeight,
                'buyer'          => ceil($pdf->GetStringWidth($row['buyer']) / 10) * $rowHeight,
                'color'          => ceil($pdf->GetStringWidth($row['color']) / 20) * $rowHeight,
                'kode_warna'     => ceil($pdf->GetStringWidth($row['kode_warna']) / 19) * $rowHeight,
                'no_order'       => ceil($pdf->GetStringWidth($row['no_order']) / 25) * $rowHeight,
                'jenis_produksi' => ceil($pdf->GetStringWidth($row['jenis_produksi']) / 15) * $rowHeight,
                'ket_celup'      => ceil($pdf->GetStringWidth($row['ket_celup']) / 23) * $rowHeight,
            ];

            $rowHeight = max($heights);

            if ($pdf->GetY() + $rowHeight > $yLimit) {
                $pdf->AddPage();
                $this->generateHeaderOpenPO($pdf, $no_model);
            }
            $yStart = $pdf->GetY(); // posisi awal Y
            $xStart = $pdf->GetX(); // posisi awal X

            // Kolom No
            $pdf->SetXY($xStart, $yStart);

            // Tulis data dengan MultiCell untuk kolom yang membutuhkan wrap text
            $pdf->Cell(6, $rowHeight, $no++, 1, 0, 'C'); // No
            $xNow = $pdf->GetX();
            $rowItem = $heights['item_type'] / 5 > 1 ? 5 : $rowHeight;
            $pdf->MultiCell(25, $rowItem, $itemType, 1, 'C'); // Jenis
            $pdf->SetXY($xNow + 25, $yStart);

            $xNow = $pdf->GetX();
            $rowUkuran = $heights['ukuran'] / 5 > 1 ?  5 : $rowHeight;
            $pdf->MultiCell(12, $rowUkuran, $row['ukuran'], 1, 'C'); // Kode
            $pdf->SetXY($xNow + 12, $yStart);

            $xNow = $pdf->GetX();
            $rowBc = $heights['bentuk_celup'] / 5 > 1 ?  5 : $rowHeight;
            $pdf->MultiCell(17, $rowBc, $row['bentuk_celup'], 1, 'C'); // Bentuk Celup
            $pdf->SetXY($xNow + 17, $yStart);

            $xNow = $pdf->GetX();
            $rowColor = $heights['color'] / 5 > 1 ?  5 : $rowHeight;
            $pdf->MultiCell(20, $rowColor, $row['color'], 1, 'C'); // Warna
            $pdf->SetXY($xNow + 20, $yStart);

            $xNow = $pdf->GetX();
            // dd($heights['kode_warna']);
            $rowKode = $heights['kode_warna'] / 5 > 1 ?  5 : $rowHeight;
            $pdf->MultiCell(20, $rowKode, $row['kode_warna'], 1, 'C'); // Kode Warna
            $pdf->SetXY($xNow + 20, $yStart);

            $pdf->Cell(10, $rowHeight, $row['buyer'], 1, 0, 'C'); // Buyer

            $xNow = $pdf->GetX();
            $rowNoOrder = $heights['no_order'] / 5 > 1 ?  5 : $rowHeight;
            $pdf->MultiCell(25, $rowNoOrder, $row['no_order'], 1, 'C'); // Nomor Order
            $pdf->SetXY($xNow + 25, $yStart);

            $pdf->Cell(16, $rowHeight, $row['delivery_awal'], 1, 0, 'C'); // Delivery
            $pdf->Cell(15, $rowHeight, number_format($row['kg_po'], 2), 1, 0, 'C'); // Qty Pesanan (Kg)
            $pdf->Cell(13, $rowHeight, $row['kg_percones'], 1, 0, 'C'); // Kg Per Cones
            $pdf->Cell(13, $rowHeight, '', 1, 0, 'C'); // Yard
            $pdf->Cell(13, $rowHeight, $row['jumlah_cones'], 1, 0, 'C'); // Cones Total
            $pdf->Cell(13, $rowHeight, '', 1, 0, 'C'); // Cones Jenis

            $xNow = $pdf->GetX();
            $rowJp = $heights['jenis_produksi'] / 5 > 1 ?  5 : $rowHeight;
            $pdf->MultiCell(18, $rowJp, $row['jenis_produksi'], 1, 'C'); // Untuk Produksi
            $pdf->SetXY($xNow + 18, $yStart);

            $xNow = $pdf->GetX();
            $pdf->MultiCell(18, $rowHeight, $row['contoh_warna'], 1, 'C'); // Contoh Warna
            $pdf->SetXY($xNow + 18, $yStart);

            $xNow = $pdf->GetX();
            $rowKc = $heights['ket_celup'] / 5 > 1 ?  5 : $rowHeight;
            $pdf->MultiCell(23, $rowKc, $row['ket_celup'], 1, 'C'); // Keterangan Celup
            $pdf->SetXY($xNow + 23, $yStart);

            $pdf->Ln($rowHeight); // Pindah ke baris berikutnya
        }

        //KETERANGAN
        $pdf->Cell(277, 5, '', 0, 1, 'C');
        $pdf->Cell(85, 5, 'KET', 0, 0, 'R');
        $pdf->SetFillColor(255, 255, 255); // Atur warna latar belakang menjadi putih
        // Check if the result array is not empty and display only the first delivery_awal
        if (!empty($result)) {
            $pdf->MultiCell(117, 5, ': ' . $result[0]['keterangan'], 0, 1, 'L');
        } else {
            $pdf->MultiCell(117, 5, ': ', 0, 1, 'L');
        }

        $pdf->Cell(277, 5, '', 0, 1, 'C');
        $pdf->Cell(170, 5, 'UNTUK DEPARTMEN ' . $tujuan, 0, 1, 'C');

        $pdf->Cell(55, 5, '', 0, 0, 'C');
        $pdf->Cell(55, 5, 'Pemesanan', 0, 0, 'C');
        $pdf->Cell(55, 5, 'Mengetahui', 0, 0, 'C');
        $pdf->Cell(55, 5, 'Tanda Terima ' . $tujuan, 0, 1, 'C');

        $pdf->Cell(55, 12, '', 0, 1, 'C');

        $pdf->Cell(55, 5, '', 0, 0, 'C');
        $pdf->Cell(55, 5, '(                               )', 0, 0, 'C');
        if (!empty($result)) {
            $pdf->Cell(55, 5, '(       ' . $result[0]['penanggung_jawab'] . '      )', 0, 0, 'C');
        } else {
            $pdf->Cell(234, 5, ': No penanggung_jawab available', 0, 0, 'C');
        }
        $pdf->Cell(55, 5, '(       ' . $penerima . '       )', 0, 1, 'C');

        // Output PDF
        return $this->response->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('S'));
    }

    public function generateHeaderOpenPO($pdf, $no_model)
    {
        // Garis margin luar (lebih tebal)
        $pdf->SetDrawColor(0, 0, 0); // Warna hitam
        $pdf->SetLineWidth(0.4); // Lebih tebal
        $pdf->Rect(9, 9, 279, 192); // Sedikit lebih besar dari margin dalam

        // Garis margin dalam (lebih tipis)
        $pdf->SetLineWidth(0.2); // Lebih tipis
        $pdf->Rect(10, 10, 277, 190); // Ukuran aslinya

        // Masukkan gambar di dalam kolom
        $x = $pdf->GetX(); // Simpan posisi X saat ini
        $y = $pdf->GetY(); // Simpan posisi Y saat ini

        // Menambahkan gambar
        $pdf->Image('assets/img/logo-kahatex.png', $x + 16, $y + 1, 10, 8); // Lokasi X, Y, lebar, tinggi

        // Header
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(43, 13, '', 1, 0, 'C'); // Tetap di baris yang sama
        // Set warna latar belakang menjadi biru telur asin (RGB: 170, 255, 255)
        $pdf->SetFillColor(170, 255, 255);
        $pdf->Cell(234, 4, 'FORMULIR', 1, 1, 'C', 1); // Pindah ke baris berikutnya setelah ini

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(43, 5, '', 0, 0, 'L'); // Tetap di baris yang sama
        $pdf->Cell(234, 5, 'DEPARTMEN CELUP CONES', 0, 1, 'C'); // Pindah ke baris berikutnya setelah ini

        $pdf->SetFont('Arial', '', 5);
        $pdf->Cell(43, 4, 'PT KAHATEX', 0, 0, 'C'); // Tetap di baris yang sama
        $pdf->Cell(234, 4, 'FORMULIR PO', 0, 1, 'C'); // Pindah ke baris berikutnya setelah ini


        // Tabel Header Atas
        $pdf->SetFont('Arial', '', 5);
        $pdf->Cell(43, 4, 'No. Dokumen', 1, 0, 'L');
        $pdf->Cell(162, 4, 'FOR-CC-087/REV_01/HAL_1/1', 1, 0, 'L');
        $pdf->Cell(31, 4, 'Tanggal Revisi', 1, 0, 'L');
        $pdf->Cell(41, 4, '04 Desember 2019', 1, 1, 'L');

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(43, 5, 'PO', 0, 0, 'L');
        $pdf->Cell(234, 5, ': ' . $no_model, 0, 1, 'L');

        $pdf->Cell(43, 5, 'Pemesanan', 0, 0, 'L');
        $pdf->Cell(234, 5, ': KAOS KAKI', 0, 1, 'L');

        $pdf->Cell(43, 5, 'Tgl', 0, 0, 'L');
        // Check if the result array is not empty and display only the first delivery_awal
        if (!empty($result)) {
            $pdf->Cell(234, 5, ': ' . $result[0]['tgl_po'], 0, 1, 'L');
        } else {
            $pdf->Cell(234, 5, ': No delivery date available', 0, 1, 'L');
        }

        // Tabel Header Baris Pertama
        $pdf->SetFont('Arial', '', 9);
        // Merge cells untuk kolom No, Bentuk Celup, Warna, Kode Warna, Buyer, Nomor Order, Delivery, Untuk Produksi, Contoh Warna, Keterangan Celup
        $pdf->Cell(6, 16, 'No', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(37, 8, 'Benang', 1, 0, 'C'); // Merge 2 kolom ke samping untuk baris pertama
        $pdf->MultiCell(17, 8, 'Bentuk Celup', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 16);
        $pdf->Cell(60, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(20, 16, 'Warna', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(20, 16, 'Kode Warna', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(10, 16, 'Buyer', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(25, 16, 'Nomor Order', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(16, 16, 'Delivery', 1, 0, 'C'); // Merge 2 baris
        $pdf->MultiCell(15, 4, 'Qty Pesanan', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 8);
        $pdf->Cell(166, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(52, 8, 'Permintaan Kelos', 1, 0, 'C'); // Merge 4 kolom
        $pdf->MultiCell(18, 8, 'Untuk Produksi', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 16);
        $pdf->Cell(236, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->MultiCell(18, 8, 'Contoh Warna', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 16);
        $pdf->Cell(254, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->MultiCell(23, 8, 'Keterangan Celup', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 16);
        $pdf->Cell(277, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(23, 16, '', 0, 1, 'C'); // Merge 2 baris

        // Sub-header untuk kolom "Benang" dan "Permintaan Kelos"
        $pdf->Cell(6, -8, '', 0, 0); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(12, -8, 'Jenis', 1, 0, 'C');
        $pdf->Cell(25, -8, 'Kode', 1, 0, 'C');
        $pdf->Cell(108, -8, '', 0, 0); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(15, -8, 'Kg', 1, 0, 'C'); // Merge 4 kolom untuk Permintaan Kelos
        $pdf->Cell(13, -8, 'Kg', 1, 0, 'C');
        $pdf->Cell(13, -8, 'Yard', 1, 0, 'C');
        $pdf->MultiCell(13, -4, 'Cones Total', 1, 'C', false);
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 8);
        $pdf->Cell(205, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->MultiCell(13, -4, 'Cones Jenis', 1, 'C', false);
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 8);
        $pdf->Cell(218, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(87, -8, '', 0, 2, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(87, 8, '', 0, 1, 'C'); // Kosong untuk menyesuaikan posisi
    }

    public function printBon($idBon)
    {
        $username = session()->get('username');
        // data ALL BON
        $dataBon = $this->bonCelupModel->getDataById($idBon); // get data by id_bon
        $detailBon = $this->outCelupModel->getDetailBonByIdBon($idBon); // get data detail bon by id_bon
        // dd($detailBon);

        // Mengelompokkan data detailBon berdasarkan no_model, item_type, dan kode_warna
        $groupedDetails = [];
        foreach ($detailBon as $detail) {
            $key = $detail['no_model'] . '|' . $detail['item_type'] . '|' . $detail['kode_warna'];
            $jmlKarung =
                $gantiRetur = ($detail['ganti_retur'] == 1) ? ' / GANTI RETUR' : '';
            if (!isset($groupedDetails[$key])) {
                $groupedDetails[$key] = [
                    'no_model' => $detail['no_model'],
                    'item_type' => $detail['item_type'],
                    'kode_warna' => $detail['kode_warna'],
                    'warna' => $detail['warna'],
                    'buyer' => $detail['buyer'],
                    'ukuran' => $detail['ukuran'],
                    'lot_kirim' => $detail['lot_kirim'],
                    'l_m_d' => $detail['l_m_d'],
                    'harga' => $detail['harga'],
                    'detailPengiriman' =>  [],
                    'totals' => [
                        'cones_kirim' => 0,
                        'gw_kirim' => 0,
                        'kgs_kirim' => 0,
                    ],
                    'ganti_retur' => $gantiRetur,
                    'jmlKarung' => 0,
                    'barcodes' => [], // Untuk menyimpan barcode
                ];
            }
            // Menambahkan data pengiriman untuk grup ini tanpa dijumlahkan
            $groupedDetails[$key]['detailPengiriman'][] = [
                'id_out_celup' => $detail['id_out_celup'],
                'cones_kirim' => $detail['cones_kirim'],
                'gw_kirim' => $detail['gw_kirim'],
                'kgs_kirim' => $detail['kgs_kirim'],
                'lot_kirim' => $detail['lot_kirim'],
                'no_karung' => $detail['no_karung'],
            ];
            // Menambahkan nilai ke total
            $groupedDetails[$key]['totals']['gw_kirim'] += $detail['gw_kirim'];
            $groupedDetails[$key]['totals']['kgs_kirim'] += $detail['kgs_kirim'];
            $groupedDetails[$key]['totals']['cones_kirim'] += $detail['cones_kirim'];

            // Menghitung jumlah baris data detailBon pada grup ini (jumlah karung)
            $groupedDetails[$key]['jmlKarung'] = count($groupedDetails[$key]['detailPengiriman']);

            // Tambahkan ID outCelup
            $groupedDetails[$key]['idsOutCelup'][] = $detail['id_out_celup'];
        }

        // Buat instance Barcode Generator
        $generator = new BarcodeGeneratorPNG();

        // Hasilkan barcode untuk setiap ID outCelup di grup
        foreach ($groupedDetails as &$group) {
            foreach ($group['detailPengiriman'] as $outCelup => $id) {
                // Hasilkan barcode dan encode sebagai base64
                $id_out_celup = $id['id_out_celup'];
                $barcode = $generator->getBarcode($id_out_celup, $generator::TYPE_CODE_128);
                $group['barcodes'][] = [
                    'no_model' => $group['no_model'],
                    'item_type' => $group['item_type'],
                    'kode_warna' => $group['kode_warna'],
                    'warna' => $group['warna'],
                    'id_out_celup' => $id['id_out_celup'],
                    'gw' => $id['gw_kirim'],
                    'kgs' => $id['kgs_kirim'],
                    'cones' => $id['cones_kirim'],
                    'lot' => $id['lot_kirim'],
                    'no_karung' => $id['no_karung'],
                    'barcode' => base64_encode($barcode),
                ];
            }
        }

        // Menggabungkan data utama dan detail yang sudah dikelompokkan
        $dataBon['groupedDetails'] = array_values($groupedDetails);

        // dd($dataBon);

        $pdf = new FPDF('P', 'mm', 'A4');

        for ($b = 1; $b <= 3; $b++) {

            if ($b === 2) {
                $yBorder = 150;
                $yPosition = 151; // Posisi untuk bon kedua                
            } else {
                $yBorder = 3;
                $yPosition = 4; // Posisi dinamis untuk bon pertama dan ketiga
                $pdf->AddPage();
            }
            // Inisialisasi FPDF
            $pdf->SetAutoPageBreak(true, 5); // Atur margin bawah saat halaman penuh

            // Tambahkan border margin
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetLineWidth(0.4);
            $pdf->Rect(3, $yBorder, 204, 142);

            // Tambahkan double border margin
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetLineWidth(0.4);
            $pdf->Rect(4, $yPosition, 202, 140);

            // Kembalikan ke properti default untuk border
            $pdf->SetDrawColor(0, 0, 0); // Tetap hitam jika digunakan pada elemen lain
            $pdf->SetLineWidth(0.2);    // Kembali ke garis default
            $pdf->SetMargins(4, 4, 4, 4); // Margin kiri, atas, kanan
            $pdf->SetXY(4, $yPosition); // Mulai di margin kiri (X=5) dan sedikit di bawah border (Y=5)
            $pdf->SetAutoPageBreak(true,); // Aktifkan auto page break dengan margin bawah 10

            // Menambahkan gambar
            $pdf->Image('assets/img/logo-kahatex.png', 20, $yPosition + 1, 8, 7); // X=10 untuk margin, Y=10 untuk margin atas
            // Header
            $pdf->SetX(4); // Pastikan posisi X sejajar margin
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(40, 12, '', 1, 0, 'C');

            // Set warna latar belakang menjadi biru telur asin (RGB: 170, 255, 255)
            $pdf->SetFillColor(170, 255, 255);
            $pdf->Cell(162, 4, 'FORMULIR', 0, 1, 'C', 1);

            $pdf->SetFillColor(255, 255, 255); // Ubah latar belakang menjadi putih

            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(40, 4, '', 0, 0, 'L');
            $pdf->Cell(162, 4, 'DEPARTMEN KELOS WARNA', 0, 1, 'C');

            $pdf->SetX(4); // Pastikan posisi X sejajar margin
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(40, 4, 'PT. KAHATEX', 0, 0, 'C');

            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(162, 4, 'BON PENGIRIMAN', 1, 1, 'C');

            // Tabel Header Atas
            $pdf->SetX(4); // Pastikan posisi X sejajar margin
            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(40, 3, 'No. Dokumen', 1, 0, 'L');
            $pdf->Cell(107, 3, 'FOR-KWA-006/REV_03/HAL_1/1', 1, 0, 'L');
            $pdf->Cell(27, 3, 'Tanggal Revisi', 1, 0, 'L');
            $pdf->Cell(28, 3, '07 Januari 2021', 1, 1, 'L');

            $pdf->SetX(4); // Pastikan posisi X sejajar margin
            $pdf->Cell(40, 3, 'NAMA LANGGANAN', 0, 0, 'L');
            $pdf->Cell(61, 3, 'KAOS KAKI', 0, 0, 'L');
            $pdf->Cell(62, 3, 'NO SURAT JALAN : ' . $dataBon['no_surat_jalan'], 0, 0, 'L');
            $pdf->Cell(36, 3, 'TANGGAL : ' . $dataBon['tgl_datang'], 0, 1, 'L');

            $pdf->SetX(4); // Pastikan posisi X sejajar margin
            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(18, 8, 'NO PO', 1, 0, 'C');
            $pdf->Cell(22, 8, 'JENIS BENANG', 1, 0, 'C');
            // Menentukan posisi awal untuk KODE BENANG
            $xKB = $pdf->GetX();
            $yKB = $pdf->GetY();
            $pdf->MultiCell(11, 4, 'KODE BENANG', 1, 'C', false); // wrap text
            // Mengembalikan posisi setelah MultiCell
            $pdf->SetXY($xKB + 11, $yKB);

            $pdf->Cell(29, 8, 'KODE WARNA', 1, 0, 'C');
            $pdf->Cell(18, 8, 'WARNA', 1, 0, 'C');
            $pdf->Cell(13, 8, 'LOT CELUP', 1, 0, 'C');
            $pdf->Cell(7, 8, 'L/M/D', 1, 0, 'C');
            // Membagi kolom "HARGA" menjadi dua baris
            $xHarga = $pdf->GetX();
            $yHarga = $pdf->GetY();
            $pdf->Cell(9, 3, 'HARGA', 1, 2, 'C'); // Baris pertama (HARGA)
            $pdf->SetXY($xHarga, $yHarga  + 3);
            $pdf->Cell(9, 5, 'PER KG', 1, 0, 'C'); // Baris kedua (PER KG)

            // Kolom "CONES" dengan tinggi penuh sejajar kolom paling awal
            $pdf->SetXY($xHarga + 9, $yHarga); // Mengatur posisi kolom "CONES" kembali ke baris awal
            $pdf->Cell(8, 8, 'CONES', 1, 0, 'C');

            $xQty = $pdf->GetX();
            $yQty = $pdf->GetY();
            $pdf->Cell(18, 3, 'QTY', 1, 2, 'C');
            $pdf->SetXY($xQty, $yQty + 3);
            $xGw = $pdf->GetX();
            $yGw = $pdf->GetY();
            $pdf->MultiCell(9, 2.5, 'GW (KG)', 1, 'C', false); // wrap text
            // Mengembalikan posisi setelah MultiCell
            $pdf->SetXY($xGw + 9, $yGw);
            $pdf->MultiCell(9, 2.5, 'NW (KG)', 1, 'C', false); // wrap text
            // Mengembalikan posisi setelah MultiCell
            $pdf->SetXY($xGw + 9, $yGw);
            $pdf->SetXY($xQty + 18, $yQty);

            $xTotal = $pdf->GetX();
            $yTotal = $pdf->GetY();
            $pdf->Cell(27, 3, 'TOTAL', 1, 1, 'C');
            $pdf->SetXY($xTotal, $yTotal + 3);
            $pdf->Cell(9, 5, 'CONES', 1, 0, 'C');
            $xGw = $pdf->GetX();
            $yGw = $pdf->GetY();
            $pdf->MultiCell(9, 2.5, 'GW (KG)', 1, 'C', false); // wrap text
            // Mengembalikan posisi setelah MultiCell
            $pdf->SetXY($xGw + 9, $yGw);
            $pdf->MultiCell(9, 2.5, 'NW (KG)', 1, 'C', false); // wrap text
            // Mengembalikan posisi setelah MultiCell
            $pdf->SetXY($xGw + 9, $yGw);
            $pdf->SetXY($xQty + 18, $yQty);

            $pdf->SetXY($xTotal + 27, $yTotal);
            $pdf->Cell(22, 8, 'KETERANGAN', 1, 1, 'C');


            $counter = [];
            $prevNoModel = null; // Variabel untuk menyimpan no_model sebelumnya
            $prevItemType = null; // Variabel untuk menyimpan item_type sebelumnya
            $prevKodeWarna = null; // Variabel untuk menyimpan kode_warna sebelumnya
            $totalRows = 32; // Total baris yang diinginkan
            $row = 0;
            $currentRow = 0; // Variabel untuk menghitung jumlah baris yang sudah tercetak

            foreach ($dataBon['groupedDetails'] as $bon) {
                $pdf->SetFont('Arial', '', 6);
                // Mengelompokkan berdasarkan no_model, item_type, dan kode_warna
                $key = $bon['no_model'] . '_' . $bon['item_type'] . '_' . $bon['kode_warna'];

                // Jika kombinasi tersebut belum ada di array counter, buat entri baru
                if (!isset($counter[$key])) {
                    $counter[$key] = 0;
                }
                foreach ($bon['detailPengiriman'] as $detail) {
                    $counter[$key]++;
                }

                // Hitung jumlah detail untuk grup saat ini
                $jmlDetail = count($bon['detailPengiriman']);
                // $jmlBaris = ($jmlDetail === 1) ? 3 : 2;

                $pdf->SetX(4); // Pastikan posisi X sejajar margin
                $pdf->Cell(18, 3, $bon['no_model'], 1, 0, 'C');
                $x2 = $pdf->GetX();
                $y2 = $pdf->GetY();

                // MultiCell untuk kolom item_type (tinggi fleksibel)
                $pdf->MultiCell(22, 3, $bon['item_type'], 1, 'C', false);
                // Kembalikan posisi untuk kolom berikutnya
                $pdf->SetXY($x2 + 22, $y2);

                // MultiCell untuk kolom ukuran (tinggi fleksibel)
                $x3 = $pdf->GetX();
                $pdf->MultiCell(11, 3, $bon['ukuran'], 1, 'C', false);
                // Kembalikan posisi untuk kolom berikutnya
                $pdf->SetXY($x3 + 11, $y2);

                // MultiCell untuk kolom kode warna (tinggi fleksibel)
                $x4 = $pdf->GetX();
                $pdf->MultiCell(29, 3, $bon['kode_warna'], 1, 'C', false);
                // Kembalikan posisi untuk kolom berikutnya
                $pdf->SetXY($x4 + 29, $y2);

                // MultiCell untuk kolom warna (tinggi fleksibel)
                $x5 = $pdf->GetX();
                $pdf->MultiCell(18, 3, $bon['warna'], 1, 'C', false);
                // Kembalikan posisi untuk kolom berikutnya
                $pdf->SetXY($x5 + 18, $y2);

                // MultiCell untuk kolom lot_kirim (tinggi fleksibel)
                $x6 = $pdf->GetX();
                $pdf->MultiCell(13, 3, $bon['lot_kirim'], 1, 'C', false);

                // // Hitung tinggi maksimum dari semua kolom
                // $maxHeight = max($multiCellHeight1, $multiCellHeight2, $multiCellHeight3, $multiCellHeight4, $multiCellHeight5, 8);

                // Kembalikan posisi untuk kolom berikutnya
                $pdf->SetXY($x6 + 13, $y2);
                $pdf->Cell(7, 3, $bon['l_m_d'], 1, 0, 'C');
                $pdf->Cell(9, 3, $bon['harga'], 1, 0, 'C');
                foreach ($bon['detailPengiriman'] as $detail) {
                    // var_dump($row);
                    $row++;
                    if ($counter[$key] == 1) {
                        $pdf->Cell(8, 3, $detail['cones_kirim'], 1, 0, 'C');
                        $pdf->Cell(9, 3, $detail['gw_kirim'], 1, 0, 'C');
                        $pdf->Cell(9, 3, $detail['kgs_kirim'], 1, 0, 'C');
                        $pdf->Cell(9, 3, $bon['totals']['cones_kirim'], 1, 0, 'C');
                        $pdf->Cell(9, 3, $bon['totals']['gw_kirim'], 1, 0, 'C');
                        $pdf->Cell(9, 3, $bon['totals']['kgs_kirim'], 1, 0, 'C');
                        $xKet = $pdf->GetX();
                        $yKet = $pdf->GetY();
                        $pdf->MultiCell(22, 3, $bon['jmlKarung'] . " KARUNG" . $bon['ganti_retur'], 1, 'L', false);
                        $pdf->SetY($yKet + 3); // Kembalikan posisi untuk kolom berikutnya
                        $currentRow++;
                        // baris baru
                        $xBuyer = $pdf->GetX();
                        $yBuyer = $pdf->GetY();
                        $pdf->SetX($xBuyer); // Kembali ke posisi X tempat sebelumnya
                        $pdf->MultiCell(18, 3, $bon['buyer'] . ' KK', 1, 'C', false); // Menerapkan MultiCell untuk 'buyer'
                        $pdf->SetXY($xBuyer + 18, $yBuyer - 3); // Kembalikan posisi untuk kolom berikutnya
                        $pdf->Cell(22, 3, '', 1, 0, 'C');
                        $pdf->Cell(11, 3, '', 1, 0, 'C');
                        $pdf->Cell(29, 3, '', 1, 0, 'C');
                        $pdf->Cell(18, 3, '', 1, 0, 'C');
                        $pdf->Cell(13, 3, '', 1, 0, 'C');
                        $pdf->Cell(7, 3, '', 1, 0, 'C');
                        $pdf->Cell(9, 3, '', 1, 0, 'C');
                        $pdf->Cell(8, 3, '', 1, 0, 'C');
                        $pdf->Cell(9, 3, '', 1, 0, 'C');
                        $pdf->Cell(9, 3, '', 1, 0, 'C');
                        $pdf->Cell(9, 3, '', 1, 0, 'C');
                        $pdf->Cell(9, 3, '', 1, 0, 'C');
                        $pdf->Cell(9, 3, '', 1, 0, 'C');
                        $pdf->Cell(22, 3, '', 1, 1, 'L');
                        $currentRow++; // Update baris yang sudah dicetak
                    } else {
                        if ($row == 1) {
                            $pdf->Cell(8, 3, $detail['cones_kirim'], 1, 0, 'C');
                            $pdf->Cell(9, 3, $detail['gw_kirim'], 1, 0, 'C');
                            $pdf->Cell(9, 3, $detail['kgs_kirim'], 1, 0, 'C');
                            $pdf->Cell(9, 3, $bon['totals']['cones_kirim'], 1, 0, 'C');
                            $pdf->Cell(9, 3, $bon['totals']['gw_kirim'], 1, 0, 'C');
                            $pdf->Cell(9, 3, $bon['totals']['kgs_kirim'], 1, 0, 'C');
                            // MultiCell untuk 'jmlKarung' dan 'ganti_retur'
                            $xKet = $pdf->GetX();
                            $yKet = $pdf->GetY();
                            $pdf->MultiCell(22, 3, $bon['jmlKarung'] . " KARUNG" . $bon['ganti_retur'], 1, 'L', false);

                            // Kembali ke posisi X untuk melanjutkan dari bawah MultiCell
                            $pdf->SetXY($xKet, $yKet + 3);
                            $currentRow++; // Update baris yang sudah dicetak
                        } elseif ($row == 2) {
                            // Baris kedua
                            $pdf->SetX(4); // Set posisi X kembali ke margin
                            $xBuyer = $pdf->GetX(); // Simpan posisi X saat ini
                            $yBuyer = $pdf->GetY(); // Simpan posisi Y saat ini

                            // MultiCell untuk 'buyer'
                            $pdf->MultiCell(18, 3, $bon['buyer'] . ' KK', 1, 'C', false);

                            // Perbarui posisi kursor setelah MultiCell selesai
                            $maxHeight = $pdf->GetY() - $yBuyer; // Hitung tinggi yang digunakan oleh MultiCell
                            $pdf->SetXY($xBuyer + 18, $yBuyer); // Geser posisi X sejajar setelah MultiCell
                            // dd($xBuyer, $yBuyer);

                            $pdf->Cell(22, 3, '', 1, 0, 'C');
                            $pdf->Cell(11, 3, '', 1, 0, 'C');
                            $pdf->Cell(29, 3, '', 1, 0, 'C');
                            $pdf->Cell(18, 3, '', 1, 0, 'C');
                            $pdf->Cell(13, 3, '', 1, 0, 'C');
                            $pdf->Cell(7, 3, '', 1, 0, 'C');
                            $pdf->Cell(9, 3, '', 1, 0, 'C');
                            $pdf->Cell(8, 3, $detail['cones_kirim'], 1, 0, 'C');
                            $pdf->Cell(9, 3, $detail['gw_kirim'], 1, 0, 'C');
                            $pdf->Cell(9, 3, $detail['kgs_kirim'], 1, 0, 'C');
                            $pdf->Cell(9, 3, '', 1, 0, 'C');
                            $pdf->Cell(9, 3, '', 1, 0, 'C');
                            $pdf->Cell(9, 3, '', 1, 0, 'C');
                            $pdf->Cell(22, 3, '', 1, 1, 'L');
                            $currentRow++; // Update baris yang sudah dicetak
                        } else {
                            $pdf->SetX(4); // Pastikan posisi X sejajar margin
                            $pdf->Cell(18, 3, '', 1, 0, 'C');
                            $pdf->Cell(22, 3, '', 1, 0, 'C');
                            $pdf->Cell(11, 3, '', 1, 0, 'C');
                            $pdf->Cell(29, 3, '', 1, 0, 'C');
                            $pdf->Cell(18, 3, '', 1, 0, 'C');
                            $pdf->Cell(13, 3, '', 1, 0, 'C');
                            $pdf->Cell(7, 3, '', 1, 0, 'C');
                            $pdf->Cell(9, 3, '', 1, 0, 'C');
                            $pdf->Cell(8, 3, $detail['cones_kirim'], 1, 0, 'C');
                            $pdf->Cell(9, 3, $detail['gw_kirim'], 1, 0, 'C');
                            $pdf->Cell(9, 3, $detail['kgs_kirim'], 1, 0, 'C');
                            $pdf->Cell(9, 3, '', 1, 0, 'C');
                            $pdf->Cell(9, 3, '', 1, 0, 'C');
                            $pdf->Cell(9, 3, '', 1, 0, 'C');
                            $pdf->Cell(22, 3, '', 1, 1, 'L');
                            $currentRow++; // Update baris yang sudah dicetak
                        }
                    }
                }

                if (
                    $prevNoModel === null || // artinya data pertama
                    ($bon['no_model'] !== $prevNoModel) ||
                    ($bon['item_type'] !== $prevItemType) ||
                    ($bon['kode_warna'] !== $prevKodeWarna)
                ) {
                    // Tentukan jumlah baris kosong yang ingin ditambahkan
                    for ($i = 0; $i < 2; $i++) {
                        $pdf->SetX(4);
                        // Cetak baris kosong dengan format sel yang sesuai
                        $pdf->Cell(18, 3, '', 1, 0, 'C');
                        $pdf->Cell(22, 3, '', 1, 0, 'C');
                        $pdf->Cell(11, 3, '', 1, 0, 'C');
                        $pdf->Cell(29, 3, '', 1, 0, 'C');
                        $pdf->Cell(18, 3, '', 1, 0, 'C');
                        $pdf->Cell(13, 3, '', 1, 0, 'C');
                        $pdf->Cell(7, 3, '', 1, 0, 'C');
                        $pdf->Cell(9, 3, '', 1, 0, 'C');
                        $pdf->Cell(8, 3, '', 1, 0, 'C');
                        $pdf->Cell(9, 3, '', 1, 0, 'C');
                        $pdf->Cell(9, 3, '', 1, 0, 'C');
                        $pdf->Cell(9, 3, '', 1, 0, 'C');
                        $pdf->Cell(9, 3, '', 1, 0, 'C');
                        $pdf->Cell(9, 3, '', 1, 0, 'C');
                        $pdf->Cell(22, 3, '', 1, 1, 'L'); // Pindah ke baris baru
                        $currentRow++; // Update baris yang sudah dicetak
                    }

                    // Reset posisi baris saat ini
                    $row = 0;
                }

                // Perbarui nilai sebelumnya
                $prevNoModel = $bon['no_model'];
                $prevItemType = $bon['item_type'];
                $prevKodeWarna = $bon['kode_warna'];
                // var_dump($prevNoModel, $prevItemType, $prevKodeWarna);
            }
            // dd($currentRow, $totalRows);
            // var_dump($prevNoModel);
            // Tambahkan baris kosong jika jumlah baris yang dicetak kurang dari 28
            while ($currentRow <= $totalRows) {
                $pdf->SetX(4);
                $pdf->Cell(18, 3, '', 1, 0, 'C');
                $pdf->Cell(22, 3, '', 1, 0, 'C');
                $pdf->Cell(11, 3, '', 1, 0, 'C');
                $pdf->Cell(29, 3, '', 1, 0, 'C');
                $pdf->Cell(18, 3, '', 1, 0, 'C');
                $pdf->Cell(13, 3, '', 1, 0, 'C');
                $pdf->Cell(7, 3, '', 1, 0, 'C');
                $pdf->Cell(9, 3, '', 1, 0, 'C');
                $pdf->Cell(8, 3, '', 1, 0, 'C');
                $pdf->Cell(9, 3, '', 1, 0, 'C');
                $pdf->Cell(9, 3, '', 1, 0, 'C');
                $pdf->Cell(9, 3, '', 1, 0, 'C');
                $pdf->Cell(9, 3, '', 1, 0, 'C');
                $pdf->Cell(9, 3, '', 1, 0, 'C');
                $pdf->Cell(22, 3, '', 1, 1, 'L');
                $currentRow++; // Update baris yang sudah dicetak
            }
            // Data keterangan
            $keterangan = [
                'KETERANGAN :' => 'GW = GROSS WEIGHT',
                '1' => 'NW = NET WEIGHT',
                '2' => 'L = LIGHT',
                '3' => 'M = MEDIUM',
                '4' => 'D = DARK',
            ];

            // Looping untuk mencetak kolom keterangan
            foreach ($keterangan as $key => $value) {
                $pdf->SetX(4); // Pastikan posisi X sejajar margin
                $pdf->SetFont('Arial', '', 6);
                $pdf->Cell(18, 3, ($key == "KETERANGAN :") ? $key : '', 0, 0, 'L'); // Kolom pertama (key)
                $pdf->Cell(37, 3, $value, 0, 0, 'L'); // Kolom kedua (value)
                $pdf->Cell(72, 3, '', 0, 0, 'L'); // Kosong
                $pdf->Cell(17, 3, $key === 'KETERANGAN :' ? 'PENGIRIM' : ($key === 4 ? $username : ''), 0, 0, 'C');
                $pdf->Cell(23, 3, '', 0, 0, 'L'); // Kosong
                $pdf->Cell(17, 3, $key === 'KETERANGAN :' ? 'PENERIMA' : '', 0, 0, 'C'); // Hanya baris pertama ada "PENERIMA"
                $pdf->Cell(18, 3, '', 0, 1, 'L'); // Kolom terakhir kosong
            }

            $pdf->Ln();  // Fungsi PageNo() untuk mendapatkan nomor halaman

            $pageNo = $pdf->PageNo();  // Fungsi PageNo() untuk mendapatkan nomor halaman
            // jika halaman pertama hitung tinggi
            if ($pageNo >= 2) {
                $startX_ = 2.5;
                $startY_ = 157;
            } else {
                $startX_ = 2.5;
                $startY_ = 14;
            }
        }
        // $pdf->Ln();  // Fungsi PageNo() untuk mendapatkan nomor halaman
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'BARCODE', 0, 1, 'C');

        $barcodeCount = 0; // Counter untuk jumlah barcode di halaman saat ini
        $barcodeWidth = 67; // Lebar kotak barcode
        $barcodeHeight = 67; // Tinggi kotak barcode
        $jarakKolom = 2; // Jarak horizontal antar kolom
        $jarakBaris = 2; // Jarak vertikal antar baris

        foreach ($dataBon['groupedDetails'] as $groups) {
            foreach ($groups['barcodes'] as $barcode) {
                // Menghitung posisi X dan Y untuk 6 barcode per halaman (3 kolom × 2 baris)
                if ($pageNo == 2) {
                    $mod = 6;
                    $baris = 2;
                } else {
                    $mod = 12;
                    $baris = 4;
                }
                // Jika sudah mencapai batas (6 barcode), tambah halaman baru
                if ($barcodeCount > 0 && $barcodeCount % $mod === 0) {
                    $pdf->AddPage(); // Tambahkan halaman baru
                    $startX_ = 2.5; // Reset posisi X untuk halaman baru
                    $startY_ = 14; // Reset posisi Y untuk halaman baru
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->Cell(0, 8, 'BARCODE', 0, 1, 'C');
                    $pageNo++;
                    $barcodeCount = 0; // Reset counter per halaman
                }
                $colIndex = $barcodeCount % 3; // 3 kolom per baris
                $rowIndex = floor($barcodeCount / 3) % $baris; // 2 baris per halaman

                // Menghitung posisi untuk setiap barcode
                $startX = $startX_ + ($colIndex * ($barcodeWidth + $jarakKolom)); // Posisi horizontal
                $startY = $startY_ + ($rowIndex * ($barcodeHeight + $jarakBaris)); // Posisi vertikal

                // Menggambar kotak di sekitar detail
                $pdf->Rect($startX, $startY, 67, 67); // Kotak barcode

                // Menyimpan gambar barcode
                $imageData = base64_decode($barcode['barcode']);
                $tempImagePath = WRITEPATH . 'uploads/barcode_temp' . $barcodeCount . '.png'; // Path file sementara
                file_put_contents($tempImagePath, $imageData);

                // Menentukan posisi X agar gambar berada di tengah kotak secara horizontal
                $imageWidth = 40; // Lebar gambar
                $centerX = $startX + (67 - $imageWidth) / 2; // Menyesuaikan posisi
                $pdf->Image($tempImagePath, $centerX, $startY + 3, $imageWidth); // Tambahkan gambar

                unlink($tempImagePath); // Menghapus file gambar sementara

                // Menghitung berapa banyak baris yang sudah tercetak di dalam MultiCell

                // Menambahkan detail teks di dalam kotak
                $pdf->SetFont('Arial', 'B', 8);
                // Teks detail
                $pdf->SetXY($startX + 2, $startY + 20);
                $pdf->Cell(20, 3, 'No Model', 0, 0, 'L');
                $pdf->Cell(5, 3, ':', 0, 0, 'C');
                $pdf->Cell(70, 3, $barcode['no_model'], 0, 1, 'L');

                $pdf->SetXY($startX + 2, $pdf->getY());
                $pdf->Cell(20, 3, 'Item Type', 0, 0, 'L');
                $pdf->Cell(5, 3, ':', 0, 0, 'C');
                $pdf->MultiCell(39, 3, $barcode['item_type'], 0, 1, 'L');
                // Menyimpan posisi Y setelah MultiCell
                // dd($currentY, $nextY, $totalHeight, $lineCount);
                $pdf->SetXY($startX + 2, $pdf->GetY()); // Menambah jarak berdasarkan jumlah baris yang tercetak

                $pdf->SetXY($startX + 2, $pdf->GetY()); // Menambah jarak berdasarkan jumlah baris yang tercetak
                $pdf->Cell(20, 3, 'Kode Warna', 0, 0, 'L');
                $pdf->Cell(5, 3, ':', 0, 0, 'C');
                $pdf->MultiCell(39, 3, $barcode['kode_warna'], 0, 0, 'L');
                $pdf->SetXY($startX + 2, $pdf->getY()); // Menambah jarak berdasarkan jumlah baris yang tercetak

                $pdf->Cell(20, 3, 'Warna', 0, 0, 'L');
                $pdf->Cell(5, 3, ':', 0, 0, 'C');
                $pdf->MultiCell(39, 3, $barcode['warna'], 0, 0, 'L');
                $pdf->SetXY($startX + 2, $pdf->getY()); // Menambah jarak berdasarkan jumlah baris yang tercetak

                $currentY = $pdf->GetY();
                $pdf->Cell(20, 3, 'GW', 0, 0, 'L');
                $pdf->Cell(5, 3, ':', 0, 0, 'C');
                $pdf->Cell(39, 3, $barcode['gw'], 0, 0, 'L');
                $pdf->SetXY($startX + 2, $currentY + 3); // Menambah jarak berdasarkan jumlah baris yang tercetak

                $pdf->Cell(20, 3, 'NW', 0, 0, 'L');
                $pdf->Cell(5, 3, ':', 0, 0, 'C');
                $pdf->Cell(39, 3, $barcode['kgs'], 0, 0, 'L');
                $pdf->SetXY($startX + 2, $currentY + 6); // Menambah jarak berdasarkan jumlah baris yang tercetak

                $pdf->Cell(20, 3, 'Cones', 0, 0, 'L');
                $pdf->Cell(5, 3, ':', 0, 0, 'C');
                $pdf->Cell(39, 3, $barcode['cones'], 0, 0, 'L');
                $pdf->SetXY($startX + 2, $currentY + 9); // Menambah jarak berdasarkan jumlah baris yang tercetak

                $pdf->Cell(20, 3, 'Lot', 0, 0, 'L');
                $pdf->Cell(5, 3, ':', 0, 0, 'C');
                $pdf->Cell(39, 3, $barcode['lot'], 0, 0, 'L');
                $pdf->SetXY($startX + 2, $currentY + 12); // Menambah jarak berdasarkan jumlah baris yang tercetak

                $pdf->Cell(20, 3, 'No Karung', 0, 0, 'L');
                $pdf->Cell(5, 3, ':', 0, 0, 'C');
                $pdf->Cell(39, 3, $barcode['no_karung'], 0, 1, 'L');
                $pdf->SetXY($startX + 2, $currentY + 15); // Menambah jarak berdasarkan jumlah baris yang tercetak

                // Counter untuk jumlah barcode
                $barcodeCount++;
            }
        }

        // Output PDF
        return $this->response->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('Bon Pengiriman.pdf', 'I'));
    }

    private function generateHeaderPOCovering($pdf, $tgl_po)
    {
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.2);
        $pdf->Rect(10, 10, 277, 190);

        // Garis margin luar (lebih tebal)
        $pdf->SetDrawColor(0, 0, 0); // Warna hitam
        $pdf->SetLineWidth(0.4); // Lebih tebal
        $pdf->Rect(9, 9, 279, 192); // Sedikit lebih besar dari margin dalam

        $pdf->Image('assets/img/logo-kahatex.png', 26, 11, 10, 8);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(43, 13, '', 1, 0, 'C');
        $pdf->SetFillColor(170, 255, 255);
        $pdf->Cell(234, 4, 'FORMULIR', 1, 1, 'C', 1);

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(43, 5, '', 0, 0, 'L');
        $pdf->Cell(234, 5, 'DEPARTMEN CELUP CONES', 0, 1, 'C');

        $pdf->SetFont('Arial', '', 5);
        $pdf->Cell(43, 4, 'PT KAHATEX', 0, 0, 'C');
        $pdf->Cell(234, 4, 'FORMULIR PO', 0, 1, 'C');

        $pdf->SetFont('Arial', '', 5);
        $pdf->Cell(43, 4, 'No. Dokumen', 1, 0, 'L');
        $pdf->Cell(162, 4, 'FOR-CC-087/REV_01/HAL_1/1', 1, 0, 'L');
        $pdf->Cell(31, 4, 'Tanggal Revisi', 1, 0, 'L');
        $pdf->Cell(41, 4, '04 Desember 2019', 1, 1, 'L');

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(43, 5, 'PO', 0, 0, 'L');
        $pdf->Cell(234, 5, ': ', 0, 1, 'L');
        $pdf->Cell(43, 5, 'Pemesanan', 0, 0, 'L');
        $pdf->Cell(234, 5, ': COVERING', 0, 1, 'L');
        $pdf->Cell(43, 5, 'Tgl', 0, 0, 'L');
        if (!empty($tgl_po)) {
            $pdf->Cell(234, 5, ': ' . $tgl_po, 0, 1, 'L');
        } else {
            $pdf->Cell(234, 5, ': No delivery date available', 0, 1, 'L');
        }

        // Tabel Header Baris Pertama
        $pdf->SetFont('Arial', '', 9);
        // Merge cells untuk kolom No, Bentuk Celup, Warna, Kode Warna, Buyer, Nomor Order, Delivery, Untuk Produksi, Contoh Warna, Keterangan Celup
        $pdf->Cell(6, 16, 'No', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(37, 8, 'Benang', 1, 0, 'C'); // Merge 2 kolom ke samping untuk baris pertama
        $pdf->MultiCell(17, 8, 'Bentuk Celup', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 16);
        $pdf->Cell(60, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(20, 16, 'Warna', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(20, 16, 'Kode Warna', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(10, 16, 'Buyer', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(25, 16, 'Nomor Order', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(16, 16, 'Delivery', 1, 0, 'C'); // Merge 2 baris
        $pdf->MultiCell(15, 4, 'Qty Pesanan', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 8);
        $pdf->Cell(166, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(52, 8, 'Permintaan Kelos', 1, 0, 'C'); // Merge 4 kolom
        $pdf->MultiCell(18, 8, 'Untuk Produksi', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 16);
        $pdf->Cell(236, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->MultiCell(18, 8, 'Contoh Warna', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 16);
        $pdf->Cell(254, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->MultiCell(23, 8, 'Keterangan Celup', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 16);
        $pdf->Cell(277, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(23, 16, '', 0, 1, 'C'); // Merge 2 baris

        // Sub-header untuk kolom "Benang" dan "Permintaan Kelos"
        $pdf->Cell(6, -8, '', 0, 0); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(12, -8, 'Jenis', 1, 0, 'C');
        $pdf->Cell(25, -8, 'Kode', 1, 0, 'C');
        $pdf->Cell(108, -8, '', 0, 0); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(15, -8, 'Kg', 1, 0, 'C'); // Merge 4 kolom untuk Permintaan Kelos
        $pdf->Cell(13, -8, 'Kg', 1, 0, 'C');
        $pdf->Cell(13, -8, 'Yard', 1, 0, 'C');
        $pdf->MultiCell(13, -4, 'Cones Total', 1, 'C', false);
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 8);
        $pdf->Cell(205, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->MultiCell(13, -4, 'Cones Jenis', 1, 'C', false);
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 8);
        $pdf->Cell(218, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(87, -8, '', 0, 2, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(87, 8, '', 0, 1, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->SetFont('Arial', '', 7);
    }

    public function generateOpenPOCovering($tgl_po)
    {
        $poCovering = $this->openPoModel->getPoForCelup($tgl_po);
        $idPO = $this->openPoModel->getDeliveryAwalNoOrderBuyer($tgl_po);

        //Cek PO
        if (empty($poCovering) || empty($poCovering[0]['no_model'])) {
            session()->setFlashdata('error', 'PO Tidak Ditemukan. Open PO Terlebih Dahulu');
            return redirect()->back();
        }

        //Hapus Kata POCOVERING
        foreach ($idPO as $key => $row) {
            $idPO[$key]['no_model'] = preg_replace('/POCOVERING\s*/i', '', $row['no_model']);
        }

        // Inisialisasi FPDF
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();

        // Tambahkan border margin
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.2);
        $pdf->Rect(10, 10, 277, 190);

        // Garis margin luar (lebih tebal)
        $pdf->SetDrawColor(0, 0, 0); // Warna hitam
        $pdf->SetLineWidth(0.4); // Lebih tebal
        $pdf->Rect(9, 9, 279, 192); // Sedikit lebih besar dari margin dalam

        // Garis margin dalam (lebih tipis)
        $pdf->SetLineWidth(0.2); // Lebih tipis
        $pdf->Rect(10, 10, 277, 190); // Ukuran aslinya

        // Masukkan gambar di dalam kolom
        $x = $pdf->GetX(); // Simpan posisi X saat ini
        $y = $pdf->GetY(); // Simpan posisi Y saat ini

        // Menambahkan gambar
        $pdf->Image('assets/img/logo-kahatex.png', $x + 16, $y + 1, 10, 8); // Lokasi X, Y, lebar, tinggi

        // Header
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(43, 13, '', 1, 0, 'C'); // Tetap di baris yang sama
        // Set warna latar belakang menjadi biru telur asin (RGB: 170, 255, 255)
        $pdf->SetFillColor(170, 255, 255);
        $pdf->Cell(234, 4, 'FORMULIR', 1, 1, 'C', 1); // Pindah ke baris berikutnya setelah ini

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(43, 5, '', 0, 0, 'L'); // Tetap di baris yang sama
        $pdf->Cell(234, 5, 'DEPARTMEN CELUP CONES', 0, 1, 'C'); // Pindah ke baris berikutnya setelah ini

        $pdf->SetFont('Arial', '', 5);
        $pdf->Cell(43, 4, 'PT KAHATEX', 0, 0, 'C'); // Tetap di baris yang sama
        $pdf->Cell(234, 4, 'FORMULIR PO', 0, 1, 'C'); // Pindah ke baris berikutnya setelah ini


        // Tabel Header Atas
        $pdf->SetFont('Arial', '', 5);
        $pdf->Cell(43, 4, 'No. Dokumen', 1, 0, 'L');
        $pdf->Cell(162, 4, 'FOR-CC-087/REV_01/HAL_1/1', 1, 0, 'L');
        $pdf->Cell(31, 4, 'Tanggal Revisi', 1, 0, 'L');
        $pdf->Cell(41, 4, '04 Desember 2019', 1, 1, 'L');

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(43, 5, 'PO', 0, 0, 'L');
        $pdf->Cell(234, 5, ': ' . $poCovering[0]['no_model'], 0, 1, 'L');

        $pdf->Cell(43, 5, 'Pemesanan', 0, 0, 'L');
        $pdf->Cell(234, 5, ': COVERING', 0, 1, 'L');

        $pdf->Cell(43, 5, 'Tgl', 0, 0, 'L');
        // Check if the result array is not empty and display only the first delivery_awal
        if (!empty($poCovering)) {
            $pdf->Cell(234, 5, ': ' . date('Y-m-d', strtotime($poCovering[0]['created_at'])), 0, 1, 'L');
        } else {
            $pdf->Cell(234, 5, ': No delivery date available', 0, 1, 'L');
        }

        // Tabel Header Baris Pertama
        $pdf->SetFont('Arial', '', 9);
        // Merge cells untuk kolom No, Bentuk Celup, Warna, Kode Warna, Buyer, Nomor Order, Delivery, Untuk Produksi, Contoh Warna, Keterangan Celup
        $pdf->Cell(6, 16, 'No', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(37, 8, 'Benang', 1, 0, 'C'); // Merge 2 kolom ke samping untuk baris pertama
        $pdf->MultiCell(17, 8, 'Bentuk Celup', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 16);
        $pdf->Cell(60, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(20, 16, 'Warna', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(20, 16, 'Kode Warna', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(10, 16, 'Buyer', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(25, 16, 'Nomor Order', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(16, 16, 'Delivery', 1, 0, 'C'); // Merge 2 baris
        $pdf->MultiCell(15, 4, 'Qty Pesanan', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 8);
        $pdf->Cell(166, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(52, 8, 'Permintaan Kelos', 1, 0, 'C'); // Merge 4 kolom
        $pdf->MultiCell(18, 8, 'Untuk Produksi', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 16);
        $pdf->Cell(236, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->MultiCell(18, 8, 'Contoh Warna', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 16);
        $pdf->Cell(254, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->MultiCell(23, 8, 'Keterangan Celup', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 16);
        $pdf->Cell(277, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(23, 16, '', 0, 1, 'C'); // Merge 2 baris

        // Sub-header untuk kolom "Benang" dan "Permintaan Kelos"
        $pdf->Cell(6, -8, '', 0, 0); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(12, -8, 'Jenis', 1, 0, 'C');
        $pdf->Cell(25, -8, 'Kode', 1, 0, 'C');
        $pdf->Cell(108, -8, '', 0, 0); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(15, -8, 'Kg', 1, 0, 'C'); // Merge 4 kolom untuk Permintaan Kelos
        $pdf->Cell(13, -8, 'Kg', 1, 0, 'C');
        $pdf->Cell(13, -8, 'Yard', 1, 0, 'C');
        $pdf->MultiCell(13, -4, 'Cones Total', 1, 'C', false);
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 8);
        $pdf->Cell(205, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->MultiCell(13, -4, 'Cones Jenis', 1, 'C', false);
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 8);
        $pdf->Cell(218, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(87, -8, '', 0, 2, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(87, 8, '', 0, 1, 'C'); // Kosong untuk menyesuaikan posisi

        $pdf->SetFont('Arial', '', 7);
        $no = 1;
        $maxHeight = 8; // Default tinggi baris
        $yLimit = 180;

        $poMapping = [];
        foreach ($idPO as $po) {
            if (empty($po['id_induk'])) { // Hanya ambil data yang tidak punya id_induk
                $poMapping[$po['id_po']] = [
                    'no_model' => $po['no_model'],
                    'buyer' => $po['buyer'],
                    'delivery_awal' => $po['delivery_awal']
                ];
            }
        }

        foreach ($poCovering as $index => $row) {
            // Ambil data dari poMapping, kalau ada
            $poData = isset($poMapping[$index]) ? $poMapping[$index] : ['no_model' => '-', 'buyer' => '-', 'delivery_awal' => '-'];

            if ($pdf->GetY() + $maxHeight > $yLimit) {
                $pdf->AddPage(); // Tambah halaman baru
                // Ulangi Header Formulir
                $this->generateHeaderPOCovering($pdf, $tgl_po);
            }

            $pdf->Cell(6, 8, $no++, 1, 0, 'C'); // Align center
            $pdf->Cell(12, 8, $row['jenis'], 1, 0, 'C'); // Align center

            //Wrap text jika melebihi space
            $itemTypeWidth = 25; // Lebar kolom item_type
            $lineHeight = 4; // Tinggi per baris untuk MultiCell
            $textWidth = $pdf->GetStringWidth($row['item_type']); // Panjang teks

            if ($textWidth > $itemTypeWidth) {
                $pdf->MultiCell($itemTypeWidth, $lineHeight, $row['item_type'], 1, 'C', false);
                $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 8);
                $pdf->Cell(43, -8, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
            } else {
                $adjustedHeight = 8; // Tinggi standar jika cukup 1 baris
                $pdf->Cell($itemTypeWidth, $adjustedHeight, $row['item_type'], 1, 0, 'C');
            }

            // Gunakan data dari id_po asli
            $id_po_asli = empty($row['id_induk']) ? $row['id_po'] : $row['id_induk'];

            if (isset($poMapping[$id_po_asli])) {
                $poData = $poMapping[$id_po_asli];
            } else {
                $poData = ['no_model' => '-', 'buyer' => '-', 'delivery_awal' => '-'];
            }

            // Lanjutkan dengan sel lainnya yang juga menyesuaikan tinggi
            $pdf->Cell(17, $maxHeight, '', 1, 0, 'C'); // Bentuk Celup
            $pdf->Cell(20, $maxHeight, $row['color'], 1, 0, 'C');
            $pdf->Cell(20, $maxHeight, $row['kode_warna'], 1, 0, 'C');
            $pdf->Cell(10, $maxHeight, $poData['buyer'], 1, 0, 'C');
            $pdf->Cell(25, $maxHeight, $poData['no_model'], 1, 0, 'C');
            $pdf->Cell(16, $maxHeight, $poData['delivery_awal'], 1, 0, 'C');
            $pdf->Cell(15, $maxHeight, $row['kg_po'], 1, 0, 'C');
            $pdf->Cell(13, $maxHeight, '', 1, 0, 'C');
            $pdf->Cell(13, $maxHeight, '', 1, 0, 'C');
            $pdf->Cell(13, $maxHeight, '', 1, 0, 'C');
            $pdf->Cell(13, $maxHeight, '', 1, 0, 'C');
            $pdf->Cell(18, $maxHeight, $row['jenis'], 1, 0, 'C');
            $pdf->Cell(18, $maxHeight, '', 1, 0, 'C');
            $pdf->Cell(23, $maxHeight, '', 1, 1, 'C');
        }
        //KETERANGAN
        $pdf->Cell(277, 5, '', 0, 1, 'C');

        $pdf->Cell(277, 5, '', 0, 1, 'C');

        $pdf->Cell(55, 5, '', 0, 0, 'C');
        $pdf->Cell(55, 5, 'Pemesan', 0, 0, 'C');
        $pdf->Cell(55, 5, 'Mengetahui', 0, 0, 'C');
        $pdf->Cell(55, 5, 'Tanda Terima ' . 'Celup Cones', 0, 1, 'C');

        $pdf->Cell(55, 9, '', 0, 1, 'C');

        $pdf->Cell(55, 5, '', 0, 0, 'C');
        $pdf->Cell(55, 5, '(                               )', 0, 0, 'C');
        if (!empty($poCovering)) {
            $pdf->Cell(55, 5, $poCovering[0]['penanggung_jawab'], 0, 0, 'C');
        } else {
            $pdf->Cell(234, 5, ': No penanggung_jawab available', 0, 0, 'C');
        }
        $pdf->Cell(55, 5, '(       ' . $poCovering[0]['penerima'] . '       )', 0, 1, 'C');

        // Output PDF
        return $this->response->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('S'));
    }


    public function exportOpenPOGabung()
    {
        $tujuan = $this->request->getGet('tujuan');
        $jenis = $this->request->getGet('jenis');
        $jenis2 = $this->request->getGet('jenis2');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        // dd($tujuan, $jenis, $jenis2, $startDate, $endDate);

        // Tentukan penerima berdasarkan tujuan
        if ($tujuan == 'CELUP') {
            $penerima = 'Retno';
        } elseif ($tujuan == 'COVERING') {
            $penerima = 'Paryanti';
        } else {
            return redirect()->back()->with('error', 'Tujuan tidak valid.');
        }

        $buyer = [];
        $openPoGabung = $this->openPoModel->listOpenPoGabungbyDate($jenis, $jenis2, $penerima, $startDate, $endDate);
        // dd ($openPoGabung);
        foreach ($openPoGabung as &$po) {
            $buyersData = $this->openPoModel->getBuyer($po['id_po']); // Ambil semua data buyer terkait
            if (is_array($buyersData) && count($buyersData) > 0) {
                // Ambil semua buyer, no_order, dan delivery_awal
                $buyers = array_column($buyersData, 'buyer');
                $noOrders = array_column($buyersData, 'no_order');
                $deliveries = array_column($buyersData, 'delivery_awal');

                // Tentukan buyer: kosong jika lebih dari satu jenis
                $po['buyer'] = count(array_unique($buyers)) === 1 ? $buyers[0] : null;

                // Tentukan delivery_awal paling awal
                $earliestDeliveryIndex = array_keys($deliveries, min($deliveries))[0];
                $po['delivery_awal'] = $deliveries[$earliestDeliveryIndex];

                // Tentukan no_order yang berhubungan dengan delivery_awal paling awal
                $po['no_order'] = $noOrders[$earliestDeliveryIndex];
            } else {
                // Jika tidak ada data buyersData
                $po['buyer'] = null;
                $po['no_order'] = null;
                $po['delivery_awal'] = null;
            }
        }
        // Pastikan untuk tidak menggunakan referensi lagi setelah loop selesai
        unset($po);

        // Inisialisasi FPDF
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->SetAutoPageBreak(true, 5); // Atur margin bawah saat halaman penuh
        $pdf->AddPage();

        // Garis margin luar (lebih tebal)
        $pdf->SetDrawColor(0, 0, 0); // Warna hitam
        $pdf->SetLineWidth(0.4); // Lebih tebal
        $pdf->Rect(9, 9, 279, 192); // Sedikit lebih besar dari margin dalam

        // Garis margin dalam (lebih tipis)
        $pdf->SetLineWidth(0.2); // Lebih tipis
        $pdf->Rect(10, 10, 277, 190); // Ukuran aslinya

        // Masukkan gambar di dalam kolom
        $x = $pdf->GetX(); // Simpan posisi X saat ini
        $y = $pdf->GetY(); // Simpan posisi Y saat ini

        // Menambahkan gambar
        $pdf->Image('assets/img/logo-kahatex.png', $x + 16, $y + 1, 10, 8); // Lokasi X, Y, lebar, tinggi

        // Header
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(43, 13, '', 1, 0, 'C'); // Tetap di baris yang sama
        // Set warna latar belakang menjadi biru telur asin (RGB: 170, 255, 255)
        $pdf->SetFillColor(170, 255, 255);
        $pdf->Cell(234, 4, 'FORMULIR', 'LTR', 1, 'C', 1); // Pindah ke baris berikutnya setelah ini

        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(43, 5, '', 0, 0, 'L'); // Tetap di baris yang sama
        $pdf->Cell(234, 5, 'DEPARTMEN CELUP CONES', 0, 1, 'C'); // Pindah ke baris berikutnya setelah ini

        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(43, 4, 'PT. KAHATEX', 0, 0, 'C'); // Tetap di baris yang sama
        $pdf->Cell(234, 4, 'FORMULIR PO', 0, 1, 'C'); // Pindah ke baris berikutnya setelah ini


        // Tabel Header Atas
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(43, 4, 'No. Dokumen', 1, 0, 'L');
        $pdf->Cell(162, 4, 'FOR-CC-087/REV_02/HAL_1/1', 1, 0, 'L');
        $pdf->Cell(31, 4, 'Tanggal Revisi', 1, 0, 'L');
        $pdf->Cell(41, 4, '04 Desember 2019', 1, 1, 'C');

        $pdf->Cell(43, 4, '', 1, 0, 'L');
        $pdf->Cell(162, 4, '', 1, 0, 'L');
        $pdf->Cell(31, 4, 'Klasifikasi', 1, 0, 'L');
        $pdf->Cell(41, 4, 'Internal', 1, 1, 'C');


        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(43, 4, 'PO', 0, 0, 'L');
        $pdf->Cell(234, 4, ':', 0, 1, 'L');

        $pdf->Cell(43, 4, 'Pemesanan', 0, 0, 'L');
        $pdf->Cell(234, 4, ': KAOS KAKI', 0, 1, 'L');

        $pdf->Cell(43, 4, 'Tgl', 0, 0, 'L');
        // Check if the result array is not empty and display only the first delivery_awal
        if (!empty($openPoGabung)) {
            $pdf->Cell(234, 4, ': ' . date('d-m-Y', strtotime($openPoGabung[0]['tgl_po'])), 0, 1, 'L');
        } else {
            $pdf->Cell(234, 4, ': No delivery date available', 0, 1, 'L');
        }

        // Tabel Header Baris Pertama
        $pdf->SetFont('Arial', 'B', 7);
        // Merge cells untuk kolom No, Bentuk Celup, Warna, Kode Warna, Buyer, Nomor Order, Delivery, Untuk Produksi, Contoh Warna, Keterangan Celup
        $pdf->Cell(6, 14, 'No', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(38, 7, 'Benang', 1, 0, 'C'); // Merge 2 kolom ke samping untuk baris pertama
        $pdf->MultiCell(12, 7, 'Bentuk Celup', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 14);
        $pdf->Cell(56, -7, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(20, 14, 'Warna', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(18, 14, 'Kode Warna', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(12, 14, 'Buyer', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(25, 14, 'Nomor Order', 1, 0, 'C'); // Merge 2 baris
        $pdf->Cell(15, 14, 'Delivery', 1, 0, 'C'); // Merge 2 baris
        $pdf->MultiCell(13, 3.5, 'Qty Pesanan', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 7);
        $pdf->Cell(159, -7, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(43, 7, 'Permintaan Kelos', 1, 0, 'C'); // Merge 4 kolom
        $pdf->MultiCell(15, 7, 'Untuk Produksi', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 14);
        $pdf->Cell(217, -7, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->MultiCell(12, 7, 'Contoh Warna', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 14);
        $pdf->Cell(229, -7, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->MultiCell(48, 14, 'Keterangan Celup', 1, 'C', false); // Merge 2 baris
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 14);
        $pdf->Cell(277, -7, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(23, 14, '', 0, 1, 'C'); // Merge 2 baris

        // Sub-header untuk kolom "Benang" dan "Permintaan Kelos"
        $pdf->Cell(6, -7, '', 0, 0); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(26, -7, 'Jenis', 1, 0, 'C');
        $pdf->Cell(12, -7, 'Kode', 1, 0, 'C');
        $pdf->Cell(102, -7, '', 0, 0); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(13, -7, 'Kg', 1, 0, 'C'); // Merge 4 kolom untuk Permintaan Kelos
        $pdf->Cell(8, -7, 'Kg', 1, 0, 'C');
        $pdf->Cell(10, -7, 'Yard', 1, 0, 'C');
        $pdf->MultiCell(12, -3.5, 'Cones Total', 1, 'C', false);
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 7);
        $pdf->Cell(189, -7, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->MultiCell(13, -3.5, 'Cones Jenis', 1, 'C', false);
        $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 8);
        $pdf->Cell(218, -7, '', 0, 0, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(87, -7, '', 0, 2, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(87, 7, '', 0, 1, 'C'); // Kosong untuk menyesuaikan posisi
        $pdf->Cell(23, -1, '', 0, 1, 'C'); // Merge 2 baris

        $no = 1;
        // Inisialisasi variabel total
        $totalKgPo = 0;
        $totalCones = 0;
        foreach ($openPoGabung as $po) {
            $pdf->SetFont('Arial', '', 6);

            // Posisi awal baris
            $yStart = $pdf->GetY();

            // Hitung tinggi maksimum dalam satu baris
            $rowHeight = 4; // Tinggi default
            $heights = [];

            // hitung jumlah baris per kolom
            $heights = [
                'item_type'      => ceil($pdf->GetStringWidth($po['item_type'] . ' (' . $po['spesifikasi_benang'] . ')') / 26) * $rowHeight,
                'ukuran'         => ceil($pdf->GetStringWidth($po['ukuran']) / 12) * $rowHeight,
                'bentuk_celup'   => ceil($pdf->GetStringWidth($po['bentuk_celup']) / 12) * $rowHeight,
                'buyer'          => ceil($pdf->GetStringWidth($po['buyer']) / 10) * $rowHeight,
                'color'          => ceil($pdf->GetStringWidth($po['color']) / 20) * $rowHeight,
                'kode_warna'     => ceil($pdf->GetStringWidth($po['kode_warna']) / 20) * $rowHeight,
                'no_order'       => ceil($pdf->GetStringWidth($po['no_order']) / 25) * $rowHeight,
                'jenis_produksi' => ceil($pdf->GetStringWidth($po['jenis_produksi']) / 15) * $rowHeight,
                'ket_celup'      => ceil($pdf->GetStringWidth($po['ket_celup']) / 48) * $rowHeight,
            ];

            $rowHeight = max($heights);

            // Tulis data dengan MultiCell untuk kolom yang membutuhkan wrap text
            $pdf->Cell(6, $rowHeight, $no++, 1, 0, 'C'); // No
            $xNow = $pdf->GetX();
            $rowItem = $heights['item_type'] / 4 > 1 ? 4 : $rowHeight;
            $pdf->MultiCell(26, $rowItem, $po['item_type'] . ' (' . $po['spesifikasi_benang'] . ')', 1, 'C'); // Jenis
            $pdf->SetXY($xNow + 26, $yStart);

            $xNow = $pdf->GetX();
            $rowUkuran = $heights['ukuran'] / 4 > 1 ?  4 : $rowHeight;
            $pdf->MultiCell(12, $rowUkuran, $po['ukuran'], 1, 'C'); // Kode
            $pdf->SetXY($xNow + 12, $yStart);

            $xNow = $pdf->GetX();
            $rowBc = $heights['bentuk_celup'] / 4 > 1 ?  4 : $rowHeight;
            $pdf->MultiCell(12, $rowBc, $po['bentuk_celup'], 1, 'C'); // Bentuk Celup
            $pdf->SetXY($xNow + 12, $yStart);

            $xNow = $pdf->GetX();
            $rowColor = $heights['color'] / 4 > 1 ?  4 : $rowHeight;
            $pdf->MultiCell(20, $rowColor, $po['color'], 1, 'C'); // Warna
            $pdf->SetXY($xNow + 20, $yStart);

            $xNow = $pdf->GetX();
            $rowKode = $heights['kode_warna'] / 4 > 1 ?  4 : $rowHeight;
            $pdf->MultiCell(18, $rowKode, $po['kode_warna'], 1, 'C'); // Kode Warna
            $pdf->SetXY($xNow + 18, $yStart);

            $pdf->SetFont('Arial', '', 5);
            $pdf->Cell(12, $rowHeight, $po['buyer'], 1, 0, 'C'); // Buyer

            $xNow = $pdf->GetX();
            $rowNoOrder = $heights['no_order'] / 4 > 1 ?  4 : $rowHeight;
            $pdf->MultiCell(25, $rowNoOrder, $po['no_order'], 1, 'C'); // Nomor Order
            $pdf->SetXY($xNow + 25, $yStart);

            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(15, $rowHeight, $po['delivery_awal'], 1, 0, 'C'); // Delivery
            $pdf->Cell(13, $rowHeight, number_format($po['kg_po'], 2), 1, 0, 'C'); // Qty Pesanan (Kg)
            $pdf->Cell(8, $rowHeight, $po['kg_percones'], 1, 0, 'C'); // Kg Per Cones
            $pdf->Cell(10, $rowHeight, '', 1, 0, 'C'); // Yard
            $pdf->Cell(12, $rowHeight, $po['jumlah_cones'], 1, 0, 'C'); // Cones Total
            $pdf->Cell(13, $rowHeight, '', 1, 0, 'C'); // Cones Jenis

            $xNow = $pdf->GetX();
            $rowJp = $heights['jenis_produksi'] / 4 > 1 ?  4 : $rowHeight;
            $pdf->MultiCell(15, $rowJp, $po['jenis_produksi'], 1, 'C'); // Untuk Produksi
            $pdf->SetXY($xNow + 15, $yStart);

            $xNow = $pdf->GetX();
            $pdf->MultiCell(12, $rowHeight, '', 1, 'C'); // Contoh Warna
            $pdf->SetXY($xNow + 12, $yStart);

            $xNow = $pdf->GetX();
            $rowKc = $heights['ket_celup'] / 4 > 1 ?  4 : $rowHeight;
            $pdf->MultiCell(48, $rowKc, $po['ket_celup'], 1, 'C'); // Keterangan Celup
            $pdf->SetXY($xNow + 48, $yStart);

            $pdf->Ln($rowHeight); // Pindah ke baris berikutnya

            // Tambahkan nilai ke total
            $totalKgPo += $po['kg_po'];
            $totalCones += $po['jumlah_cones'];
        }

        // Tambahkan baris total
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(146, 5, 'Total', 1, 0, 'R'); // Gabungkan sel sebelum kolom "Qty Pemesanan"
        $pdf->Cell(13, 5, number_format($totalKgPo, 2), 1, 0, 'C'); // Total Qty Pemesanan (kg)
        $pdf->Cell(8, 5, '', 1, 0, 'C'); // Kosong untuk "Kg Per Cones" dan lainnya
        $pdf->Cell(10, 5, '', 1, 0, 'C'); // Kosong untuk "Kg Per Cones" dan lainnya
        $pdf->Cell(12, 5, $totalCones == 0 ? '' : $totalCones, 1, 0, 'C'); // Total Cones
        $pdf->Cell(13, 5, '', 1, 0, 'C'); // Kosong untuk "Kg Per Cones" dan lainnya
        $pdf->Cell(15, 5, '', 1, 0, 'C'); // Kosong untuk "Kg Per Cones" dan lainnya
        $pdf->Cell(12, 5, '', 1, 0, 'C'); // Kosong untuk "Kg Per Cones" dan lainnya
        $pdf->Cell(48, 5, '', 1, 0, 'C'); // Kosong untuk "Kg Per Cones" dan lainnya

        // KETERANGAN
        $pdf->Cell(277, 5, '', 0, 1, 'C');
        $pdf->Cell(85, 5, 'KET', 0, 0, 'R');
        // Check if the result array is not empty and display only the first delivery_awal
        if (!empty($openPoGabung)) {
            $pdf->Cell(117, 5, ': ' . $openPoGabung[0]['keterangan'], 0, 1, 'L');
        } else {
            $pdf->Cell(117, 5, ': ', 0, 1, 'L');
        }

        $pdf->Cell(277, 5, '', 0, 1, 'C');
        $pdf->Cell(277, 5, '', 0, 1, 'C');
        $pdf->Cell(277, 5, '', 0, 1, 'C');
        $pdf->Cell(277, 5, '', 0, 1, 'C');
        // $pdf->Cell(170, 5, 'UNTUK DEPARTMEN ' . $tujuan, 0, 1, 'C');

        $pdf->Cell(55, 5, '', 0, 0, 'C');
        $pdf->Cell(55, 5, 'Pemesanan', 0, 0, 'C');
        $pdf->Cell(55, 5, 'Mengetahui', 0, 0, 'C');
        $pdf->Cell(55, 5, 'Tanda Terima ' . $tujuan, 0, 1, 'C');

        $pdf->Cell(55, 15, '', 0, 1, 'C');

        $pdf->Cell(55, 5, '', 0, 0, 'C');
        $pdf->Cell(55, 5, '(                               )', 0, 0, 'C');
        if (!empty($openPoGabung)) {
            $pdf->Cell(55, 5, '(       ' . $openPoGabung[0]['penanggung_jawab'] . '      )', 0, 0, 'C');
        } else {
            $pdf->Cell(234, 5, ': No penanggung_jawab available', 0, 0, 'C');
        }
        $pdf->Cell(55, 5, '(       ' . $penerima . '       )', 0, 1, 'C');

        // Output PDF
        return $this->response->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('PO Gabungan.pdf', 'I'));
    }

    public function generatePemesananSpandexKaretCovering($jenis, $tgl_po)
    {
        // Ambil data dari model
        $data = $this->pemesananSpandexKaretModel->getDataForPdf($jenis, $tgl_po);
        // dd ($data);
        // Inisialisasi FPDF (portrait A4)
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();

        // Garis tepi luar (margin 10mm → konten 190×277)
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.4);
        $pdf->Rect(9, 9, 192, 132);    // sedikit lebih besar untuk border luar
        $pdf->SetLineWidth(0.2);
        $pdf->Rect(10, 10, 190, 130);  // border dalam

        // Logo
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Image('assets/img/logo-kahatex.png', $x + 16, $y + 1, 10, 8);

        // Header
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(43, 13, '', 1, 0, 'C');
        $pdf->SetFillColor(170, 255, 255);
        $pdf->Cell(147, 4, 'FORMULIR', 1, 1, 'C', 1);

        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(43, 5, '', 0, 0, 'L');
        $pdf->Cell(147, 5, 'DEPARTEMEN COVERING', 0, 1, 'C');

        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(43, 4, 'PT KAHATEX', 0, 0, 'C');
        $pdf->Cell(147, 4, 'SURAT PENGELUARAN BARANG', 0, 1, 'C');

        // Tabel Header Atas (total lebar 190)
        $pdf->SetFont('Arial', '', 5);
        $pdf->Cell(43, 4, 'No. Dokumen', 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 5);
        $pdf->Cell(60, 4, 'FOR-COV-631', 1, 0, 'L');
        $pdf->SetFont('Arial', '', 5);
        $pdf->Cell(24, 4, 'Halaman', 1, 0, 'L');
        $pdf->Cell(63, 4, '1 dari 1', 1, 1, 'C');

        // Tanggal
        $pdf->Cell(43, 4, 'Tanggal Efektif', 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 5);
        $pdf->Cell(60, 4, '01 Mei 2017', 1, 0, 'L');
        $pdf->SetFont('Arial', '', 5);
        $pdf->Cell(24, 4, 'Revisi', 1, 0, 'L');
        $pdf->Cell(63, 4, '00', 1, 1, 'C');

        // kosongkan sel
        $pdf->Cell(103, 4, '', 1, 0, 'L');
        $pdf->Cell(24, 4, 'Tanggal Revisi', 1, 0, 'L');
        $pdf->Cell(63, 4, '', 1, 1, 'C');

        // garis double
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Line(10, 36, 200, 36); // Garis horizontal
        $pdf->Cell(0, 1, '', 0, 1); // Pindah ke baris berikutnya

        // customer
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(103, 8, 'CUSTOMER: ', 0, 0, 'L', false); // Tinggi cell diatur menjadi 8 agar teks berada di tengah
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(24, 4, 'NO: ', 0, 0, 'L', false); // Tinggi cell diatur menjadi 8 agar teks berada di tengah
        $pdf->Cell(63, 4, '', 0, 1, 'C', false); // Tinggi cell diatur menjadi 8 agar teks berada di tengah
        $pdf->Cell(103, 4, '', 0, 0, 'L', false); // Tinggi cell diatur menjadi 8 agar teks berada di tengah
        $pdf->Cell(24, 4, 'TANGGAL: ', 0, 0, 'L', false); // Tinggi cell diatur menjadi 8 agar teks berada di tengah
        $pdf->Cell(63, 4, '', 0, 1, 'L', false); // Tinggi cell diatur menjadi 8 agar teks berada di tengah

        // Tabel Header Baris Pertama
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(10, 8,  'No',           1, 0, 'C');
        $pdf->Cell(55, 8,  'JENIS BARANG', 1, 0, 'C');
        $pdf->Cell(15, 8,  'DR/TPM',       1, 0, 'C');
        $pdf->Cell(30, 8,  'WARNA/CODE',   1, 0, 'C');
        $pdf->Cell(20, 4,  'JUMLAH',       1, 0, 'C');
        // Keterangan merge dua baris (8 + 4 mm = 12 mm)
        $pdf->Cell(60, 8, 'KETERANGAN',   1, 1, 'C');

        // Baris Kedua: hanya untuk sub‐kolom JUMLAH (KG + CONES)
        $pdf->SetX(10 /* left margin */ + 10 + 55 + 15 + 30); // pos X setelah No+Jenis+DRTPM+Warna
        $pdf->Cell(10, -4, 'KG',    1, 0, 'C');
        $pdf->Cell(10, -4, 'CONES', 1, 0, 'C');
        $pdf->Ln();  // turun ke baris data
        $pdf->Cell(190, 4, '', 0, 1, 'C'); // Kosongkan sel untuk No
        // foreach data.
        $urut = 18; // untuk mengatur posisi Y dari baris ke 2
        $no = 1;
        if (count($data) > 0) {
            foreach ($data as $row) {
                $pdf->Cell(10, 4,  $no++,           1, 0, 'C');
                $pdf->Cell(55, 4,  $row['item_type'], 1, 0, 'C');
                $pdf->Cell(15, 4,  "",        1, 0, 'C');
                $pdf->Cell(30, 4,  $row['color'],    1, 0, 'C');
                $pdf->Cell(10, 4,  number_format($row['total_pesan'], 2),   1, 0, 'C');
                $pdf->Cell(10, 4,  $row['total_cones'],   1, 0, 'C');
                // Keterangan merge dua baris (8 + 4 mm = 12 mm)
                $pdf->Cell(60, 4, '',   1, 1, 'C');
            }
            if ($no < $urut) {
                // Jika tidak ada data yang ditemukan
                for ($i = $no; $i < $urut; $i++) {
                    $pdf->Cell(10, 4,  $no++, 1, 0, 'C');
                    $pdf->Cell(55, 4,  '', 1, 0, 'C');
                    $pdf->Cell(15, 4,  '',        1, 0, 'C');
                    $pdf->Cell(30, 4,  '',    1, 0, 'C');
                    $pdf->Cell(10, 4,  '',   1, 0, 'C');
                    $pdf->Cell(10, 4,  '',   1, 0, 'C');
                    // Keterangan merge dua baris (8 + 4 mm = 12 mm)
                    $pdf->Cell(60, 4, '',   1, 1, 'C');
                }
            }
        } else {
            // Jika ada data yang ditemukan, tetapi kurang dari 5 baris
            for ($i = 0; $i < $urut; $i++) {
                $pdf->Cell(10, 4,  '',           1, 0, 'C');
                $pdf->Cell(55, 4,  '', 1, 0, 'C');
                $pdf->Cell(15, 4,  '',        1, 0, 'C');
                $pdf->Cell(30, 4,  '',    1, 0, 'C');
                $pdf->Cell(10, 4,  '',   1, 0, 'C');
                $pdf->Cell(10, 4,  '',   1, 0, 'C');
                // Keterangan merge dua baris (8 + 4 mm = 12 mm)
                $pdf->Cell(60, 4, '',   1, 1, 'C');
            }
        }

        // tanda tangan
        // $pdf->Cell(277, 5, '', 0, 1, 'C');
        $pdf->Cell(63, 5, 'YANG BUKA BON', 0, 0, 'C');
        $pdf->Cell(64, 5, 'GUDANG ANGKUTAN', 0, 0, 'C');
        $pdf->Cell(63, 5, 'PENERIMA', 0, 1, 'C');
        $pdf->Cell(190, 5, '', 0, 1, 'C');
        $pdf->Cell(190, 5, '', 0, 1, 'C');
        $pdf->Cell(63, 5, '(       ' . 'PARYANTI' . '       )', 0, 0, 'C');
        $pdf->Cell(64, 5, '(                               )', 0, 0, 'C');
        $pdf->Cell(63, 5, '(       ' . 'HARTANTO' . '       )', 0, 1, 'C');
        $pdf->Cell(55, 5, '', 0, 1, 'C');
        $pdf->Cell(55, 5, '', 0, 0, 'C');
        $pdf->Cell(55, 5, '', 0, 0, 'C');
        $pdf->Cell(55, 5, '', 0, 0, 'C');
        $pdf->Cell(55, 5, '', 0, 1, 'C');
        $pdf->Cell(55, 5, '', 0, 0, 'C');






        // … di sini loop $data dan tampilkan isi tabel sesuai style-mu …

        // Output PDF
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('S'));
    }

    public function generateBarcodeRetur($tglRetur)
    {
        // 1) Ambil data
        $dataList = $this->outCelupModel->getDataReturByTgl($tglRetur);
        if (empty($dataList)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Tidak ada retur pada tanggal {$tglRetur}");
        }

        // 2) Inisialisasi PDF
        $pdf       = new FPDF('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage();
        $generator = new BarcodeGeneratorPNG();

        // 3) Konfigurasi grid
        $cols    = 3;
        $boxW    = 63;
        $boxH    = 60;
        $gapX    = 5;
        $gapY    = 5;
        $marginX = 10;
        $marginY = 15;
        $lineH   = 4;

        foreach ($dataList as $i => $dataRetur) {
            // Hitung posisi kolom & baris
            $col = $i % $cols;
            $row = floor($i / $cols);
            $x   = $marginX + $col * ($boxW + $gapX);
            $y   = $marginY + $row * ($boxH + $gapY);

            // Gambar kotak putih + border
            $pdf->SetFillColor(255);
            $pdf->Rect($x, $y, $boxW, $boxH, 'F');
            $pdf->Rect($x, $y, $boxW, $boxH);

            // 4) Generate barcode (tanpa padding, CODE-128)
            $idOut       = (string)$dataRetur['id_out_celup'];
            $barcodeData  = $generator->getBarcode($idOut, $generator::TYPE_CODE_128);

            // Simpan & tampilkan PNG
            $tmpFile = tempnam(sys_get_temp_dir(), 'bc_') . '.png';
            file_put_contents($tmpFile, $barcodeData);
            $pdf->Image($tmpFile, $x + 5, $y + 3, $boxW - 10, 12);
            @unlink($tmpFile);

            // (Opsional) Tampilkan kode asli di bawah barcode
            $pdf->SetFont('Arial', '', 6);
            $pdf->SetXY($x + 5, $y + 16);
            // $pdf->Cell($boxW - 10, 4, $code, 0, 1, 'C'); // Menampilkan id out celup di barcode

            // 5) Tampilkan teks dengan wrapping
            $pdf->SetFont('Arial', '', 7);
            $textX  = $x + 3;
            $textY  = $y + 22;
            $textW  = $boxW - 6;  // sisakan 3mm margin kiri & kanan
            $pdf->SetXY($textX, $textY);

            $fields = [
                'Model'       => $dataRetur['no_model'],
                'Item Type'   => $dataRetur['item_type'],
                'Kode Warna'  => $dataRetur['kode_warna'],
                'Warna'       => $dataRetur['warna'],
                'Kgs Retur'   => $dataRetur['kgs_retur'],
                'Cones Retur' => $dataRetur['cns_retur'],
                'Lot Retur'   => $dataRetur['lot_retur'],
                'No Karung'   => $dataRetur['no_karung'],
            ];

            $labelWidth = 20; // Lebar tetap untuk label
            $valueWidth = $textW - $labelWidth;

            foreach ($fields as $label => $value) {
                $pdf->SetX($textX);
                $pdf->Cell($labelWidth, $lineH, $label, 0, 0); // Kolom label
                $pdf->MultiCell($valueWidth, $lineH, ': ' . $value, 0, 'L'); // Kolom isi
            }

            // 6) Jika penuh ke bawah, tambahkan halaman
            if (($y + $boxH + $gapY > 280) && ($col === $cols - 1)) {
                $pdf->AddPage();
            }
        }

        // 7) Output PDF
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader(
                'Content-Disposition',
                "inline; filename=\"Barcode_Retur_{$tglRetur}.pdf\""
            )
            ->setBody($pdf->Output('', 'S'));
    }
    public function printBarcodeOtherBon($idOtherBon)
    {
        $data = $this->otherBonModel->getDataById($idOtherBon);
        $generator = new BarcodeGeneratorPNG();

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFillColor(255, 255, 255); // Warna latar putih
        $pdf->SetTextColor(0, 0, 0);      // Warna teks hitam
        $pdf->SetDrawColor(0, 0, 0);      // Warna garis hitam
        // $pdf->Ln();  // Fungsi PageNo() untuk mendapatkan nomor halaman
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 0, 'BARCODE ' . $data[0]['no_model'], 0, 1, 'C');

        $startX_ = 2.5;
        $startY_ = 14;
        $barcodeCount = 0; // Counter untuk jumlah barcode di halaman saat ini
        $barcodeWidth = 67; // Lebar kotak barcode
        $barcodeHeight = 67; // Tinggi kotak barcode
        $jarakKolom = 2; // Jarak horizontal antar kolom
        $jarakBaris = 2; // Jarak vertikal antar baris

        foreach ($data as $barcode) {
            // Buat instance Barcode Generator
            $generate = $generator->getBarcode($barcode['id_out_celup'], $generator::TYPE_CODE_128);
            $generate = base64_encode($generate);
            // Menghitung posisi X dan Y untuk 12 barcode per halaman (3 kolom × 2 baris)
            $mod = 12;
            $baris = 4;
            // Jika sudah mencapai batas (6 barcode), tambah halaman baru
            if ($barcodeCount > 0 && $barcodeCount % $mod === 0) {
                $pdf->AddPage(); // Tambahkan halaman baru
                $startX_ = 2.5; // Reset posisi X untuk halaman baru
                $startY_ = 14; // Reset posisi Y untuk halaman baru
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 8, 'BARCODE', 0, 1, 'C');
                $barcodeCount = 0; // Reset counter per halaman
            }
            $colIndex = $barcodeCount % 3; // 3 kolom per baris
            $rowIndex = floor($barcodeCount / 3) % $baris; // 2 baris per halaman

            // Menghitung posisi untuk setiap barcode
            $startX = $startX_ + ($colIndex * ($barcodeWidth + $jarakKolom)); // Posisi horizontal
            $startY = $startY_ + ($rowIndex * ($barcodeHeight + $jarakBaris)); // Posisi vertikal

            // Menggambar kotak di sekitar detail
            $pdf->Rect($startX, $startY, 67, 67); // Kotak barcode

            // Menyimpan gambar barcode
            $imageData = base64_decode($generate);
            $tempImagePath = WRITEPATH . 'uploads/barcode_temp' . $barcodeCount . '.png'; // Path file sementara
            file_put_contents($tempImagePath, $imageData);

            // Menentukan posisi X agar gambar berada di tengah kotak secara horizontal
            $imageWidth = 40; // Lebar gambar
            $centerX = $startX + (67 - $imageWidth) / 2; // Menyesuaikan posisi
            $pdf->Image($tempImagePath, $centerX, $startY + 3, $imageWidth); // Tambahkan gambar

            unlink($tempImagePath); // Menghapus file gambar sementara

            // Menghitung berapa banyak baris yang sudah tercetak di dalam MultiCell

            // Menambahkan detail teks di dalam kotak
            $pdf->SetFont('Arial', 'B', 8);
            // Teks detail
            $pdf->SetXY($startX + 2, $startY + 20);
            $pdf->Cell(20, 3, 'No Model', 0, 0, 'L');
            $pdf->Cell(5, 3, ':', 0, 0, 'C');
            $pdf->Cell(70, 3, $barcode['no_model'], 0, 1, 'L');

            $pdf->SetXY($startX + 2, $pdf->getY());
            $pdf->Cell(20, 3, 'Item Type', 0, 0, 'L');
            $pdf->Cell(5, 3, ':', 0, 0, 'C');
            $pdf->MultiCell(39, 3, $barcode['item_type'], 0, 1, 'L');
            // Menyimpan posisi Y setelah MultiCell
            // dd($currentY, $nextY, $totalHeight, $lineCount);
            $pdf->SetXY($startX + 2, $pdf->GetY()); // Menambah jarak berdasarkan jumlah baris yang tercetak

            $pdf->SetXY($startX + 2, $pdf->GetY()); // Menambah jarak berdasarkan jumlah baris yang tercetak
            $pdf->Cell(20, 3, 'Kode Warna', 0, 0, 'L');
            $pdf->Cell(5, 3, ':', 0, 0, 'C');
            $pdf->MultiCell(39, 3, $barcode['kode_warna'], 0, 0, 'L');
            $pdf->SetXY($startX + 2, $pdf->getY()); // Menambah jarak berdasarkan jumlah baris yang tercetak

            $pdf->Cell(20, 3, 'Warna', 0, 0, 'L');
            $pdf->Cell(5, 3, ':', 0, 0, 'C');
            $pdf->MultiCell(39, 3, $barcode['warna'], 0, 0, 'L');
            $pdf->SetXY($startX + 2, $pdf->getY()); // Menambah jarak berdasarkan jumlah baris yang tercetak

            $currentY = $pdf->GetY();
            $pdf->Cell(20, 3, 'GW', 0, 0, 'L');
            $pdf->Cell(5, 3, ':', 0, 0, 'C');
            $pdf->Cell(39, 3, $barcode['gw_kirim'], 0, 0, 'L');
            $pdf->SetXY($startX + 2, $currentY + 3); // Menambah jarak berdasarkan jumlah baris yang tercetak

            $pdf->Cell(20, 3, 'NW', 0, 0, 'L');
            $pdf->Cell(5, 3, ':', 0, 0, 'C');
            $pdf->Cell(39, 3, $barcode['kgs_kirim'], 0, 0, 'L');
            $pdf->SetXY($startX + 2, $currentY + 6); // Menambah jarak berdasarkan jumlah baris yang tercetak

            $pdf->Cell(20, 3, 'Cones', 0, 0, 'L');
            $pdf->Cell(5, 3, ':', 0, 0, 'C');
            $pdf->Cell(39, 3, $barcode['cones_kirim'], 0, 0, 'L');
            $pdf->SetXY($startX + 2, $currentY + 9); // Menambah jarak berdasarkan jumlah baris yang tercetak

            $pdf->Cell(20, 3, 'Lot', 0, 0, 'L');
            $pdf->Cell(5, 3, ':', 0, 0, 'C');
            $pdf->Cell(39, 3, $barcode['lot_kirim'], 0, 0, 'L');
            $pdf->SetXY($startX + 2, $currentY + 12); // Menambah jarak berdasarkan jumlah baris yang tercetak

            $pdf->Cell(20, 3, 'No Karung', 0, 0, 'L');
            $pdf->Cell(5, 3, ':', 0, 0, 'C');
            $pdf->Cell(39, 3, $barcode['no_karung'], 0, 1, 'L');
            $pdf->SetXY($startX + 2, $currentY + 15); // Menambah jarak berdasarkan jumlah baris yang tercetak

            // Counter untuk jumlah barcode
            $barcodeCount++;
        }

        // Output PDF
        return $this->response->setHeader('Content-Type', 'application/pdf')
            ->setBody($pdf->Output('Barcode Pemasukan Lain-lain.pdf', 'I'));
    }
}
