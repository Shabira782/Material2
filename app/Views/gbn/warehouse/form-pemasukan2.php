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
                                            <!-- <option value="KHTEX">KHTEX</option> -->
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
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">1</button>
                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <!-- Form Items -->
                                        <div class="kebutuhan-item">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label>No Model</label>
                                                    <select class="form-control" id="id_celup" name="id_celup[0]" required>
                                                        <option value="">Pilih No Model </option>
                                                        <?php foreach ($no_model as $item): ?>
                                                            <option value="<?= $item['no_model'] ?>"><?= $item['no_model'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <input type="text" class="form-control" id="no_model" name="no_model[0]" hidden>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Item Type</label>
                                                    <select class="form-select" name="item_type[0]" id="item_type" required>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Kode Warna</label>
                                                    <select class="form-select" name="kode_warna[0]" id="kode_warna" required>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Warna</label>
                                                    <input type="text" class="form-control" name="warna[0]" id="warna" required placeholder="Warna" readonly>
                                                </div>

                                            </div>

                                            <!-- Surat Jalan Section -->
                                            <div class="row g-3 mt-3">
                                                <div class="col-md-3">
                                                    <label>Harga</label>
                                                    <input type="float" class="form-control" name="harga[0]" id="harga" placeholder="Harga" required>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Lot</label>
                                                    <input type="float" class="form-control" name="lot[0]" id="lot" placeholder="Lot" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>LMD</label>
                                                    <select class="form-control" name="l_m_d" id="l_m_d[0]" placeholder="L/M/D" required>
                                                        <option value="">Pilih LMD</option>
                                                        <option value="L">L</option>
                                                        <option value="M">M</option>
                                                        <option value="D">D</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>GW</label>
                                                    <input type="text" class="form-control" name="gw[0]" id="gw" required placeholder="gw">
                                                </div>
                                                <div class="col-md-1">
                                                    <label for="ganti-retur" class="text-center">Ganti Retur</label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label>
                                                                <input type="hidden" name="ganti_retur[0]" value="0">
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
                                                                <th width=200 class="text-center">Cluster</th>
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
                                                                    <select class="form-select text-center nama_cluster" name="nama_cluster[0][0]">
                                                                        <option value="">Pilih Cluster</option>
                                                                        <?php foreach ($cluster as $c) { ?>
                                                                            <option value="<?= $c['nama_cluster'] ?>"><?= $c['nama_cluster'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </td>
                                                                <td><input type="float" class="form-control kgs" name="kgs[0][0]" required></td>
                                                                <td><input type="float" class="form-control cones" name="cones[0][0]" required></td>
                                                                <td><input type="float" class="form-control karung" name="karung[0][0]" required></td>
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
                                                                <th class="text-center"></th>
                                                                <th class="text-center">Total NW</th>
                                                                <th class="text-center">Total Cones</th>
                                                                <th class="text-center">Total Karung</th>
                                                                <th></th>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td><input type="float" class="form-control" id="total_kgs" name="total_kgs" placeholder="NW" readonly></td>
                                                                <td><input type="float" class="form-control" id="total_cones" name="total_cones" placeholder="Cones" readonly></td>
                                                                <td><input type="float" class="form-control" id="total_karung" name="total_karung" placeholder="Karung" readonly></td>
                                                                <td></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- Buttons -->
                                            <div class="row mt-3">
                                                <div class="col-12 text-center mt-2">
                                                    <button class="btn btn-icon btn-3 btn-outline-info add-more" type="button">
                                                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                                                    </button>
                                                    <button class="btn btn-icon btn-3 btn-outline-danger remove-tab" type="button">
                                                        <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
                                                    </button>
                                                </div>
                                            </div>
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
            $('#id_celup').select2();
            $('#id_celup').on("select2:select", function() {
                let id_celup = $(this).val(); // Ambil value yang dipilih di select2
                let no_model = $('#id_celup option:selected').text(); // Ambil teks (No Model) dari opsi yang dipilih

                // Isi nilai No Model ke input
                $('#no_model').val(no_model);

                // Ambil item type berdasarkan id_order
                $.ajax({
                    url: "<?= base_url($role . '/otherIn/getItemTypeForPemasukan2/') ?>" + id_celup,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        // Kosongkan opsi sebelumnya
                        $('#item_type').empty();

                        // Tambahkan opsi default
                        $('#item_type').append('<option value="">-- Pilih Item Type --</option>');

                        // Iterasi data untuk menambahkan opsi
                        if (data && data.length > 0) {
                            data.forEach(function(item) {
                                $('#item_type').append(
                                    '<option value="' + item.item_type + '">' + item.item_type + '</option>'
                                );
                            });
                        } else {
                            $('#item_type').append('<option value="">Tidak ada item tersedia</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengambil data Item Type.');
                    }
                });
            });

            // Event listener untuk mendapatkan kode warna saat item type dipilih
            $('#item_type').on("change", function() {
                let id_order = $('#id_order').val(); // Pastikan id_order masih sama
                let item_type = $(this).val(); // Ambil nilai item type yang dipilih

                // Ambil kode warna berdasarkan id_order dan item_type
                if (item_type) {
                    $.ajax({
                        url: "<?= base_url($role . '/otherIn/getKodeWarnaForOtherIn') ?>",
                        type: "POST",
                        data: {
                            id_order: id_order,
                            item_type: item_type,
                        },
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            // Kosongkan opsi sebelumnya
                            $('#kode_warna').empty();

                            // Tambahkan opsi default
                            $('#kode_warna').append('<option value="">-- Pilih Kode Warna --</option>');

                            // Iterasi data untuk menambahkan opsi
                            if (data && data.length > 0) {
                                data.forEach(function(item) {

                                    $('#kode_warna').append(
                                        '<option value="' + item.kode_warna + '">' + item.kode_warna + '</option>'
                                    );
                                });
                            } else {
                                $('#kode_warna').append('<option value="">Tidak ada kode warna tersedia</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat mengambil data Kode Warna.');
                        }
                    });
                } else {
                    // Kosongkan dropdown jika tidak ada item type yang dipilih
                    $('#kode_warna').empty();
                    $('#kode_warna').append('<option value="">-- Pilih Kode Warna --</option>');
                }
            });
            // Event listener untuk mendapatkan kode warna saat item type dipilih
            $('#kode_warna').on("change", function() {
                let id_order = $('#id_order').val(); // Pastikan id_order masih sama
                let item_type = $('#item_type').val(); // Pastikan id_order masih sama
                let kode_warna = $(this).val(); // Ambil nilai item type yang dipilih

                // Ambil kode warna berdasarkan id_order dan item_type
                if (item_type) {
                    $.ajax({
                        url: "<?= base_url($role . '/otherIn/getWarnaForOtherIn') ?>",
                        type: "POST",
                        data: {
                            id_order: id_order,
                            item_type: item_type,
                            kode_warna: kode_warna,
                        },
                        dataType: "json",
                        success: function(data) {
                            $('#warna').val(data.color); // Ambil warna dari respons
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat mengambil data Kode Warna.');
                        }
                    });
                } else {
                    // Kosongkan dropdown jika tidak ada item type yang dipilih
                    $('#warna').empty();
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
                // Ambil semua select di dalam poTable
                const selects = tbody.querySelectorAll('.nama_cluster');
                // Kumpulkan semua nilai terpilih (kecuali "")
                const selected = Array.from(selects)
                    .map(s => s.value)
                    .filter(v => v !== "");

                // Nonaktifkan opsi yang sudah dipilih di elemen lain
                selects.forEach(s => {
                    const current = s.value;
                    Array.from(s.options).forEach(opt => {
                        opt.disabled = (opt.value !== current) && selected.includes(opt.value);
                    });
                });
            }

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
            <td><input type="number" class="form-control kgs" name="kgs[${rowCount}]" required></td>
            <td><input type="number" class="form-control cones" name="cones[${rowCount}]" required></td>
            <td><input type="number" class="form-control karung" name="karung[${rowCount}]" required></td>
            <td class="text-center">
                <button type="button" class="btn btn-danger removeRow"><i class="fas fa-trash"></i></button>
            </td>
        `;
                tbody.appendChild(newRow);

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
        newTabPane.querySelector(".add-more").addEventListener("click", addNewTab);
        // Fungsi untuk membuat tab baru
        function addNewTab() {
            // ID untuk tab dan konten baru
            const newTabId = `nav-tab-${tabIndex}`;
            const newContentId = `nav-content-${tabIndex}`;
            const newPoTableId = `poTable-${tabIndex}`;
            const totalKarungId = `total_karung_${tabIndex}`;
            const totalGwId = `total_gw_kirim_${tabIndex}`;
            const totalKgsId = `total_kgs_kirim_${tabIndex}`;
            const totalConesId = `total_cones_kirim_${tabIndex}`;
            const totalLotId = `total_lot_kirim_${tabIndex}`;

            const newInputId = `no_model_${tabIndex}`;
            const id_celup = `id_celup_${tabIndex}`;
            const itemTypeId = `item_type_${tabIndex}`;
            const kodeWarnaId = `kode_warna_${tabIndex}`;
            const warnaId = `warna_${tabIndex}`;
            const lotCelupId = `lot_celup_${tabIndex}`;

            // Tambahkan tab baru ke nav-tab
            const newTabButton = document.createElement("button");
            newTabButton.className = "nav-link";
            newTabButton.id = `${newTabId}-button`;
            newTabButton.dataset.bsToggle = "tab";
            newTabButton.dataset.bsTarget = `#${newContentId}`;
            newTabButton.type = "button";
            newTabButton.role = "tab";
            newTabButton.ariaControls = newContentId;
            newTabButton.ariaSelected = "false";
            newTabButton.textContent = tabIndex;

            // Tambahkan tab button ke nav-tab
            navTab.appendChild(newTabButton);

            // Tambahkan konten baru ke tab-content
            const newTabPane = document.createElement("div");
            newTabPane.className = "tab-pane fade";
            newTabPane.id = newContentId;
            newTabPane.role = "tabpanel";
            newTabPane.ariaLabelledby = `${newTabId}-button`;

            // Tambahkan elemen `input-group` ke tab baru
            newTabPane.innerHTML = `
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <!-- Form Items -->
                        <div class="kebutuhan-item">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label>No Model</label>
                                    <select class="form-control" id="id_order" name="id_order[0]" required>
                                        <option value="">Pilih No Model </option>
                                        <?php foreach ($no_model as $item): ?>
                                            <option value="<?= $item['id_order'] ?>"><?= $item['no_model'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" class="form-control" id="no_model" name="no_model[0]" hidden>
                                </div>
                                <div class="col-md-3">
                                    <label>Item Type</label>
                                    <select class="form-select" name="item_type[0]" id="item_type" required>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Kode Warna</label>
                                    <select class="form-select" name="kode_warna[0]" id="kode_warna" required>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Warna</label>
                                    <input type="text" class="form-control" name="warna[0]" id="warna" required placeholder="Warna" readonly>
                                </div>

                            </div>

                            <!-- Surat Jalan Section -->
                            <div class="row g-3 mt-3">
                                <div class="col-md-3">
                                    <label>Harga</label>
                                    <input type="float" class="form-control" name="harga[0]" id="harga" placeholder="Harga" required>
                                </div>

                                <div class="col-md-3">
                                    <label>Lot</label>
                                    <input type="float" class="form-control" name="lot[0]" id="lot" placeholder="Lot" required>
                                </div>
                                <div class="col-md-2">
                                    <label>LMD</label>
                                    <select class="form-control" name="l_m_d" id="l_m_d[0]" placeholder="L/M/D" required>
                                        <option value="">Pilih LMD</option>
                                        <option value="L">L</option>
                                        <option value="M">M</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>GW</label>
                                    <input type="text" class="form-control" name="gw[0]" id="gw" required placeholder="gw">
                                </div>
                                <div class="col-md-1">
                                    <label for="ganti-retur" class="text-center">Ganti Retur</label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>
                                                <input type="hidden" name="ganti_retur[0]" value="0">
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
                                                <th width=200 class="text-center">Cluster</th>
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
                                                    <select class="form-select text-center nama_cluster" name="nama_cluster[0][0]">
                                                        <option value="">Pilih Cluster</option>
                                                        <?php foreach ($cluster as $c) { ?>
                                                            <option value="<?= $c['nama_cluster'] ?>"><?= $c['nama_cluster'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td><input type="float" class="form-control kgs" name="kgs[0][0]" required></td>
                                                <td><input type="float" class="form-control cones" name="cones[0][0]" required></td>
                                                <td><input type="float" class="form-control karung" name="karung[0][0]" required></td>
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
                                                <th class="text-center"></th>
                                                <th class="text-center">Total NW</th>
                                                <th class="text-center">Total Cones</th>
                                                <th class="text-center">Total Karung</th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><input type="float" class="form-control" id="total_kgs" name="total_kgs" placeholder="NW" readonly></td>
                                                <td><input type="float" class="form-control" id="total_cones" name="total_cones" placeholder="Cones" readonly></td>
                                                <td><input type="float" class="form-control" id="total_karung" name="total_karung" placeholder="Karung" readonly></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- Buttons -->
                            <div class="row mt-3">
                                <div class="col-12 text-center mt-2">
                                    <button class="btn btn-icon btn-3 btn-outline-info add-more" type="button">
                                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                                    </button>
                                    <button class="btn btn-icon btn-3 btn-outline-danger remove-tab" type="button">
                                        <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            `;
            navTabContent.appendChild(newTabPane);
            document.getElementById(newContentId).querySelectorAll('.slc2').forEach(el => {
                $(el).select2({
                    width: '100%'
                });

                $(el).on("select2:select", function() {
                    let idcelup = $(this).val(); // Ambil value yang dipilih di select2

                    $.ajax({
                        url: "<?= base_url($role . '/createBon/getItem/') ?>" + idcelup,
                        type: "POST",
                        data: {
                            id: idcelup
                        }, // Kirim dalam format object
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            // Update elemen input dan select di dalam tab baru
                            document.getElementById(id_celup).value = idcelup;
                            document.getElementById(newInputId).value = data.no_model;
                            document.getElementById(itemTypeId).innerHTML = `<option value="${data.item_type}" selected>${data.item_type}</option>`;
                            document.getElementById(kodeWarnaId).innerHTML = `<option value="${data.kode_warna}" selected>${data.kode_warna}</option>`;
                            document.getElementById(warnaId).innerHTML = `<option value="${data.warna}" selected>${data.warna}</option>`;
                            document.getElementById(lotCelupId).value = data.lot_celup;
                            valueLot = data.lot_celup;
                        }
                    });
                });
            });

            // Pindahkan ke tab baru
            const bootstrapTab = new bootstrap.Tab(newTabButton);
            bootstrapTab.show();

            // Event listener tombol
            newTabPane.querySelector(".add-more").addEventListener("click", addNewTab);

            newTabPane.querySelector(".remove-tab").addEventListener("click", function() {
                removeTab(newTabButton, newTabPane);
            });
            // Pasang event listener pada input baru
            newTabPane.querySelectorAll("input").forEach(input => {
                input.addEventListener("input", () => {
                    calculateTotals(newTabPane.querySelector(`#${newPoTableId}`));
                });
            });
            // Add row functionality
            const addRowButton = newTabPane.querySelector("#addRow");
            const removeRowButton = newTabPane.querySelector("#removeRow");
            const newPoTable = newTabPane.querySelector(`#${newPoTableId}`);
            const makan = tabIndex - 1;
            console.log(makan);
            addRowButton.addEventListener("click", function() {
                const rowCount = newPoTable.querySelectorAll("tbody tr").length + 1;
                const newRow = document.createElement("tr");

                newRow.innerHTML = `
                    <td><input type="text" class="form-control text-center" name="no_karung[${tabIndex-2}][${rowCount-1}]" value="${rowCount}" readonly></td>
                    <td><input type="float" class="form-control gw_kirim_input" name="gw_kirim[${tabIndex-2}][${rowCount-1}]" required></td>
                    <td><input type="float" class="form-control kgs_kirim_input" name="kgs_kirim[${tabIndex-2}][${rowCount-1}]" required></td>
                    <td><input type="float" class="form-control cones_kirim_input" name="cones_kirim[${tabIndex-2}][${rowCount-1}]" required></td>
                    <td><input type="float" class="form-control lot_celup_input" name="lot_celup[${tabIndex-2}][${rowCount-1}]" value="${valueLot}" id="${lotCelupId}" required></td>
                    <td class="text-center">
                    <button type="button" class="btn btn-danger removeRow"><i class="fas fa-trash"></i></button>
                    </td>
                    `;

                newPoTable.querySelector("tbody").appendChild(newRow);

                // Tambahkan event listener untuk tombol hapus (removeRow) pada baris baru
                newRow.querySelector(".removeRow").addEventListener("click", function() {
                    newRow.remove();
                    updateRowNumbers(newPoTable);
                    calculateTotals(newPoTable); // Perbarui total setelah baris dihapus
                });
                // Recalculate totals when new row is added
                newRow.querySelectorAll('input').forEach(input => {
                    input.addEventListener('input', function() {
                        calculateTotals(newPoTable);
                    });
                });
                // calculateTotals(newPoTable);
                calculateTotals(newTabPane.querySelector(`#${newPoTableId}`));
            });

            // Event listeners for input changes
            newPoTable.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', function() {
                    calculateTotals(newPoTable);
                });
            });



            tabIndex++;
            calculateTotals(newPoTable);
        }
    </script>

    <?php $this->endSection(); ?>