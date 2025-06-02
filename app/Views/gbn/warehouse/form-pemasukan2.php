<?php $this->extend($role . '/warehouse/header'); ?>
<?php $this->section('content'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.min.css">
<style>
    /* Auto Complete */
    .ui-state-active {
        background: rgb(230, 153, 233) !important;
        color: #fff !important;
    }
</style>
<div class="container-fluid py-4">
    <?php if (session()->getFlashdata('success')) : ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    html: '<?= session()->getFlashdata('success') ?>',
                });
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: '<?= session()->getFlashdata('error') ?>',
                });
            });
        </script>
    <?php endif; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Form Input Bon</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url($role . '/savePemasukan2') ?>" method="post">
                            <div id="kebutuhan-container">
                                <div class="row mb-4">
                                    <div class="col-md-4">

                                        <label>Detail Surat Jalan</label>
                                        <select class="form-control" name="detail_sj" id="detail_sj" required>
                                            <option value="">Pilih Detail Surat Jalan</option>
                                            <option value="COVER MAJALAYA">COVER MAJALAYA</option>
                                            <option value="IMPORT DARI KOREA">IMPORT DARI KOREA</option>
                                            <option value="JS MISTY">JS MISTY</option>
                                            <option value="JS SOLID">JS SOLID</option>
                                            <option value="KHTEX">KHTEX</option>
                                            <option value="PO(+)">PO(+)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>No Surat Jalan</label>
                                        <input type="text" class="form-control" id="no_surat_jalan" name="no_surat_jalan" placeholder="No Surat Jalan" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Tanggal Datang</label>
                                        <input type="date" class="form-control" id="tgl_datang" name="tgl_datang" required>
                                    </div>
                                </div>
                                <!--  -->
                                <!-- <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">1</button>
                                    </div>
                                </nav> -->
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <!-- Form Items -->
                                        <div class="kebutuhan-item">
                                            <div class="row g-3 mb-2">
                                                <div class="col-md-12">
                                                    <label for="itemType">Done Celup</label>
                                                    <select class="form-control" id="add_item" name="add_item" required>
                                                        <option value="">Pilih Item </option>
                                                        <?php foreach ($no_model as $item): ?>
                                                            <option value="<?= $item['id_celup'] ?>"><?= $item['no_model'] ?> | <?= $item['item_type'] ?> |<?= $item['kode_warna'] ?> | <?= $item['warna'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-3">

                                                <div class="col-md-3">
                                                    <label>No Model</label>
                                                    <input type="text" class="form-control" name="id_celup" id="id_celup" required placeholder="Pilih No Model" readonly hidden>
                                                    <input type="text" class="form-control" name="no_model" id="no_model" required placeholder="No Model" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Item Type</label>
                                                    <input type="text" class="form-control" name="item_type" id="item_type" required placeholder="Item Type" readonly>

                                                </div>
                                                <div class="col-md-3">
                                                    <label>Kode Warna</label>
                                                    <input type="text" class="form-control" name="kode_warna" id="kode_warna" required placeholder="Kode Warna" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Warna</label>
                                                    <input type="text" class="form-control" name="warna" id="warna" required placeholder="Warna" readonly>
                                                </div>
                                            </div>

                                            <!-- Surat Jalan Section -->
                                            <div class="row g-3 mt-3">
                                                <div class="col-md-3">
                                                    <label>Lot</label>
                                                    <input type="float" class="form-control" name="lot" id="lot" required readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Harga</label>
                                                    <input type="float" class="form-control" name="harga" id="harga" placeholder="Harga" required>
                                                </div>

                                                <div class="col-md-2">
                                                    <label>LMD</label>
                                                    <select class="form-control" name="l_m_d" id="l_m_d" placeholder="L/M/D" required>
                                                        <option value="">Pilih LMD</option>
                                                        <option value="L">L</option>
                                                        <option value="M">M</option>
                                                        <option value="D">D</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>GW</label>
                                                    <input type="text" class="form-control" name="gw" id="gw" required placeholder="gw">
                                                </div>
                                                <div class="col-md-1">
                                                    <label for="ganti-retur" class="text-center">Ganti Retur</label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label>
                                                                <input type="hidden" name="ganti_retur" value="0">
                                                                <input type="checkbox" name="ganti_retur" id="ganti_retur" value="1"
                                                                    <?= isset($data['ganti_retur']) && $data['ganti_retur'] == 1 ? 'checked' : '' ?>>
                                                            </label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="">Ya</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-5">
                                                <h3>Form Input Data Karung</h3>
                                            </div>

                                            <!-- Out Celup Section -->
                                            <div class="row g-3 mt-3">
                                                <div class="table-responsive">
                                                    <table id="poTable" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th width=250 class="text-center">Cluster</th>
                                                                <th width=250 class="text-center">Kapasitas</th>
                                                                <th class="text-center">Nw</th>
                                                                <th class="text-center">Cones</th>
                                                                <th class="text-center">Karung</th>
                                                                <th class="text-center">
                                                                    <button type="button" class="btn btn-info" id="addRow">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <select class="form-select text-center nama_cluster" name="nama_cluster[0]">
                                                                        <option value="">Pilih Cluster</option>
                                                                        <?php foreach ($cluster as $c) { ?>
                                                                            <option value="<?= $c['nama_cluster'] ?>"><?= $c['nama_cluster'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </td>
                                                                <td><input type="float" class="form-control kapasitas" name="kapasitas[0]" required></td>
                                                                <td><input type="number" step="0.01" class="form-control kgs" name="kgs[0]" required></td>
                                                                <td><input type="float" class="form-control cones" name="cones[0]" required></td>
                                                                <td><input type="float" class="form-control karung" name="karung[0]" required></td>
                                                                <td class="text-center">
                                                                    <!-- <button type="button" class="btn btn-danger removeRow">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button> -->
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <!-- Baris Total -->
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="2"></td>
                                                                <th class="text-center">Total NW</th>
                                                                <th class="text-center">Total Cones</th>
                                                                <th class="text-center">Total Karung</th>
                                                                <th></th>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"></td>
                                                                <td><input type="number" step="0.01" class="form-control" id="total_kgs" name="total_kgs" placeholder="NW" readonly></td>
                                                                <td><input type="float" class="form-control" id="total_cones" name="total_cones" placeholder="Cones" readonly></td>
                                                                <td><input type="float" class="form-control" id="total_karung" name="total_karung" placeholder="Karung" readonly></td>
                                                                <td></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- Buttons -->
                                            <!-- <div class="row mt-3">
                                                <div class="col-12 text-center mt-2">
                                                    <button class="btn btn-icon btn-3 btn-outline-info add-more" type="button">
                                                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                                                    </button>
                                                    <button class="btn btn-icon btn-3 btn-outline-danger remove-tab" type="button">
                                                        <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
                                                    </button>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-info w-100">Save</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $(document).ready(function() {
            $('#add_item').select2({
                width: '100%'
            });
            $('.nama_cluster').select2({
                width: '100%'
            });
            $('#add_item').on("select2:select", function() {
                let idcelup = $(this).val(); // Ambil value yang dipilih di select2

                $.ajax({
                    url: "<?= base_url($role . '/pemasukan2/getItem/') ?>" + idcelup,
                    type: "POST",
                    data: {
                        id: idcelup
                    }, // Kirim dalam format object
                    dataType: "json",
                    success: function(data) {
                        // console.log(data);
                        $('#no_model').val(data.no_model);
                        $('#item_type').val(data.item_type);
                        $('#kode_warna').val(data.kode_warna);
                        $('#warna').val(data.warna);
                        $('#id_celup').val(idcelup);
                        $('#lot').val(data.lot_celup);
                    }
                });
            });
            $(document).on('select2:select', '.nama_cluster', function(event) {
                const clusterValue = $(this).val();
                const kapasitasInput = $(this).closest("tr").find(".kapasitas");
                const BASE_URL = '<?= base_url($role) ?>';

                console.log(clusterValue);

                if (clusterValue) {
                    fetch(`${BASE_URL}/sisaKapasitasByCLuster/${encodeURIComponent(clusterValue)}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);
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


        document.addEventListener('DOMContentLoaded', () => {
            const poTable = document.getElementById('poTable');
            const tbody = poTable.querySelector('tbody');
            // Generate opsi cluster dari PHP sekali saja
            const clusterOptions = `
                <option value="">Pilih Cluster</option>
                <?php foreach ($cluster as $c): ?>
                    <option value="<?= $c['nama_cluster'] ?>"><?= $c['nama_cluster'] ?></option>
                <?php endforeach; ?>
            `;

            // Fungsi untuk memperbarui opsi cluster
            function updateClusterOptions() {
                // Ambil semua select di dalam tbody
                const selects = document.querySelectorAll('.nama_cluster');

                // Kumpulkan semua nilai terpilih (kecuali "")
                const selected = Array.from(selects)
                    .map(select => select.value)
                    .filter(value => value !== ""); // Hanya ambil yang terisi

                // Nonaktifkan opsi yang sudah dipilih di elemen lain
                selects.forEach(select => {
                    const current = select.value; // Nilai yang sedang dipilih
                    Array.from(select.options).forEach(option => {
                        // Disabled jika opsi sudah dipilih di select lain (kecuali opsi saat ini)
                        option.disabled = selected.includes(option.value) && option.value !== current;
                    });
                });
            }

            // Event listener untuk memperbarui opsi ketika ada perubahan
            document.addEventListener('change', function(event) {
                if (event.target.classList.contains('nama_cluster')) {
                    updateClusterOptions();
                }
            });

            // Inisialisasi: pasang listener pada baris awal
            function initExistingRow(row) {
                const sel = row.querySelector('.nama_cluster');
                sel.addEventListener('change', () => {
                    updateClusterOptions();
                });
                row.querySelectorAll('input').forEach(inp => {
                    inp.addEventListener('input', () => {
                        calculateTotals(poTable);
                    });
                });
            }
            tbody.querySelectorAll('tr').forEach(initExistingRow);

            // Tombol tambah baris
            document.getElementById('addRow').addEventListener('click', () => {
                const rowCount = tbody.querySelectorAll('tr').length;
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>
                        <select class="form-select text-center nama_cluster" name="nama_cluster[${rowCount}]">
                            ${clusterOptions}
                        </select>
                    </td>
                    <td><input type="number" class="form-control kapasitas" name="kapasitas[${rowCount}]" required></td>
                    <td><input type="number" step="0.01" class="form-control kgs" name="kgs[${rowCount}]" required></td>
                    <td><input type="number" class="form-control cones" name="cones[${rowCount}]" required></td>
                    <td><input type="number" class="form-control karung" name="karung[${rowCount}]" required></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger removeRow"><i class="fas fa-trash"></i></button>
                    </td>
                `;
                tbody.appendChild(newRow);
                // Aktifkan Select2 pada elemen baru
                $(newRow).find('.nama_cluster').select2({
                    width: '100%'
                });

                // Pasang event pada select & input
                initExistingRow(newRow);

                // Pasang event pada tombol remove
                newRow.querySelector('.removeRow').addEventListener('click', () => {
                    newRow.remove();
                    calculateTotals(poTable);
                    updateClusterOptions();
                });

                // Setelah semua, perbarui opsi
                updateClusterOptions();
            });

            // Hitung awal & disable opsi
            calculateTotals(poTable);
            updateClusterOptions();

            $(document).on('input', '.kgs', function() {
                const $row = $(this).closest('tr'); // Ambil baris terkait
                const kapasitasValue = parseFloat($row.find('.kapasitas').val()) || 0; // Ambil nilai kapasitas
                const kgsValue = parseFloat($(this).val()) || 0; // Ambil nilai kgs

                // Periksa apakah kgs melebihi kapasitas
                if (kgsValue > kapasitasValue) {
                    alert('Melebihi kapasitas!');
                    $(this).val(''); // Reset nilai input KGS
                    calculateTotals(poTable);
                }
            });
        });

        // function updateRowNumbers(poTable) {
        //     const rows = poTable.querySelectorAll('tbody tr');
        //     rows.forEach((row, index) => {
        //         const karungInput = row.querySelector('input[name^="no_karung"]');
        //         if (karungInput) {
        //             karungInput.value = index + 1;
        //         }
        //     });
        // }

        function calculateTotals(poTable) {
            let totalKgs = 0;
            let totalCones = 0;
            let totalKarung = 0;
            let totalRows = 0;

            // Hitung total berdasarkan input di dalam poTable
            poTable.querySelectorAll("tbody tr").forEach(row => {
                totalKgs += parseFloat(row.querySelector("input[name^='kgs']")?.value || 0);
                totalKgs = parseFloat(totalKgs.toFixed(2));
                totalCones += parseFloat(row.querySelector("input[name^='cones']")?.value || 0);
                totalKarung += parseFloat(row.querySelector("input[name^='karung']")?.value || 0);
            });

            totalRows = poTable.querySelectorAll("tbody tr").length;

            // Perbarui nilai di <tfoot>
            const tfoot = poTable.querySelector("tfoot");
            if (tfoot) {
                // tfoot.querySelector("input[id^='total_karung']").value = totalRows;
                tfoot.querySelector("input[id^='total_kgs']").value = totalKgs;
                tfoot.querySelector("input[id^='total_cones']").value = totalCones;
                tfoot.querySelector("input[id^='total_karung']").value = totalKarung;
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Untuk tab pertama
            const firstPoTable = document.querySelector("#poTable-1");
            if (firstPoTable) {
                firstPoTable.querySelectorAll("input").forEach(input => {
                    input.addEventListener("input", function() {
                        calculateTotals(firstPoTable);
                    });
                });
                calculateTotals(firstPoTable); // Hitung total awal
            }
        });

        // Event listener tombol
        // newTabPane.querySelector(".add-more").addEventListener("click", addNewTab);
    </script>

    <?php $this->endSection(); ?>