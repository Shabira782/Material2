<?php $this->extend($role . '/pemesanan/header'); ?>
<?php $this->section('content'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.min.css">
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

    <!-- Modal Detail Stok -->
    <div class="modal fade" id="modalStock" tabindex="-1" aria-labelledby="modalStockLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalStockLabel">Detail Stok</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="stockForm">
                        <div id="stockData" class="row g-3"></div>
                        <button type="submit" class="btn bg-gradient-info mt-3 text-end">Pilih Stok</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Button Import -->
    <div class="card card-frame">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bolder">Data Pemesanan <?= $jenis; ?> <?= $area; ?> <?= $tglPakai; ?></h5>
                <a href="<?= base_url($role . '/selectClusterWarehouse2/' . $area . '/' . $jenis . '/' . $tglPakai) ?>"
                    class="btn bg-gradient-info">
                    <i class="fas fa-layer-group"></i>Pilih CLuster
                </a>
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
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Tanggal Pakai Area</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Area</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">No Model</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Item Type</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Kode Warna</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Warna</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Jalan Mc</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Total Kgs Pesan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Total Cns Pesan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Lot Pesan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Keterangan Area</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Po Tambahan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Kg/Krg Kirim</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Lot Kirim</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Cluster Out</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Send</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($dataPemesanan)): ?>
                                <?php
                                $no = 1;
                                foreach ($dataPemesanan as $data): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $data['tgl_pakai'] ?></td>
                                        <td><?= $data['area'] ?></td>
                                        <td><?= $data['no_model'] ?></td>
                                        <td><?= $data['item_type'] ?></td>
                                        <td><?= $data['kode_warna'] ?></td>
                                        <td><?= $data['color'] ?></td>
                                        <td><?= $data['ttl_jl_mc'] ?></td>
                                        <td><?= $data['ttl_kg'] ?></td>
                                        <td><?= $data['ttl_cns'] ?></td>
                                        <td><?= $data['lot_pesan'] ?></td>
                                        <td><?= $data['ket_pesan'] ?></td>
                                        <td><?= $data['po_tambahan'] ?></td>
                                        <td><?= !empty($data['kgs_out']) ? number_format($data['kgs_out'], 2) . ' / ' . $data['krg_out'] : '' ?></td>
                                        <td><?= $data['lot_kirim'] ?></td>
                                        <td><?= $data['cluster_kirim'] ?></td>
                                        <td>
                                            <!-- button pesan ke covering -->
                                            <?php if ($data['jenis'] === 'SPANDEX' || $data['jenis'] === 'KARET'): ?>
                                                <?php if (!$data['sudah_pesan_spandex']): ?>
                                                    <a href="<?= base_url($role . '/pesanKeCovering/' . $data['id_total_pemesanan']) ?>"
                                                        class="btn bg-gradient-info">
                                                        <i class="fas fa-layer-group"></i> Pesan <?= ucfirst($data['jenis']) ?>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="badge bg-info"><?= $data['status'] ?></span>
                                                <?php endif; ?>
                                                <?php else:
                                                if (!empty($data['id_pengeluaran'])) {

                                                ?>
                                                    <a href="<?= base_url($role . '/pengirimanArea/' . $data['id_total_pemesanan']) ?>"
                                                        class="btn bg-gradient-success">
                                                        <i class="fas fa-paper-plane"></i>Send
                                                    </a>
                                            <?php
                                                }
                                            endif; ?>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (empty($dataPemesanan)) : ?>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <p>No data available in the table.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>



<?php $this->endSection(); ?>