<?php
session_start(); // Pastikan ini baris pertama

include '../config/controller.php';
// Check if the user is already logged in
if (isset($_SESSION['username'])) {
  header('Location: ../index.php');
  exit;
}

$error = '';
if (isset($_POST['login'])) {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if (empty($username) || empty($password)) {
    $error = 'Username dan password wajib diisi!';
  } else {
    $response = $func->login($username, $password);
    if ($response['response'] == 'positive') {
      $_SESSION['username'] = $response['username'];
      $_SESSION['role'] = $response['role'];
      $_SESSION['bidang'] = $response['bidang']; // Pastikan ini diambil dari database
      var_dump($_SESSION); // Debugging
      echo "<script>
    alert('Login Berhasil! Selamat datang, {$response['username']}');
    window.location.href = '../index.php';
  </script>";
      exit;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login SIPADU</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../assets/css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../assets/images/logo.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light py-5 px-4 px-sm-5">
              <div class="brand-logo text-center">
                <img src="../assets/images/logo.png" alt="logo">
              </div>
              <h4 class="text-center mb-4">SIPADU Kecamatan Sukodadi</h4>
              <h6 class="fw-light text-center">Login untuk melanjutkan.</h6>
              <form class="pt-3" method="post" action="">
                <div class="form-group">
                  <input type="text" name="username" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Username">
                </div>
                <div class="form-group">
                  <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password">
                </div>
                <div class="mt-3 d-grid gap-2">
                  <button type="submit" name="login" class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn">Log In</button>
                </div>
                <div class="mb-2 d-grid gap-2">

                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../assets/js/off-canvas.js"></script>
  <script src="../assets/js/template.js"></script>
  <script src="../assets/js/settings.js"></script>
  <script src="../assets/js/hoverable-collapse.js"></script>
  <script src="../assets/js/todolist.js"></script>
  <!-- endinject -->
</body>

</html>