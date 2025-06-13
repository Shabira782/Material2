<?php $this->extend($role . '/retur/header'); ?>
<?php $this->section('content'); ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <?php if (session()->getFlashdata('success')) : ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?= session()->getFlashdata('success') ?>',
                });
            });
        </script>
    <?php endif; ?>
    <!-- Content utama -->
    <div class="container-fluid py-4">
        <div class="row my-4">
            <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Material System</p>
                                    <h5 class="font-weight-bolder mb-0">FORM PERSETUJUAN RETUR AREA BAHAN BAKU <?= $detailRetur['jenis'] ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row my-4">
            <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body">
                        <form id="returForm" method="POST" action="<?= base_url($role . '/retur/saveRetur') ?>">
                            <input type="hidden" name="id_retur" value="<?= $detailRetur['id_retur'] ?>">
                            <!-- Informasi Dasar -->
                            <div class="section-title">
                                <i class="fas fa-info-circle me-2"></i>Detail Retur
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="tgl_retur" class="form-label">Tanggal Retur</label>
                                    <input type="text" class="form-control" id="tgl_retur" name="tgl_retur" value="<?= $detailRetur['tgl_retur'] ?>" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="no_model" class="form-label">No Model</label>
                                    <input type="text" class="form-control" id="no_model" name="no_model" value="<?= $detailRetur['no_model'] ?>" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="item_type" class="form-label">Item Type</label>
                                    <input type="text" class="form-control" id="item_type" name="item_type" value="<?= $detailRetur['item_type'] ?>" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="kode_warna" class="form-label">Kode Warna</label>
                                    <input type="text" class="form-control" id="kode_warna" name="kode_warna" value="<?= $detailRetur['kode_warna'] ?>" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="warna" class="form-label">Warna</label>
                                    <input type="text" class="form-control" id="warna" name="warna" value="<?= $detailRetur['warna'] ?>" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="loss" class="form-label">Loss Retur</label>
                                    <input type="text" class="form-control" id="loss" name="loss" value="<?= $detailRetur['loss'] . '%' ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="kg_po" class="form-label">Qty PO</label>
                                    <input type="text" class="form-control" id="kg_po" name="kg_po" value="<?= $detailRetur['kg_po'] ?? 0 ?>" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="kg_po_plus" class="form-label">Qty PO (+)</label>
                                    <input type="text" class="form-control" id="kg_po_plus" name="kg_po_plus" value="<?= $detailRetur['kg_po_plus'] ?? 0 ?>" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="qty_kirim" class="form-label">Qty Kirim (KG)</label>
                                    <input type="text" class="form-control" id="qty_kirim" name="qty_kirim" value="<?= $detailRetur['qty_kirim'] ?? 0 ?>" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="cones_kirim" class="form-label">Cones Kirim</label>
                                    <input type="text" class="form-control" id="cones_kirim" name="cones_kirim" value="<?= $detailRetur['cones_kirim'] ?? 0 ?>" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="karung_kirim" class="form-label">Karung Kirim</label>
                                    <input type="text" class="form-control" id="karung_kirim" name="karung_kirim" value="<?= $detailRetur['karung_kirim'] ?? 0 ?>" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="lot_kirim" class="form-label">Lot Kirim</label>
                                    <input type="text" class="form-control" id="lot_kirim" name="lot_kirim" value="<?= $detailRetur['lot_kirim'] ?? '' ?>" readonly>
                                </div>
                            </div>

                            <!-- Informasi Retur -->
                            <div class="section-title mt-4">
                                <i class="fas fa-calendar-alt me-2"></i>Informasi Retur
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="area_retur" class="form-label">Area Retur</label>
                                    <input type="text" class="form-control" id="area_retur" name="area_retur" value="<?= $detailRetur['area_retur'] ?? '' ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="tgl_retur" class="form-label">Tanggal Retur</label>
                                    <input type="date" class="form-control" id="tgl_retur" name="tgl_retur" value="<?= $detailRetur['tgl_retur'] ?? '' ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="kgs_retur" class="form-label">Qty Retur</label>
                                    <input type="number" class="form-control" id="kgs_retur" name="kgs_retur" value="<?= $detailRetur['kgs_retur'] ?? 0 ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="cns_retur" class="form-label">Cones Retur</label>
                                    <input type="number" class="form-control" id="cns_retur" name="cns_retur" value="<?= $detailRetur['cns_retur'] ?? 0 ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="krg_retur" class="form-label">Karung Retur</label>
                                    <input type="text" class="form-control" id="krg_retur" name="krg_retur" value="<?= $detailRetur['krg_retur'] ?? '' ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="lot_retur" class="form-label">Lot Retur</label>
                                    <input type="text" class="form-control" id="lot_retur" name="lot_retur" value="<?= $detailRetur['lot_retur'] ?? '' ?>" readonly>
                                </div>
                            </div>

                            <!-- Kategori dan Keterangan -->
                            <div class="section-title mt-4">
                                <i class="fas fa-tags me-2"></i>Kategori dan Keterangan
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="kategori" class="form-label">Kategori</label>
                                    <input type="text" class="form-control" id="kategori" name="kategori" value="<?= $detailRetur['kategori'] ?? '' ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="keterangan_area" class="form-label">Keterangan Area</label>
                                    <textarea class="form-control" id="keterangan_area" name="keterangan_area" rows="3" readonly><?= $detailRetur['keterangan_area'] ?? '' ?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="keterangan_gbn" class="form-label">Keterangan GBN</label>
                                    <textarea class="form-control" id="keterangan_gbn" name="keterangan_gbn" rows="3" required></textarea>
                                </div>
                            </div>

                            <!-- Cluster -->
                            <div class="section-title mt-4">
                                <div>
                                    <i class="fas fa-dolly-flatbed me-2"></i>Pemasukan Ke Jalur
                                </div>
                            </div>
                            <div class="row my-4">
                                <div class="table-responsive">
                                    <table id="returTable" class="table table-hover table-striped table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th width="250">Cluster</th>
                                                <th>Kapasitas</th>
                                                <th>Kgs</th>
                                                <th>Cones</th>
                                                <th>Krg/Pck</th>
                                                <th>Lot</th>
                                                <th>
                                                    <button class="btn btn-info w-100" type="button" id="addRow">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select name="nama_cluster[0]" class="form-select nama_cluster" style="width: 100%" required>
                                                        <option value="">Pilih Cluster</option>
                                                        <?php foreach ($cluster as $item): ?>
                                                            <option value="<?= $item['nama_cluster'] ?>"><?= $item['nama_cluster'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control kapasitas" name="kapasitas[0]" required readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control kgs" name="kgs[0]" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control cones" name="cones[0]" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control krg" name="krg[0]" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control lot" name="lot" value="<?= $detailRetur['lot_retur'] ?? '' ?>" required>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th class="text-center">Total Kgs</th>
                                                <th class="text-center">Total Cones</th>
                                                <th class="text-center">Total Krg/Pck</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><input type="float" class="form-control text-center" id="total_kgs" name="total_kgs" placeholder="0" readonly></td>
                                                <td><input type="float" class="form-control text-center" id="total_cones" name="total_cones" placeholder="0" readonly></td>
                                                <td><input type="float" class="form-control text-center" id="total_krg" name="total_krg" placeholder="0" readonly></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- Tombol Action -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-secondary me-2" onclick="resetForm()">
                                            <i class="fas fa-undo me-1"></i>Reset
                                        </button>
                                        <button type="submit" class="btn btn-dark">
                                            <i class="fas fa-check me-1"></i>Accept Retur
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('.nama_cluster').select2({
            width: '100%'
        });

        $(document).on('select2:select', '.nama_cluster', function(event) {
            const clusterValue = $(this).val();
            const kapasitasInput = $(this).closest("tr").find(".kapasitas");
            const BASE_URL = '<?= base_url($role) ?>';

            if (clusterValue) {
                fetch(`${BASE_URL}/sisaKapasitasByCLuster/${encodeURIComponent(clusterValue)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.kapasitas) {
                            kapasitasInput.val(parseFloat(data.kapasitas).toFixed(2));
                        } else {
                            kapasitasInput.val("");
                        }
                    })
                    .catch(() => {
                        kapasitasInput.val("");
                    });
            } else {
                kapasitasInput.val("");
            }
        });

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const returTable = document.getElementById('returTable');
        const tbody = returTable.querySelector('tbody');
        const addBtn = document.getElementById('addRow');

        const clusterOptions = `
                <option value="">Pilih Cluster</option>
                <?php foreach ($cluster as $c): ?>
                    <option value="<?= $c['nama_cluster'] ?>"><?= $c['nama_cluster'] ?></option>
                <?php endforeach; ?>
            `;

        // Hitung sekali saat load halaman
        calculateTotals();

        // Re-hitung saat ada input baru di tabel
        tbody.addEventListener('input', calculateTotals);

        document.getElementById('addRow').addEventListener('click', () => {
            const rowCount = tbody.querySelectorAll('tr').length;
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                    <td>
                        <select class="form-select text-center nama_cluster" name="nama_cluster[${rowCount}]">
                            ${clusterOptions}
                        </select>
                    </td>
                    <td><input type="number" class="form-control kapasitas" name="kapasitas[${rowCount}]" required readonly></td>
                    <td><input type="number" step="0.01" class="form-control kgs" name="kgs[${rowCount}]" required></td>
                    <td><input type="number" class="form-control cones" name="cones[${rowCount}]" required></td>
                    <td><input type="number" class="form-control krg" name="krg[${rowCount}]" required></td>
                    <td><input type="text" class="form-control lot" name="lot" value="<?= $detailRetur['lot_retur'] ?>" required></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger removeRow"><i class="fas fa-trash"></i></button>
                    </td>
                `;
            tbody.appendChild(newRow);
            // Aktifkan Select2 pada elemen baru
            $(newRow).find('.nama_cluster').select2({
                width: '100%'
            });

            // Pasang event pada tombol remove
            newRow.querySelector('.removeRow').addEventListener('click', () => {
                newRow.remove();
                setTimeout(calculateTotals, 0);
            });

            addBtn.addEventListener('click', () => {
                // menunggu DOM update
                setTimeout(calculateTotals, 0);
            });

        });
    });

    function calculateTotals() {
        let totalKgs = 0;
        let totalCones = 0;
        let totalKrg = 0;

        // Jumlahkan semua .kgs
        document.querySelectorAll('#returTable .kgs').forEach(input => {
            const val = parseFloat(input.value) || 0;
            totalKgs += val;
        });

        // Jumlahkan semua .cones
        document.querySelectorAll('#returTable .cones').forEach(input => {
            const val = parseFloat(input.value) || 0;
            totalCones += val;
        });

        // Jumlahkan semua .krg dan .karung (untuk baris statis + dinamis)
        document.querySelectorAll('#returTable .krg, #returTable .karung').forEach(input => {
            const val = parseFloat(input.value) || 0;
            totalKrg += val;
        });

        // Tampilkan hasil di footer
        document.getElementById('total_kgs').value = totalKgs.toFixed(2);
        document.getElementById('total_cones').value = totalCones;
        document.getElementById('total_krg').value = totalKrg;
    }
</script>

<!-- <script>
    // Fungsi untuk menghitung total
    function calculateTotals() {
        let totalKgs = 0;
        let totalCones = 0;
        let totalKrg = 0;

        // Jumlahkan semua .kgs
        document.querySelectorAll('#returTable .kgs').forEach(input => {
            const val = parseFloat(input.value) || 0;
            totalKgs += val;
        });

        // Jumlahkan semua .cones
        document.querySelectorAll('#returTable .cones').forEach(input => {
            const val = parseFloat(input.value) || 0;
            totalCones += val;
        });

        // Jumlahkan semua .krg dan .karung (untuk baris statis + dinamis)
        document.querySelectorAll('#returTable .krg, #returTable .karung').forEach(input => {
            const val = parseFloat(input.value) || 0;
            totalKrg += val;
        });

        // Tampilkan hasil di footer
        document.getElementById('total_kgs').value = totalKgs.toFixed(2);
        document.getElementById('total_cones').value = totalCones;
        document.getElementById('total_lot').value = totalKrg;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.querySelector('#returTable tbody');
        const addBtn = document.getElementById('addRow');

        // Hitung sekali saat load halaman
        calculateTotals();

        // Re-hitung saat ada input baru di tabel
        tbody.addEventListener('input', calculateTotals);

        // Setelah menambah baris, re-attach event dan hitung ulang
        addBtn.addEventListener('click', () => {
            // menunggu DOM update
            setTimeout(calculateTotals, 0);
        });

        // Re-hitung setelah menghapus baris
        tbody.addEventListener('click', event => {
            if (event.target.closest('.removeRow')) {
                // menunggu DOM update
                setTimeout(calculateTotals, 0);
            }
        });
    });
</script> -->


<?php $this->endSection(); ?>