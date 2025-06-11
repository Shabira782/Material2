<?php $this->extend($role . '/warehouse/header'); ?>
<?php $this->section('content'); ?>
<div class="container-fluid py-4">
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

    <?php if (session()->getFlashdata('error')) : ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '<?= session()->getFlashdata('error') ?>',
                });
            });
        </script>
    <?php endif; ?>
    <div class="row">
        <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4 mt-2">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5>
                            Pengriman Area
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn bg-gradient-info" data-bs-toggle="modal" data-bs-target="#inputManual">Input</button>
                            <form action="<?= base_url($role . '/reset_pengeluaran') ?>" method="post">
                                <button type="submit" class="btn bg-gradient-secondary"><i class="fas fa-redo"></i> Reset Data</button>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="<?= base_url($role . '/pengeluaran_jalur') ?>" method="post">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-3">
                                            <label for="barcode" class="form-control-label">No Model</label>
                                            <input class="form-control" type="text" name="no_model" id="no_model" value="<?= $data[0]['no_model'] ?>">
                                        </div>
                                        <div class="col-3">
                                            <label for="barcode" class="form-control-label">Item Type</label>
                                            <input class="form-control" type="text" name="item_type" id="item_type" value="<?= $data[0]['item_type'] ?>">
                                        </div>
                                        <div class="col-3">
                                            <label for="barcode" class="form-control-label">Kode Warna</label>
                                            <input class="form-control" type="text" name="kode_warna" id="kode_warna" value="<?= $data[0]['kode_warna'] ?>">
                                        </div>
                                        <div class="col-3">
                                            <label for="barcode" class="form-control-label">Warna</label>
                                            <input class="form-control" type="text" name="color" id="color" value="<?= $data[0]['color'] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">

                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
            <div class="card my-1">
                <div class="card-body">
                    <div class="row">
                        <div class="d-flex justify-content-between">
                            <h6>

                            </h6>
                        </div>

                    </div>
                    <form action="<?= base_url($role . '/proses_pengeluaran_jalur') ?>" method="post">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table id="inTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th width=30 class="text-center"><input type="checkbox" name="select_all" id="select_all" value=""></th>
                                                <th class="text-center"><i class="fas fa-cart-shopping"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            $today = date('d-M-Y');
                                            $formated = trim(date('Y-m-d'));

                                            // foreach ($dataOutJalur as $data) {
                                            ?>
                                            <tr>
                                                <td align="center"><input type="checkbox" name="checked_id[]" class="checkbox" value="<?= $no - 1 ?>"> <?= $no++ ?></td>
                                                <td>
                                                    <div class="form-group d-flex justify-content-end">
                                                        <label for="tgl_keluar">Tanggal Keluar : <?= $today ?></label>
                                                        <input type="date" class="form-control" name="tgl_keluar[]" value="<?= $formated ?>" hidden>
                                                    </div>
                                                    <div class="form-group d-flex justify-content-center">
                                                        <label for="nama_cluster"><?= $data[0]['nama_cluster'] ?></label>
                                                        <input type="text" class="form-control" name="nama_cluster[]" value="<?= $data[0]['nama_cluster'] ?>" hidden>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="tgl">Model : </label>
                                                        <input type="text" class="form-control" name="no_model[]" value="<?= $data[0]['no_model'] ?>" readonly>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for="">Kode Benang:</label>
                                                                <input type="text" class="form-control" name="item_type[]" value="<?= $data[0]['item_type'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for="">Kode Warna:</label>
                                                                <input type="text" class="form-control" name="kode_warna[]" value="<?= $data[0]['kode_warna'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for=""> Warna:</label>
                                                                <input type="text" class="form-control" name="warna[]" value="<?= $data[0]['color'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for=""> Lot:</label>
                                                                <input type="text" class="form-control" name="lot_out[]" value="<?= $data[0]['lot_stock'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for=""> Kgs Keluar:</label>
                                                                <input type="number" class="form-control" name="kgs_keluar[]" value="<?= $data[0]['kg_stock'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for="">Cones Keluar:</label>
                                                                <input type="number" class="form-control" name="cns_keluar[]" value="<?= $data[0]['cns_stock'] ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group d-flex justify-content-end">
                                                        <button type="button" class="btn btn-danger removeRow btn-hapus">
                                                            <i class=" fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                            // }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-4 mt-2">
                                <button type="submit" class="btn bg-gradient-info w-100">Out Data</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endSection(); ?>