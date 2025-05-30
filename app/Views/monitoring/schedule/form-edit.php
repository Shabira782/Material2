<?php $this->extend($role . '/schedule/header'); ?>
<?php $this->section('content'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
    }

    .locked-input {
        pointer-events: none;
        background-color: #e9ecef;
    }

    .table-responsive {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: rgb(0, 77, 94);
        color: #ffffff;
        font-weight: 600;
        text-transform: uppercase;
        padding: 15px;
    }

    .table tbody td {
        padding: 15px;
        vertical-align: middle;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 15px;
    }

    .form-control,
    .form-select {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 8px 12px;
        width: 100%;
        transition: border-color 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: rgb(0, 147, 152);
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }

    .form-check-input {
        height: 30px;
    }


    /* Button Styles */
    .btn {
        padding: 10px 20px;
        border-radius: 4px;
        font-weight: 600;
        text-transform: uppercase;
        transition: all 0.3s ease;
    }



    .btn-danger {
        background-color: #e74c3c;
        border-color: #e74c3c;
    }

    .btn-danger:hover {
        background-color: #c0392b;
        border-color: #c0392b;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Schedule Celup</h3>
                    <div class="card-tools">
                        <h6 class="badge bg-info text-white">Tanggal Schedule : <?= $tanggal_schedule ?> | Lot Urut : <?= $lot_urut ?></h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <form action="<?= base_url(session('role') . '/schedule/updateSchedule') ?>" method="post">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="no_mesin" class="form-label">No Mesin</label>
                                        <input type="text" class="form-control" id="no_mesin" name="no_mesin" value="<?= $no_mesin ?>" readonly>
                                        <input type="hidden" name="tanggal_schedule" value="<?= $tanggal_schedule ?>">
                                        <input type="hidden" name="lot_urut" value="<?= $lot_urut ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="min_caps" class="form-label">Min Caps</label>
                                        <input type="number" class="form-control" id="min_caps" name="min_caps" value="<?= $min_caps ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="max_caps" class="form-label">Max Caps</label>
                                        <input type="number" class="form-control" id="max_caps" name="max_caps" value="<?= $max_caps ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="sisa_kapasitas" class="form-label">Sisa Kapasitas</label>
                                        <input type="number" class="form-control" id="sisa_kapasitas" name="sisa_kapasitas" value="<?= $max_caps ?>" data-max-caps="<?= $max_caps ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="jenis_bahan_baku" class="form-label">Jenis Bahan Baku</label>
                                        <input type="text" class="form-control" id="jenis_bahan_baku" name="jenis_bahan_baku" value="<?= $jenis ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="kode_warna" class="form-label">Kode Warna</label>
                                        <input type="text" class="form-control" id="kode_warna" name="kode_warna" value="<?= $kode_warna ?>" required readonly>
                                        <div id="suggestionsKWarna" class="suggestions-box" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="warna" class="form-label">Warna</label>
                                        <input type="text" class="form-control" id="warna" name="warna" value="<?= $warna ?>" maxlength="32" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="poTable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Order</th>
                                                    <th class="text-center">
                                                        <button type="button" class="btn btn-info" id="addRow">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1; ?>
                                                <?php foreach ($scheduleData as $detail): ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <?= $no++ ?>
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <label for="itemtype"> Item Type</label>
                                                                        <select class="form-select item-type" name="item_type[]" required>
                                                                            <?php foreach ($scheduleData as $item): ?>
                                                                                <option value="<?= $item['item_type'] ?>" <?= ($item['item_type'] == $detail['item_type']) ? 'selected' : '' ?>><?= $item['item_type'] ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                        <input type="hidden" name="id_celup[]" value="<?= $detail['id_celup'] ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <label for="po"> PO</label>
                                                                        <select class="form-select po-select" name="po[]" required>
                                                                            <?php foreach ($scheduleData as $po): ?>
                                                                                <option value="<?= $po['no_model'] ?>" <?= ($po['no_model'] == $detail['no_model']) ? 'selected' : '' ?>><?= $po['no_model'] ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                        <label for="tgl_start_mc"> Tgl Start MC</label>
                                                                        <input type="date" class="form-control" name="tgl_start_mc[]" value="<?= $detail['start_mc'] ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                        <label for="delivery_awal"> Delivery Awal</label>
                                                                        <input type="date" class="form-control" name="delivery_awal[]" value="<?= $detail['delivery_awal'] ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                        <label for="delivery_akhir"> Delivery Akhir</label>
                                                                        <input type="date" class="form-control" name="delivery_akhir[]" value="<?= $detail['delivery_akhir'] ?>" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                        <label for="qty_po"> Qty PO</label>
                                                                        <input type="number" class="form-control" name="qty_po[]" value="<?= number_format($detail['qty_po'], 2) ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                        <label for="qty_po_plus"> Qty PO (+)</label>
                                                                        <input type="number" class="form-control" name="qty_po_plus[]" value="" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <label for="qty_celup">Qty Celup</label>
                                                                    <input type="number" class="form-control" step="0.01" min="0.01" name="qty_celup[]" value="<?= $detail['qty_celup'] ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-3">
                                                                    <div class="form-group">
                                                                        <label for="qty_celup">KG Kebutuhan :</label>
                                                                        <br />
                                                                        <span class="badge bg-info">
                                                                            <span class="kg_kebutuhan"><?= $detail['kg_kebutuhan'] ?></span> KG
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-3">
                                                                    <div class="form-group">
                                                                        <label for="sisa_jatah">Sisa Jatah :</label>
                                                                        <br />
                                                                        <span class="badge bg-info">
                                                                            <span class="sisa_jatah" data-sisajatah="<?= number_format($detail['sisa_jatah'], 2) ?>"><?= number_format($detail['sisa_jatah'], 2) ?></span> KG
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-3">
                                                                    <label for="last_status">Last Status</label>
                                                                    <br />
                                                                    <span class="badge bg-<?= $detail['last_status'] == 'scheduled' ? 'info' : ($detail['last_status'] == 'celup' ? 'warning' : 'success') ?>"><?= $detail['last_status'] ?></span>
                                                                    <input type="hidden" class="form-control last_status" name="last_status[]" value="<?= $detail['last_status'] ?>">
                                                                </div>
                                                                <div class="col-3 d-flex align-items-center">
                                                                    <div class="form-group">
                                                                        <label for="po_plus">PO +</label>
                                                                        <input type="checkbox" id="po_plus" class="form-control form-check-input" name="po_plus[]" value="1" <?= $detail['po_plus'] == 1 ? 'checked' : '' ?>>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-info editRow" data-id="<?= $detail['id_celup'] ?>" data-tanggalschedule="<?= $tanggal_schedule ?>">
                                                                <i class="fas fa-calendar-alt"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-danger removeRow">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td class="text-center">
                                                        <strong>Total Qty Celup</strong>
                                                    </td>
                                                    <td colspan="8" class="text-center">
                                                        <input type="number" class="form-control" id="total_qty_celup" name="total_qty_celup" value="" readonly>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-info w-100">Update Jadwal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Tanggal Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_celup" id="idCelup">
                    <div class="form-group mb-3">
                        <label for="tanggal_schedule" class="form-label">Tanggal Schedule</label>
                        <input type="date" class="form-control" id="tanggal_schedule" name="tanggal_schedule" value="<?= $tanggal_schedule ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const kodeWarna = document.getElementById('kode_warna');
        const suggestionsBoxKWarna = document.getElementById('suggestionsKWarna');
        const warnaInput = document.getElementById('warna');
        const poTable = document.getElementById("poTable");

        // Buat semua input dan select jadi readonly/disabled jika last_status 'celup', 'done', atau 'sent'
        const lastStatuses = document.querySelectorAll('.last_status');
        lastStatuses.forEach(status => {
            if (status.value === 'celup' || status.value === 'done' || status.value === 'sent') {
                const row = status.closest('tr');
                const elements = row.querySelectorAll('input, select, button');
                elements.forEach(el => {
                    // Untuk input, gunakan readonly; untuk select dan button, gunakan disabled
                    if (el.tagName.toLowerCase() === 'input') {
                        el.setAttribute('readonly', true);
                    } else {
                        el.setAttribute('disabled', true);
                    }
                    el.classList.add('locked-input');
                });
            }
        });

        // Event delegation untuk tombol Edit (gunakan closest untuk menangani klik pada ikon di dalam tombol)
        document.addEventListener('click', function(e) {
            const button = e.target.closest('.editRow');
            if (button) {
                const idCelup = button.dataset.id;
                const modalEl = document.getElementById('editModal');
                if (!modalEl) {
                    console.error('Modal edit tidak ditemukan!');
                    return;
                }
                const modal = new bootstrap.Modal(modalEl);
                document.getElementById('idCelup').value = idCelup;
                document.getElementById('tanggal_schedule').value = button.dataset.tanggalschedule;
                modal.show();
            }
        });

        // Ajax Submit dengan Error Handling untuk modal edit
        $('#editModal form').submit(function(e) {
            e.preventDefault();
            const formData = {
                id_celup: $('#idCelup').val(),
                tanggal_schedule: $('#tanggal_schedule').val(),
                no_mesin: $('#no_mesin').val(),
                lot_urut: $('#lot_urut').val()
            };

            $.ajax({
                    url: '<?= base_url(session('role') . '/schedule/updateTglSchedule') ?>',
                    type: 'POST',
                    data: formData,
                    dataType: 'json'
                })
                .done(response => {
                    if (response.success) {
                        alert('Update berhasil!');
                        window.location.href = '<?= base_url(session('role') . '/schedule') ?>';
                    } else {
                        alert('Gagal: ' + (response.message || 'Terjadi kesalahan'));
                    }
                })
                .fail((xhr, status, error) => {
                    alert(`Error: ${error}`);
                    console.error('Detail error:', xhr.responseText);
                });
        });

        // Event handler untuk input kode_warna
        if (kodeWarna) {
            kodeWarna.addEventListener('input', function() {
                const query = kodeWarna.value.trim();
                if (query.length >= 3) {
                    fetchData('getKodeWarna', {
                        query
                    }, displayKodeWarnaSuggestions);
                    fetchData('getWarna', {
                        kode_warna: query
                    }, (data) => {
                        if (data.length > 0) {
                            warnaInput.value = data[0].color;
                            // Jika ada lebih dari satu select item-type, update semuanya
                            document.querySelectorAll('.item-type').forEach(select => {
                                fetchItemType(query, data[0].color, select);
                            });
                        }
                    });
                } else {
                    suggestionsBoxKWarna.style.display = 'none';
                }
            });
        }

        // Fungsi utilitas untuk fetching data
        function fetchData(endpoint, params, callback) {
            const url = new URL(`<?= base_url(session('role') . "/schedule/") ?>${endpoint}`);
            Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(callback)
                .catch(error => console.error(`Error fetching ${endpoint}:`, error));
        }

        // Fungsi menampilkan saran kode warna
        function displayKodeWarnaSuggestions(suggestions) {
            suggestionsBoxKWarna.innerHTML = '';
            if (suggestions.length > 0) {
                suggestionsBoxKWarna.style.display = 'block';
                suggestions.forEach(suggestion => {
                    const suggestionDiv = document.createElement('div');
                    suggestionDiv.textContent = suggestion;
                    suggestionDiv.addEventListener('click', () => {
                        kodeWarna.value = suggestion;
                        suggestionsBoxKWarna.style.display = 'none';
                    });
                    suggestionsBoxKWarna.appendChild(suggestionDiv);
                });
            } else {
                suggestionsBoxKWarna.style.display = 'none';
            }
        }

        // Fungsi fetch item type; jika targetSelect diberikan, maka dropdown akan di-update
        function fetchItemType(kodeWarna, warna, targetSelect) {
            fetchData('getItemType', {
                kode_warna: kodeWarna,
                warna
            }, (data) => {
                if (targetSelect) {
                    populateSelect(targetSelect, data, 'item_type', 'item_type');
                }
            });
        }

        // Fungsi utilitas untuk mengisi select dropdown
        function populateSelect(selectElement, data, valueKey, textKey) {
            selectElement.innerHTML = `<option value="">Pilih ${textKey}</option>`;
            if (data.length > 0) {
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item[valueKey];
                    option.textContent = item[textKey];
                    selectElement.appendChild(option);
                });
            } else {
                selectElement.innerHTML = '<option value="">Tidak ada data</option>';
            }
        }

        // (Optional) Fungsi fetch PO by kode warna, warna, dan item type
        function fetchPOByKodeWarna(kodeWarna, warna, itemType, poSelect) {
            fetchData('getPO', {
                kode_warna: kodeWarna,
                warna,
                item_type: itemType
            }, (data) => {
                populateSelect(poSelect, data, 'id_order', 'no_model');
                console.log(data);
            });
        }

        // ✅ Fungsi untuk menghitung total_qty_celup dan memeriksa max_caps serta tagihan SCH
        function calculateTotalAndRemainingCapacity() {
            const rows = poTable.querySelectorAll("tbody tr");
            let totalQtyCelup = 0;

            // Hitung total qty celup hanya dari baris dengan last_status yang valid
            rows.forEach(row => {
                const lastStatusEl = row.querySelector("input[name='last_status[]']");
                const lastStatus = lastStatusEl ? lastStatusEl.value.trim() : "";
                if (["scheduled", "celup", "reschedule"].includes(lastStatus)) {
                    const qtyCelup = parseFloat(row.querySelector("input[name='qty_celup[]']").value) || 0;
                    totalQtyCelup += qtyCelup;
                }
            });

            // Update total_qty_celup
            const totalQtyCelupElement = document.getElementById("total_qty_celup");
            if (totalQtyCelupElement) {
                totalQtyCelupElement.value = totalQtyCelup.toFixed(2); // Format 2 angka di belakang koma
            }

            // Ambil nilai max_caps
            const maxCaps = parseFloat(document.getElementById("max_caps").value) || 0;

            // Periksa apakah total_qty_celup melebihi max_caps
            if (totalQtyCelup > maxCaps) {
                alert("⚠️ Total Qty Celup melebihi Max Caps!");
                totalQtyCelupElement.classList.add("is-invalid");
                totalQtyCelupElement.focus();
            } else {
                totalQtyCelupElement.classList.remove("is-invalid");
            }

            // Periksa apakah qty_celup di setiap baris yang memiliki last_status valid melebihi tagihan SCH di baris tersebut
            rows.forEach(row => {
                const lastStatusEl = row.querySelector("input[name='last_status[]']");
                const lastStatus = lastStatusEl ? lastStatusEl.value.trim() : "";
                if (["scheduled", "celup", "reschedule"].includes(lastStatus)) {
                    const qtyCelupInput = row.querySelector("input[name='qty_celup[]']");
                    const qtyCelup = parseFloat(qtyCelupInput.value) || 0;
                    const tagihanSCH = parseFloat(row.querySelector(".sisa_jatah").textContent) || 0;

                    if (qtyCelup > tagihanSCH) {
                        alert(`⚠️ Qty Celup di baris ini melebihi Tagihan SCH! (Tagihan SCH: ${tagihanSCH.toFixed(2)})`);
                        qtyCelupInput.classList.add("is-invalid");
                        qtyCelupInput.focus();
                        // Reset qty celup
                        qtyCelupInput.value = '';
                    } else {
                        qtyCelupInput.classList.remove("is-invalid");
                    }
                }
            });

            // Update sisa kapasitas
            const sisaKapasitasElement = document.getElementById("sisa_kapasitas");
            if (sisaKapasitasElement) {
                const sisaKapasitas = maxCaps - totalQtyCelup;
                sisaKapasitasElement.value = sisaKapasitas.toFixed(2); // Format 2 angka di belakang koma
                if (sisaKapasitas < 0) {
                    alert("⚠️ Sisa Kapasitas negatif!");
                    sisaKapasitasElement.classList.add("is-invalid");
                    sisaKapasitasElement.focus();
                } else {
                    sisaKapasitasElement.classList.remove("is-invalid");
                }
            }
        }

        // ✅ Event listener untuk menghitung total qty celup dan sisa kapasitas
        poTable.addEventListener("input", function(e) {
            if (e.target.name === "qty_celup[]") {
                calculateTotalAndRemainingCapacity();
            }
        });


        // Event delegation untuk perubahan pada dropdown PO
        if (poTable) {
            poTable.addEventListener("change", function(e) {
                if (e.target.classList.contains("po-select")) {
                    const poSelect = e.target;
                    const tr = poSelect.closest("tr");
                    const itemTypeValue = tr.querySelector("select[name^='item_type']").value;
                    const kodeWarnaValue = document.querySelector("input[name='kode_warna']").value;

                    if (poSelect.value && itemTypeValue && kodeWarnaValue) {
                        fetchPODetails(poSelect.value, tr, itemTypeValue, kodeWarnaValue);
                    } else {
                        resetPODetails(tr);
                    }
                }
            });
        }

        // Fungsi Fetch Detail PO
        function fetchPODetails(poNo, tr, itemType, kodeWarna) {
            const url = `<?= base_url(session('role') . "/schedule/getPODetails") ?>?id_order=${poNo}&item_type=${encodeURIComponent(itemType)}&kode_warna=${encodeURIComponent(kodeWarna)}`;
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    console.log("Data received from server:", data); // Log data yang diterima dari server

                    if (data && !data.error) {
                        const tglStartMC = tr.querySelector("input[name='tgl_start_mc[]']");
                        const deliveryAwal = tr.querySelector("input[name='delivery_awal[]']");
                        const deliveryAkhir = tr.querySelector("input[name='delivery_akhir[]']");
                        const qtyPO = tr.querySelector("input[name='qty_po[]']");
                        const qtyPOPlus = tr.querySelector("input[name='qty_po_plus[]']");
                        const kgKebutuhan = tr.querySelector(".kg_kebutuhan");
                        const sisaJatah = tr.querySelector(".sisa_jatah");

                        tglStartMC.value = data.start_mesin || '';
                        deliveryAwal.value = data.delivery_awal || '';
                        deliveryAkhir.value = data.delivery_akhir || '';
                        if (!qtyPO.value) {
                            qtyPO.value = parseFloat(data.kg_kebutuhan).toFixed(2);
                        } else {
                            qtyPO.value = parseFloat(qtyPO.value).toFixed(2);
                        }
                        qtyPOPlus.value = parseFloat(data.qty_po_plus).toFixed(2) || '';
                        kgKebutuhan.textContent = parseFloat(data.kg_kebutuhan).toFixed(2) || '0.00';
                        sisaJatah.textContent = parseFloat(data.sisa_jatah).toFixed(2) || '0.00';
                    } else {
                        console.error('Error fetching PO details:', data.error || 'No data found');
                    }
                })
                .catch(error => {
                    console.error('Error fetching PO details:', error);
                });
        }

        // Fungsi reset detail PO
        function resetPODetails(tr) {
            const fields = ['tgl_start_mc[]', 'delivery_awal[]', 'delivery_akhir[]', 'qty_po[]', 'qty_po_plus[]'];
            fields.forEach(field => {
                const element = tr.querySelector(`input[name="${field}"]`);
                if (element) element.value = '';
            });

            const spans = tr.querySelectorAll('.kg_kebutuhan, .sisa_jatah');
            spans.forEach(span => span.textContent = '0.00');
        }

        // Inisialisasi perhitungan kapasitas saat load halaman
        calculateTotalAndRemainingCapacity();


        // ✅ Event delegation untuk menambah baris baru
        document.getElementById("addRow").addEventListener("click", function() {
            const tbody = poTable.querySelector("tbody");
            const newRow = document.createElement("tr");
            newRow.innerHTML = `
<td class="text-center">${tbody.rows.length + 1}</td>
<td>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="itemtype"> Item Type</label>
                <select class="form-select item-type" name="item_type[]" required>
                    <option value="">Pilih Item Type</option>
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="po">PO</label>
                <select class="form-select po-select" name="po[]" required>
                    <option value="">Pilih PO</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label for="tgl_start_mc">Tgl Start MC</label>
                <input type="date" class="form-control" name="tgl_start_mc[]" readonly>
            </div>
        </div>

        <div class="col-4">
            <div class="form-group">
                <label for="delivery_awal">Delivery Awal</label>
                <input type="date" class="form-control" name="delivery_awal[]" readonly>
            </div>
        </div>
        <div class="col-4">
            <div class="form-group ">
                <label for="delivery_akhir">Delivery Akhir</label>
                <input type="date" class="form-control" name="delivery_akhir[]" readonly>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label for="qty_po">Qty PO</label>
                <input type="number" class="form-control" name="qty_po[]" readonly>
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label for="qty_po_plus">Qty PO (+)</label>
                <input type="number" class="form-control" name="qty_po_plus[]" readonly>
            </div>
        </div>
        <div class="col-4">
            <label for="qty_celup">Qty Celup</label>
            <input type="number" class="form-control" name="qty_celup[]" value="" required>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label for="qty_celup">KG Kebutuhan :</label>
                <br />
                <span class="badge bg-info">
                    <span class="kg_kebutuhan">0.00</span> KG
                </span>
            </div>
        </div>
        <div class="col-3">
            <div class="form-group">
                <label for="ss">Sisa Jatah :</label>
                <br />
                <span class="badge bg-info">
                    <span class="sisa_jatah">0.00</span> KG
                </span>
            </div>
        </div>
        <div class="col-3">
            <label for="last_status">Last Status</label>
            <br />
            <span class="badge bg-info">scheduled</span>
            <input type="hidden" class="form-control last_status" name="last_status[]" value="scheduled">
        </div>
        <div class="col-3 d-flex align-items-center">
            <div class="form-group">
                <label for="po_plus">PO +</label>
                <input type="checkbox" id="po_plus" class="form-control form-check-input" name="po_plus[]" value="1">
            </div>
        </div>

    </div>

</td>
<td class="text-center">
    <button type="button" class="btn btn-danger removeRow">
        <i class="fas fa-trash"></i>
    </button>
</td>
`;
            tbody.appendChild(newRow);

            // Ambil elemen item-type di baris baru
            const itemTypeSelect = newRow.querySelector(".item-type");

            // Fetch item type hanya untuk item-type di baris baru
            fetchItemType(kodeWarna.value, warnaInput.value, itemTypeSelect);

            // Tambahkan event listener untuk perubahan item type
            itemTypeSelect.addEventListener("change", function() {
                const itemTypeValue = this.value;
                const poSelect = newRow.querySelector(".po-select");
                if (itemTypeValue) {
                    fetchPOByKodeWarna(kodeWarna.value, warnaInput.value, itemTypeValue, poSelect);
                }
            });
        });


        document.querySelector("#poTable").addEventListener("click", function(event) {
            if (event.target.closest(".removeRow")) {
                const row = event.target.closest("tr");
                const table = document.querySelector("#poTable tbody");

                // Validasi jika ada input ID Celup pada baris
                const idCelupInput = row.querySelector('input[name^="id_celup["]');
                const idCelup = idCelupInput ? idCelupInput.value : null;

                if (idCelup) {
                    // SweetAlert konfirmasi sebelum menghapus
                    Swal.fire({
                        title: "Apakah Anda Yakin?",
                        text: "Data Schedule akan dihapus",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Ya, hapus!",
                        cancelButtonText: "Batal",
                        dangerMode: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Kirim permintaan AJAX untuk menghapus data
                            $.ajax({
                                url: '<?= base_url(session('role') . '/schedule/deleteSchedule') ?>',
                                type: 'POST',
                                data: {
                                    id_celup: idCelup
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire("Berhasil!", "Data Schedule berhasil dihapus.", "success").then(() => {
                                            row.remove();
                                            if (table.rows.length === 0) {
                                                window.location.href = '<?= base_url(session('role') . '/schedule') ?>';
                                            } else {
                                                calculateTotalAndRemainingCapacity();
                                            }
                                        });
                                    } else {
                                        Swal.fire("Gagal!", "Data Schedule gagal dihapus.", "error");
                                    }
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire("Error!", "Terjadi kesalahan pada server.", "error");
                                },
                            });
                        }
                    });
                } else {
                    // Jika id_celup tidak ditemukan atau null, cukup hapus barisnya saja
                    Swal.fire({
                        title: "Hapus Baris?",
                        text: "Baris ini akan dihapus tanpa mengirim data ke server.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Ya, hapus!",
                        cancelButtonText: "Batal",
                        dangerMode: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            row.remove();
                            if (table.rows.length === 0) {
                                window.location.href = '<?= base_url(session('role') . '/schedule') ?>';
                            } else {
                                calculateTotalAndRemainingCapacity();
                            }
                        }
                    });
                }
            }
        });
    });
</script>
<?php $this->endSection(); ?>