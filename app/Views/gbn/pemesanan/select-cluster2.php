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
            <div class="table-responsive">
                <table id="dataTable" class="display text-center text-uppercase text-xs font-bolder" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">No Model</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Item Type</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Kode Warna</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Warna</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Jalan Mc</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Total Kgs Pesan</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Total Cns Pesan</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Lot Pesan</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Keterangan Area</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Pilih</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Cluster</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Kg Stock</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Cns Stock</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Krg Stock</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Lot Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pemesanan as $key => $id) {
                            if ($id['no_model'] != @$pemesanan[$key - 1]['no_model'] || $id['item_type'] != @$pemesanan[$key - 1]['item_type'] || $id['kode_warna'] != @$pemesanan[$key - 1]['kode_warna'] || $id['warna'] != @$pemesanan[$key - 1]['warna']) {
                                $no_model = $id['no_model'];
                                $item_type = $id['item_type'];
                                $kode_warna = $id['kode_warna'];
                                $warna = $id['warna'];
                            } else {
                                $no_model = "";
                                $item_type = "";
                                $kode_warna = "";
                                $warna = "";
                            } ?>
                            <tr>
                                <td><?= $no_model ?></td>
                                <td><?= $item_type ?></td>
                                <td><?= $kode_warna ?></td>
                                <td><?= $warna ?></td>
                                <td><?= $id['ttl_jl_mc'] ?></td>
                                <td><?= $id['ttl_kg'] ?></td>
                                <td><?= $id['ttl_cns'] ?></td>
                                <td><?= $id['lot'] ?></td>
                                <td><?= $id['keterangan'] ?></td>
                                <td><input type="checkbox" class="form-checkbox"></td>
                                <td><?= $id['nama_cluster'] ?></td>
                            </tr>
                        <?php
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<?php $this->endSection(); ?>