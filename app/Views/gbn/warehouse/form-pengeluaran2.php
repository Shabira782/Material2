<?php $this->extend($role . '/warehouse/header'); ?>
<?php $this->section('content'); ?>

<div class="container-fluid py-4">

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Header Card -->
        <div class="col-xl-12 col-sm-12 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold"><?= $title; ?></p>
                            <h5 class="font-weight-bolder mb-0">
                                Pemesanan Bahan Baku <?= $pengeluaran[0]['jenis']; ?> Area <?= $pengeluaran[0]['admin']; ?>
                            </h5>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="ni ni-chart-bar-32 text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- List Pemesanan -->
        <div class="col-12">
            <div class="card-body px-0 pt-0 pb-2">
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <p><strong>NO MODEL:</strong> <?= $pengeluaran[0]['no_model']; ?></p>
                                        <p><strong>FU:</strong> <?= $pengeluaran[0]['foll_up']; ?></p>
                                        <p><strong>AREA:</strong> <?= $pengeluaran[0]['admin']; ?></p>
                                        <p><strong>TGL PAKAI:</strong> <?= date('d-m-Y', strtotime($pengeluaran[0]['tgl_pakai'])); ?></p>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <p><strong>ITEM TYPE:</strong> <?= $pengeluaran[0]['item_type']; ?></p>
                                        <p><strong>WARNA:</strong> <?= $pengeluaran[0]['color']; ?></p>
                                        <p><strong>KODE WARNA:</strong> <?= $pengeluaran[0]['kode_warna']; ?></p>
                                        <p><strong>SISA JATAH:</strong> <?= $sisa_jatah ?> KG</p>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <p><strong>TTL PESAN (KG):</strong> <?= number_format($pengeluaran[0]['ttl_kg'], 0, ',', '.'); ?> KG</p>
                                        <p><strong>CONES PESAN:</strong> <?= $pengeluaran[0]['ttl_cns']; ?> CONES</p>
                                        <p><strong>LOT PESAN:</strong> <?= $pengeluaran[0]['lot']; ?></p>
                                        <p><strong>KETERANGAN:</strong> <?= $pengeluaran[0]['keterangan'] ?? "-"; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Pengeluaran -->
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Form Pengeluaran Bahan Baku</h6>
                </div>
                <div class="card-body px-3 pt-3 pb-2">
                    <form action="<?= base_url($role . '/savePengirimanArea'); ?>" method="post">

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="jenis" value="<?= $pengeluaran[0]['jenis'] ?>">
                        <input type="hidden" name="no_model" value="<?= $pengeluaran[0]['no_model'] ?>">
                        <input type="hidden" name="foll_up" value="<?= $pengeluaran[0]['foll_up'] ?>">
                        <input type="hidden" name="area_out" value="<?= $pengeluaran[0]['admin'] ?>">
                        <input type="hidden" name="tgl_pakai" value="<?= $pengeluaran[0]['tgl_pakai'] ?>">

                        <!-- Validation Errors -->
                        <?php if (isset($validation)) : ?>
                            <div class="alert alert-danger">
                                <?= $validation->listErrors() ?>
                            </div>
                        <?php endif; ?>

                        <!-- Baris Form Input Dinamis -->
                        <?php foreach ($pengeluaran as $i => $data): ?>
                            <input type="hidden" name="id_pengeluaran[]" value="<?= $data['id_pengeluaran'] ?>">
                            <input type="hidden" name="id_stock[]" value="<?= $data['id_stock'] ?>">
                            <input type="hidden" name="kg_kirim_before[]" value="<?= $data['kgs_out'] ?>">
                            <input type="hidden" name="cns_kirim_before[]" value="<?= $data['cns_out'] ?>">
                            <input type="hidden" name="krg_kirim_before[]" value="<?= $data['krg_out'] ?>">

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="palet_<?= $i ?>">Palet</label>
                                    <input
                                        type="text"
                                        id="palet_<?= $i ?>"
                                        class="form-control"
                                        name="palet[]"
                                        value="<?= $data['nama_cluster'] ?>"
                                        readonly>
                                </div>
                                <div class="col-md-2">
                                    <label>Lot</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="lot[]"
                                        value="<?= $data['lot_stock'] ?>"
                                        required>
                                </div>
                                <div class="col-md-1">
                                    <label>KG Stok</label>
                                    <input
                                        type="number"
                                        class="form-control kgs-stock"
                                        name="kgs_stok[]"
                                        value="<?= number_format($data['kg_stock'] + $data['kgs_out'], 2) ?>"
                                        readonly>
                                </div>
                                <div class="col-md-2">
                                    <label>KG Kirim</label>
                                    <input
                                        type="number"
                                        class="form-control kg-kirim"
                                        name="kg_kirim[]"
                                        value="<?= $data['kgs_out'] ?>"
                                        required>
                                </div>
                                <div class="col-md-1">
                                    <label>Cns Stok</label>
                                    <input
                                        type="number"
                                        class="form-control cns-stock"
                                        name="cns_stok[]"
                                        value="<?= $data['cns_stock'] + $data['cns_out'] ?>"
                                        readonly>
                                </div>
                                <div class="col-md-2">
                                    <label>Cns Kirim</label>
                                    <input
                                        type="number"
                                        class="form-control cns-kirim"
                                        name="cns_kirim[]"
                                        value="<?= $data['cns_out'] ?>"
                                        required>
                                </div>
                                <div class="col-md-1">
                                    <label>Krg Stok</label>
                                    <input
                                        type="number"
                                        class="form-control krg-stock"
                                        name="krg_stok[]"
                                        value="<?= $data['krg_stock'] + $data['krg_out'] ?>"
                                        readonly>
                                </div>
                                <div class="col-md-1">
                                    <label>Krg Kirim</label>
                                    <input
                                        type="number"
                                        class="form-control krg-kirim"
                                        name="krg_kirim[]"
                                        value="<?= $data['krg_out'] ?>"
                                        required>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Total Summary -->
                        <div class="row mt-3 text-center">
                            <div class="col-md-4">
                                <strong>Total KG Kirim:</strong>
                                <span id="total-kg-kirim">0</span> KG
                            </div>
                            <div class="col-md-4">
                                <strong>Total Cones Kirim:</strong>
                                <span id="total-cns-kirim">0</span> CONES
                            </div>
                            <div class="col-md-4">
                                <strong>Total Karung Kirim:</strong>
                                <span id="total-krg-kirim">0</span> KARUNG
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn bg-gradient-info w-100 mt-3">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- JavaScript Total Calculator -->
<script>
    function hitungTotal(selector, outputId) {
        let total = 0;
        document.querySelectorAll(selector).forEach(function(input) {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById(outputId).textContent = total.toLocaleString();
    }

    function validateInput(input, stockClass) {
        // Temukan elemen stok terdekat berdasarkan kelas stok
        const stockInput = input.closest('.row').querySelector(stockClass);
        console.log(stockInput);
        if (!stockInput) return; // Jika elemen stok tidak ditemukan, hentikan

        const maxStock = parseFloat(stockInput.value) || 0; // Ambil nilai stok
        const currentValue = parseFloat(input.value) || 0; // Ambil nilai input

        if (currentValue > maxStock) {
            alert(`Nilai tidak boleh lebih besar dari stock (${maxStock})!`);
            input.value = maxStock; // Set nilai ke stok maksimum
        }
    }

    function updateAllTotals() {
        hitungTotal('.kg-kirim', 'total-kg-kirim');
        hitungTotal('.cns-kirim', 'total-cns-kirim');
        hitungTotal('.krg-kirim', 'total-krg-kirim');
    }

    // Validasi semua input di halaman
    function validateAllInputs() {
        document.querySelectorAll('.kg-kirim, .cns-kirim, .krg-kirim').forEach(function(input) {
            validateInput(input);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateAllTotals();
        document.querySelectorAll('.kg-kirim, .cns-kirim, .krg-kirim').forEach(function(input) {
            input.addEventListener('input', updateAllTotals);
            input.addEventListener('input', function() {
                if (input.classList.contains('kg-kirim')) {
                    validateInput(input, '.kgs-stock'); // Validasi KG Kirim
                } else if (input.classList.contains('cns-kirim')) {
                    validateInput(input, '.cns-stock'); // Validasi Cns Kirim
                } else if (input.classList.contains('krg-kirim')) {
                    validateInput(input, '.krg-stock'); // Validasi Krg Kirim
                }
            });
        });
    });
</script>

<?php $this->endSection(); ?>