<?php
// session_start()
// include '../config/controller.php';

// Periksa apakah pengguna sudah login (opsional, tergantung kebutuhan)
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: ../pages/login.php');
    exit;
}

if (isset($_POST['submit'])) {
    $namaLengkap = htmlspecialchars($_POST['namaLengkap'] ?? '');
    $username = htmlspecialchars($_POST['username'] ?? '');
    $role = $_POST['role'] ?? '';
    $password = $_POST['password'] ?? '';
    $bidang = $_POST['bidang']; // Bidang opsional, default NULL untuk admin

    // Panggil fungsi register sekali dan simpan hasilnya
    $response = $func->register($username, $password, $namaLengkap, $role, $bidang);

    if ($response['response'] == 'positive') {
        echo "<script>
            alert('Akun berhasil didaftarkan!');
            window.location.href = '?req=akun&pages=list';
        </script>";
    } else {
        echo "<script>
            alert('" . htmlspecialchars($response['alert']) . "');
        </script>";
    }
}
?>

<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Daftarkan Akun</h4>
            <p class="card-description">Formulir untuk menambahkan akun.</p>
            <form class="forms-sample" method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="namaLengkap">Nama Lengkap</label>
                    <input type="text" class="form-control" id="namaLengkap" name="namaLengkap" placeholder="Masukkan Nama Lengkap" required>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="" selected disabled>Pilih role akun</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <div class="form-group" id="bidangField" style="display: none;">
                    <label for="bidang">Bidang</label>
                    <select class="form-select" id="bidang" name="bidang">
                        <option value="" selected disabled>Pilih bidang</option>
                        <option value="perencanaan">Perencanaan</option>
                        <option value="umum">Umum</option>
                        <option value="pelayanan">Pelayanan</option>
                        <option value="pemerintahan">Pemerintahan</option>
                        <option value="pembangunan">Pembangunan</option>
                        <option value="ketentraman">Ketentraman</option>
                    </select>
                </div>

                <button type="submit" name="submit" class="btn btn-primary me-2">Simpan</button>
                <button type="reset" class="btn btn-light">Batal</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('role').addEventListener('change', function() {
        var bidangField = document.getElementById('bidangField');
        if (this.value === 'user') {
            bidangField.style.display = 'block';
        } else {
            bidangField.style.display = 'none';
        }
    });
</script>