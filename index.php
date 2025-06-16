<?php
session_start();
include 'config/controller.php';

if (!isset($_SESSION['username'])) {
  header('Location: pages/login.php');
  exit;
}

// Debugging session (aktifkan untuk pengujian)
// echo "<pre>";
// var_dump($_SESSION);
// echo "</pre>";

// Ambil data pengguna, dengan penanganan error yang lebih baik
$row = $func->getDataById('akun', 'username', $_SESSION['username']);
if (!$row) {
  $nama = 'Pengguna Tidak Ditemukan';
} else {
  $id_akun = htmlspecialchars($row['id_akun']);
  $nama = isset($row['namaLengkap']) && !empty($row['namaLengkap']) ? htmlspecialchars($row['namaLengkap']) : 'Pengguna';
  // Perbarui session jika ada perubahan role atau bidang
  if ($_SESSION['role'] != $row['role'] || $_SESSION['bidang'] != $row['bidang']) {
    $_SESSION['role'] = $row['role'];
    $_SESSION['bidang'] = $row['bidang'];
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>SIPADU Sukodadi</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" type="text/css" href="assets/js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="assets/images/logo.png" />
</head>

<body class="with-welcome-text">
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand brand-logo d-none d-md-flex" href="index.php" style="align-items: center; gap: 10px; text-decoration: none; color: inherit;">
            <img src="assets/images/logo.png" alt="Logo SIPADU" style="height: 30px; width: auto; margin-left: 1rem;" />
            <h3 style="margin: 0; font-size: 1.25rem;"><strong>SIPADU</strong></h3>
          </a>
          <a class="navbar-brand brand-logo-mini d-md-none" href="index.php" style="display: inline-block;">
            <img src="assets/images/logo.png" alt="Logo SIPADU Mini" style="height: 30px; width: auto; margin-left: 50%; margin-bottom: 30%;" />
          </a>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
          <li class="nav-item fw-semibold d-none d-lg-block ms-0">
            <h1 class="welcome-text">Haii, <span class="text-black fw-bold"><?= $nama ?> !</span></h1>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title">Beranda</span>
            </a>
          </li>
          <li class="nav-item nav-category">Menu</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="menu-icon mdi mdi-floor-plan"></i>
              <span class="menu-title">Bidang</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <?php if (!$_SESSION['bidang'] || $_SESSION['bidang'] == 'perencanaan' || $_SESSION['role'] == 'admin'): ?>
                  <li class="nav-item"><a class="nav-link" href="?req=bidang&pages=perencanaan">Sub Bagian Perencanaan Evaluasi dan Keuangan</a></li>
                <?php endif; ?>
                <?php if (!$_SESSION['bidang'] || $_SESSION['bidang'] == 'umum' || $_SESSION['role'] == 'admin'): ?>
                  <li class="nav-item"><a class="nav-link" href="?req=bidang&pages=umum">Sub Bagian Umum dan Kepegawaian</a></li>
                <?php endif; ?>
                <?php if (!$_SESSION['bidang'] || $_SESSION['bidang'] == 'pelayanan' || $_SESSION['role'] == 'admin'): ?>
                  <li class="nav-item"><a class="nav-link" href="?req=bidang&pages=pelayanan">Seksi Pelayanan Publik</a></li>
                <?php endif; ?>
                <?php if (!$_SESSION['bidang'] || $_SESSION['bidang'] == 'pemerintahan' || $_SESSION['role'] == 'admin'): ?>
                  <li class="nav-item"><a class="nav-link" href="?req=bidang&pages=pemerintahan">Seksi Pemerintahan</a></li>
                <?php endif; ?>
                <?php if (!$_SESSION['bidang'] || $_SESSION['bidang'] == 'pembangunan' || $_SESSION['role'] == 'admin'): ?>
                  <li class="nav-item"><a class="nav-link" href="?req=bidang&pages=pembangunan">Seksi Pembangunan dan Pemberdayaan Masyarakat</a></li>
                <?php endif; ?>
                <?php if (!$_SESSION['bidang'] || $_SESSION['bidang'] == 'ketentraman' || $_SESSION['role'] == 'admin'): ?>
                  <li class="nav-item"><a class="nav-link" href="?req=bidang&pages=ketentraman">Seksi Ketentraman dan Ketertiban Umum</a></li>
                <?php endif; ?>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
              <i class="menu-icon mdi mdi-account-circle-outline"></i>
              <span class="menu-title">Akun</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item" <?php if ($_SESSION['role'] != 'admin') echo 'style="display: none;"'; ?>>
                  <a class="nav-link" href="?req=akun&pages=list">List Akun</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="?req=akun&pages=akunku&id=<?= $id_akun ?>">Akun Saya</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/logout.php">
              <i class="menu-icon mdi mdi-logout"></i>
              <span class="menu-title">Log Out</span>
            </a>
          </li>
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <?php
          include 'pages/pages.php';
          ?>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer py-3">
          <div class="container-fluid">
            <div class="d-sm-flex justify-content-center justify-content-sm-between align-items-sm-center">
              <span class="text-muted text-center text-sm-left mb-2 mb-sm-0">SIPADU Kecamatan Sukodadi Kabupaten Lamongan</span>
              <div class="d-flex align-items-center justify-content-center justify-content-sm-end">
                <span class="text-muted text-center me-2">Copyright © 2025. Sarjana Terapan Administrasi Negara Unesa</span>
                <img style="height: 2rem; width: auto;" src="https://upload.wikimedia.org/wikipedia/commons/f/f4/State_University_of_Surabaya_logo.png" alt="Logo Unesa">
              </div>
            </div>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="assets/vendors/chart.js/chart.umd.js"></script>
  <script src="assets/vendors/progressbar.js/progressbar.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/template.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="assets/js/jquery.cookie.js" type="text/javascript"></script>
  <script src="assets/js/dashboard.js"></script>
  <!-- End custom js for this page-->
</body>

</html>