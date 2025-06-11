<?php $this->extend($role . '/pemesanan/header'); ?>
<?php $this->section('content'); ?>
<style>
    /* Main container styling */
    .container-fluid {
        padding: 1.5rem;
    }

    /* Card grid styling */
    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.25rem;
        margin-top: 1.5rem;
    }

    /* Individual card styling */
    .stock-card {
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        overflow: hidden;
        height: 100%;
    }

    .stock-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
        border-color: #c9d1d9;
    }

    .stock-card .card-header {
        background-color: #082653;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1rem;
        border-bottom: none;
    }

    .stock-card .card-body {
        padding: 1.25rem;
    }

    /* Stock info styling */
    .stock-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .stock-info .label {
        font-weight: 500;
        color: #495057;
    }

    .stock-info .value {
        font-weight: 600;
        color: #212529;
    }

    /* Divider styling */
    .divider {
        height: 1px;
        background-color: #e9ecef;
        margin: 0.75rem 0;
    }

    /* Modal styling */
    .modal-header {
        background-color: #082653;
        color: white;
        border-bottom: none;
    }

    .modal-header .btn-close {
        color: white;
        filter: brightness(0) invert(1);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .info-badge {
        font-size: 0.85rem;
        padding: 0.5rem;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        font-weight: 500;
        background-color: #e9f3ff;
        color: #0d6efd;
        border: 1px solid #c9deff;
    }

    .info-section {
        margin-bottom: 1.5rem;
    }

    .info-section-title {
        font-weight: 600;
        margin-bottom: 1rem;
        color: #082653;
        border-bottom: 2px solid #082653;
        padding-bottom: 0.5rem;
        display: inline-block;
    }

    /* Form styling */
    .form-section {
        background-color: #f8f9fa;
        padding: 1.25rem;
        border-radius: 8px;
        margin-top: 1.5rem;
    }

    .form-section-title {
        font-weight: 600;
        margin-bottom: 1rem;
        color: #082653;
    }

    .form-control {
        border-radius: 6px;
        padding: 0.6rem 0.75rem;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(8, 38, 83, 0.25);
        border-color: #082653;
    }

    .btn-submit {
        background-color: #082653;
        border-color: #082653;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
    }

    .btn-submit:hover {
        background-color: #061c3e;
        border-color: #061c3e;
    }

    /* Empty state styling */
    .empty-state {
        text-align: center;
        padding: 3rem;
        background-color: #f8f9fa;
        border-radius: 10px;
        grid-column: 1 / -1;
    }

    .empty-state-icon {
        font-size: 3rem;
        color: #adb5bd;
        margin-bottom: 1rem;
    }

    .empty-state-text {
        color: #6c757d;
        font-weight: 500;
    }

    /* Responsive Layout */
    @media (min-width: 768px) {
        .card-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 992px) {
        .card-container {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (min-width: 1200px) {
        .card-container {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    /* Custom Checkbox */
    .checkbox-new {
        position: relative;
        width: 20px;
        height: 20px;
        background-color: #f0f0f0;
        border: 2px solid #ccc;
        border-radius: 4px;
        transition: all 0.3s ease;
    }
</style>

<div class="container-fluid">
    <?php if (session()->getFlashdata('success')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    html: '<?= session()->getFlashdata('success') ?>',
                    confirmButtonColor: '#082653'
                });
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: '<?= session()->getFlashdata('error') ?>',
                    confirmButtonColor: '#082653'
                });
            });
        </script>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Data Stock</h3>
            <span class="badge bg-gradient-info"><?= date('d F Y'); ?></span>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <button id="saveButton" class="btn btn-sm btn-info">Save</button>
            </div>
            <!-- <button type="submit" class="btn btn-sm btn-info">Save</button> -->
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered table-hover table-sm text-center text-uppercase" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center text-secondary text-sm font-weight-bolder">No Model</th>
                            <th class="text-center text-secondary text-sm font-weight-bolder">Item Type</th>
                            <th class="text-center text-secondary text-sm font-weight-bolder">Kode Warna</th>
                            <th class="text-center text-secondary text-sm font-weight-bolder">Warna</th>
                            <th class="text-center text-secondary text-sm font-weight-bolder">Jalan Mc</th>
                            <th class="text-center text-secondary text-sm font-weight-bolder">Total Kgs Pesan</th>
                            <th class="text-center text-secondary text-sm font-weight-bolder">Total Cns Pesan</th>
                            <th class="text-center text-secondary text-sm font-weight-bolder">Lot Pesan</th>
                            <th class="text-center text-secondary text-sm font-weight-bolder">Keterangan Area</th>
                            <th class="text-center text-secondary text-sm font-weight-bolder">Pilih</th>
                            <th class="text-center text-secondary text-sm font-weight-bolder">Cluster</th>
                            <th class="text-center text-secondary text-sm font-weight-bolder">Kg Stock</th>
                            <th class="text-center text-secondary text-sm font-weight-bolder">Cns Stock</th>
                            <th class="text-center text-secondary text-sm font-weight-bolder">Krg Stock</th>
                            <th class="text-center text-secondary text-sm font-weight-bolder">Lot Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pemesanan as $key => $id) {
                            if ($id['no_model'] != @$pemesanan[$key - 1]['no_model'] || $id['item_type'] != @$pemesanan[$key - 1]['item_type'] || $id['kode_warna'] != @$pemesanan[$key - 1]['kode_warna'] || $id['color'] != @$pemesanan[$key - 1]['color']) {
                                $no_model = $id['no_model'];
                                $item_type = $id['item_type'];
                                $kode_warna = $id['kode_warna'];
                                $warna = $id['color'];
                                $ttl_jl_mc = $id['ttl_jl_mc'];
                                $ttl_kg = $id['ttl_kg'];
                                $ttl_cns = $id['ttl_cns'];
                                $lot = $id['lot'];
                                $keterangan = $id['keterangan'];
                            } else {
                                $no_model = "";
                                $item_type = "";
                                $kode_warna = "";
                                $warna = "";
                                $ttl_jl_mc = "";
                                $ttl_kg = "";
                                $ttl_cns = "";
                                $lot = "";
                                $keterangan = "";
                            }
                        ?>
                            <tr>
                                <td class="text-center align-middle"><?= $no_model; ?></td>
                                <td class="text-center align-middle"><?= $item_type; ?></td>
                                <td class="text-center align-middle"><?= $kode_warna; ?></td>
                                <td class="text-center align-middle"><?= $warna; ?></td>
                                <td class="text-center align-middle"><?= $ttl_jl_mc; ?></td>
                                <td class="text-center align-middle"><?= $ttl_kg; ?></td>
                                <td class="text-center align-middle"><?= $ttl_cns; ?></td>
                                <td class="text-center align-middle"><?= $lot; ?></td>
                                <td class="text-center align-middle"><?= $keterangan; ?></td>
                                <td class="text-center align-middle">
                                    <?php if (!empty($id['nama_cluster'])) { ?>
                                        <input type="checkbox" class="checkbox-new checkbox"
                                            data-id-total-pesanan="<?= $id['id_total_pemesanan']; ?>"
                                            data-id-stock="<?= $id['id_stock']; ?>"
                                            data-id-pengeluaran="<?= $id['id_pengeluaran']; ?>"
                                            <?= !empty($id['id_pengeluaran']) ? 'checked' : ''; ?>>
                                    <?php } ?>
                                </td>
                                <td class="text-center align-middle"><?= $id['nama_cluster'] ?></td>
                                <td class="text-center align-middle"><?= $id['kg_stock'] ?></td>
                                <td class="text-center align-middle"><?= $id['cns_stock'] ?></td>
                                <td class="text-center align-middle"><?= $id['krg_stock'] ?></td>
                                <td class="text-center align-middle"><?= $id['lot_stock'] ?></td>
                            </tr>
                        <?php
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedData = [];
    let deletedData = [];

    // Event listener untuk checkbox
    document.querySelectorAll('.checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const parentRow = this.closest('tr');
            const lot = parentRow.querySelector('td:nth-child(15)').innerText.trim();
            const cluster = parentRow.querySelector('td:nth-child(11)').innerText.trim();
            const idTotalPesanan = this.dataset.idTotalPesanan;
            const idStock = this.dataset.idStock;
            const idPengeluaran = this.dataset.idPengeluaran;
            const area = "<?= $area; ?>"; // Ganti dengan area yang relevan

            console.log('Parent Row:', parentRow);

            const dataObject = {
                id_total_pemesanan: idTotalPesanan,
                id_stock: idStock,
                lot_out: lot,
                nama_cluster: cluster,
                area_out: area,
                id_pengeluaran: idPengeluaran
            };
            console.log(dataObject);

            if (this.checked) {
                // Tambahkan ke array selectedData jika belum ada
                if (!selectedData.some(item => item.id_total_pemesanan === idTotalPesanan)) {
                    selectedData.push(dataObject);
                }
                // Hapus dari deletedData jika ada
                deletedData = deletedData.filter(item => item.id_total_pemesanan !== idTotalPesanan);
            } else {
                // Tambahkan ke array deletedData jika belum ada
                if (!deletedData.some(item => item.id_total_pemesanan === idTotalPesanan)) {
                    deletedData.push(dataObject);
                }
                // Hapus dari selectedData jika ada
                selectedData = selectedData.filter(item => item.id_total_pemesanan !== idTotalPesanan);
            }
        });
    });


    // Event listener untuk tombol Save
    document.getElementById('saveButton').addEventListener('click', function() {
        console.log('Selected Data:', selectedData);
        console.log('Deleted Data:', deletedData);

        if (selectedData.length === 0 && deletedData.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: 'Harap pilih setidaknya satu data untuk disimpan atau dihapus!',
            });
            return; // Hentikan eksekusi lebih lanjut
        }

        // Variabel untuk melacak status operasi
        let saveSuccess = false,
            deleteSuccess = false;

        // Kirim data yang dipilih (selectedData)
        const savePromise = selectedData.length > 0 ? fetch('<?= base_url($role . "/warehouse/saveSelectCluster"); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?= csrf_hash(); ?>'
            },
            body: JSON.stringify({
                selectedData
            })
        }).then(response => response.json()) : Promise.resolve({
            success: true
        });

        // Kirim data yang dihapus (deletedData)
        const deletePromise = deletedData.length > 0 ? fetch('<?= base_url($role . "/warehouse/deleteSelectCluster"); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?= csrf_hash(); ?>'
            },
            body: JSON.stringify({
                selectedData: deletedData
            })
        }).then(response => response.json()) : Promise.resolve({
            success: true
        });

        // Tunggu semua operasi selesai
        Promise.all([savePromise, deletePromise])
            .then(([saveResult, deleteResult]) => {
                saveSuccess = saveResult.success;
                deleteSuccess = deleteResult.success;

                // Tampilkan SweetAlert berdasarkan hasil operasi
                if (saveSuccess && deleteSuccess) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data berhasil disimpan!',
                    });
                } else if (saveSuccess) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian!',
                        text: 'Data berhasil disimpan, tetapi ada masalah saat menghapus data!',
                    });
                } else if (deleteSuccess) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian!',
                        text: 'Data berhasil dihapus, tetapi ada masalah saat menyimpan data!',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Gagal menyimpan atau menghapus data!',
                    });
                }
                // Reset array setelah selesai
                selectedData = [];
                deletedData = [];
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat memproses data!',
                });
            });
    });
</script>

<?php $this->endSection(); ?>