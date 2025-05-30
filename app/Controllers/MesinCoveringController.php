<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MesinCoveringModel;


class MesinCoveringController extends BaseController
{
    protected $role;
    protected $active;
    protected $filters;
    protected $request;
    protected $mesinCoveringModel;

    public function __construct()
    {
        $this->mesinCoveringModel = new MesinCoveringModel();

        $this->role = session()->get('role');
        $this->active = '/index.php/' . session()->get('role');
        if ($this->filters   = ['role' => ['covering']] != session()->get('role')) {
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

    public function mesinCovering()
    {
        $mesinCovering = $this->mesinCoveringModel->getAllMesinCovering();
        $data = [
            'active' => $this->active,
            'title' => 'Mesin Covering',
            'role' => $this->role,
            'mesinCovering' => $mesinCovering,
        ];
        // dd($data);
        return view($this->role . '/mesin/index', $data);
    }

    public function getMesinCovDetails($id)
    {
        if ($this->request->isAJAX()) {
            $data = $this->mesinCoveringModel->find($id);

            if ($data) {
                return $this->response->setJSON([
                    'id_mesin' => $data['id_mesin'],
                    'no_mesin' => $data['no_mesin'],
                    'nama' => $data['nama'],
                    'jenis' => $data['jenis'],
                    'buatan' => $data['buatan'],
                    'merk' => $data['merk'],
                    'type' => $data['type'],
                    'jml_spindle' => $data['jml_spindle'],
                    'tahun' => $data['tahun'],
                    'jml_unit' => $data['jml_unit'],
                ]);
            } else {
                return $this->response->setJSON(['error' => 'Data tidak ditemukan.']);
            }
        }

        throw new \CodeIgniter\Exceptions\PageNotFoundException();
    }

    public function saveDataMesin()
    {
        $data = $this->request->getPost();
        // dd($data);

        $saveData = [
            'no_mesin' => $data['no_mesinAdd'],
            'nama' => $data['namaAdd'],
            'jenis' => $data['jenisAdd'],
            'buatan' => $data['buatanAdd'],
            'merk' => $data['merkAdd'],
            'type' => $data['typeAdd'],
            'jml_spindle' => $data['jmlSpindleAdd'],
            'tahun' => $data['tahunAdd'],
            'jml_unit' => $data['jmlUnitAdd'],
        ];

        if ($this->mesinCoveringModel->save($saveData)) {
            return redirect()->to(base_url($this->role . '/mesinCov'))->with('success', 'Data berhasil disimpan.');
        } else {
            return redirect()->to(base_url($this->role . '/mesinCov'))->with('error', 'Data gagal disimpan.');
        }
    }

    public function updateDataMesin()
    {
        $data = $this->request->getPost();
        $id_mesin = $data['id_mesin'];
        $no_mesin = $data['no_mesinE'];
        $cekNoMesin = $this->mesinCoveringModel
            ->where('no_mesin', $no_mesin)
            ->where('id_mesin !=', $id_mesin) // Pastikan tidak mengecek diri sendiri
            ->first();

        if ($cekNoMesin) {
            // Jika duplikat ditemukan, kembalikan pesan error
            return redirect()->to(base_url($this->role . '/mesinCov'))->with('error', 'Nomor mesin sudah digunakan.');
        }
        
        $saveData = [
            'no_mesin' => $data['no_mesinE'],
            'nama' => $data['namaCov'],
            'jenis' => $data['jenis'],
            'buatan' => $data['buatan'],
            'merk' => $data['merk'],
            'type' => $data['type'],
            'jml_spindle' => $data['jmlSpindle'],
            'tahun' => $data['tahun'],
            'jml_unit' => $data['jmlUnit'],
        ];
        // dd($data, $saveData);
        if ($this->mesinCoveringModel->update($id_mesin, $saveData)) {
            return redirect()->to(base_url($this->role . '/mesinCov'))->with('success', 'Data berhasil diubah.');
        } else {
            return redirect()->to(base_url($this->role . '/mesinCov'))->with('error', 'Data gagal diubah.');
        }
    }

    public function deleteDataMesin($id)
    {
        if ($this->mesinCoveringModel->delete($id)) {
            return redirect()->to(base_url($this->role . '/mesinCov'))->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->to(base_url($this->role . '/mesinCov'))->with('error', 'Data gagal dihapus.');
        }
    }
}
