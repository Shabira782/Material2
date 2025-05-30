<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('assets/img/apple-icon.png') ?>">
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <title>
        Material System
    </title>
    <!--     Fonts and icons     -->
    <link href="<?= base_url('/assets/css/open_sans_family.css') ?>" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="<?= base_url('/assets/css/nucleo-icons.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('/assets/css/nucleo-svg.css') ?>" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="<?= base_url('assets/fa/js/fontawesome.min.js') ?>"></script>
    <link href="<?= base_url('assets/fa/css/all.min.css') ?>" rel=" stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="<?= base_url('/assets/css/soft-ui-dashboard.css') ?>" rel="stylesheet" />
    <!-- Nepcha Analytics (nepcha.com) -->
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
</head>

<body class="">
    <div class="container position-sticky z-index-sticky top-0">

    </div>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-75">
                <div class="container">
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
                        <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                            <div class="card card-plain mt-8">
                                <div class="card-header pb-0 text-left bg-transparent">
                                    <h2 class="font-weight-bolder text-info text-gradient"> Material System </h2>
                                    <p class="mb-0">Silahkan Masukan Email dan Password Anda</p>
                                </div>
                                <div class="card-body">
                                    <form role="form" action="<?= base_url('authverify') ?>" method="POST">
                                        <label>Email</label>
                                        <div class="mb-3">
                                            <input type="text" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="email-addon" name="username">
                                        </div>
                                        <label>Password</label>
                                        <div class="mb-3">
                                            <input type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon" name="password">
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Sign in</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('<?= base_url('/assets/img/lp.png') ?>')"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <footer class="footer py-5">
        <div class="container">

            <div class="row">
                <div class="col-8 mx-auto text-center mt-1">
                    <p class="mb-0 text-secondary">
                        Copyright © <script>
                            document.write(new Date().getFullYear())
                        </script> RnD BP System
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <!--   Core JS Files   -->
    <script src="<?= base_url('/assets/js/core/popper.min.js') ?>"></script>
    <script src="<?= base_url('/assets/js/core/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('/assets/js/plugins/perfect-scrollbar.min.js') ?>"></script>
    <script src="<?= base_url('/assets/js/plugins/smooth-scrollbar.min.js') ?>"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="<?= base_url('/assets/js/buttons.js') ?>"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="<?= base_url('/assets/js/soft-ui-dashboard.min.js?v=1.0.7') ?>"></script>
</body>

</html>