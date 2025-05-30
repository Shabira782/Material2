<?php $this->extend($role . '/pemesanan/header'); ?>
<?php $this->section('content'); ?>

<style>
    .custom-outline {
        color: #344767;
        border-color: #344767;
    }

    .custom-outline:hover {
        background-color: #344767;
        color: white;
    }
</style>

<div class="container-fluid py-4">

    <!-- Button Filter -->
    <div class="card border-0 rounded-top-4 shadow-lg">
        <div class="card-body p-4 rounded-top-4" style="background-color: #344767;">
            <div class="d-flex align-items-center mb-3">
                <i class="fas fa-filter text-white me-3 fs-4"></i>
                <h4 class="mb-0 fw-bold" style="color: white;">Filter Pemesanan</h4>
            </div>
        </div>
        <div class="card-body bg-white rounded-bottom-0 p-4">
            <div class="row gy-4">
                <div class="col-md-6 col-lg-4">
                    <label for="keyInput">Key</label>
                    <input type="text" class="form-control" id="keyInput" placeholder="Area">
                </div>
                <div class="col-md-6 col-lg-4">
                    <label for="">Tanggal Awal (Tanggal Pakai)</label>
                    <input type="date" class="form-control" id="tglAwal">
                </div>
                <div class="col-md-6 col-lg-4">
                    <label for="">Tanggal Akhir (Tanggal Pakai)</label>
                    <input type="date" class="form-control" id="tglAkhir">
                </div>
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="btn-group" role="group">
                            <button class="btn text-white px-4" id="btnSearch" style="background-color: #344767;">
                                <i class="fas fa-search me-2"></i>Cari
                            </button>
                            <button class="btn btn-outline custom-outline" id="btnReset">
                                <i class="fas fa-redo-alt"></i>
                            </button>
                        </div>
                        <button class="btn btn-info d-none" id="btnExport">
                            <i class="fas fa-file-excel me-2"></i>Ekspor
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="display text-center text-uppercase text-xs font-bolder" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">No</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Foll Up</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">No Model</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">No Order</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Area</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Buyer</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Delivery Awal</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Delivery Akhir</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Order Type</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Item Type</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Kode Warna</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Warna</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Tanggal List</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Tanggal Pesan</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Tanggal Pakai</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Jalan MC</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Cones Pesan</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">KG Pesan</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Sisa KGS MC</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Sisa Cones MC</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Lot</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">PO(+)</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let dataTable = $('#dataTable').DataTable({
            "paging": true,
            "searching": false,
            "ordering": true,
            "info": true,
            "responsive": true,
            "processing": true,
            "serverSide": false
        });

        function loadData() {
            let key = $('#keyInput').val().trim();
            let tanggal_awal = $('#tglAwal').val().trim();
            let tanggal_akhir = $('#tglAkhir').val().trim();

            // Validasi: Jika semua input kosong, tampilkan alert dan hentikan pencarian
            if (key === '' && tanggal_awal === '' && tanggal_akhir === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Apa yang mau dicari cuy?',
                });
                return;
            }

            // Validasi 2: Salah satu tanggal doang yang diisi
            if ((tanggal_awal !== '' && tanggal_akhir === '') || (tanggal_awal === '' && tanggal_akhir !== '')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tanggal Nanggung',
                    text: 'Isi kedua tanggal kalau mau filter berdasarkan tanggal, jangan setengah-setengah cuy.',
                });
                return;
            }

            $.ajax({
                url: "<?= base_url($role . '/pemesanan/filterPemesananArea') ?>",
                type: "GET",
                data: {
                    key: key,
                    tanggal_awal: tanggal_awal,
                    tanggal_akhir: tanggal_akhir
                },
                dataType: "json",
                success: function(response) {
                    dataTable.clear().draw();

                    if (response.length > 0) {
                        $.each(response, function(index, item) {
                            dataTable.row.add([
                                index + 1,
                                item.foll_up,
                                item.no_model,
                                item.no_order,
                                item.area,
                                item.buyer,
                                item.delivery_awal,
                                item.delivery_akhir,
                                item.unit,
                                item.item_type,
                                item.kode_warna,
                                item.color,
                                item.tgl_list,
                                item.tgl_pesan,
                                item.tgl_pakai,
                                item.jl_mc,
                                item.ttl_qty_cones,
                                item.ttl_berat_cones,
                                item.sisa_kgs_mc,
                                item.sisa_cones_mc,
                                item.lot,
                                item.po_tambahan,
                                item.keterangan
                            ]).draw(false);
                        });

                        $('#btnExport').removeClass('d-none'); // Munculkan tombol Export Excel
                    } else {
                        $('#btnExport').addClass('d-none'); // Sembunyikan jika tidak ada data
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        }

        $('#btnSearch').click(function() {
            loadData();
        });

        $('#btnExport').click(function() {
            let key = $('#keyInput').val().trim();
            let tanggal_awal = $('#tglAwal').val().trim();
            let tanggal_akhir = $('#tglAkhir').val().trim();
            window.location.href = "<?= base_url($role . '/pemesanan/exportPemesananArea') ?>?key=" + key + "&tanggal_awal=" + tanggal_awal + "&tanggal_akhir=" + tanggal_akhir;
        });

        dataTable.clear().draw();
    });

    // Fitur Reset
    $('#btnReset').click(function() {
        // Kosongkan input
        $('input[type="text"]').val('');
        $('input[type="date"]').val('');

        // Kosongkan tabel hasil pencarian
        $('#dataTable tbody').html('');

        // Sembunyikan tombol Export Excel
        $('#btnExport').addClass('d-none');
    });
</script>


<?php $this->endSection(); ?>