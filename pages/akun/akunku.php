<?php


// Ambil ID akun dari parameter URL
$id_akun = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_akun <= 0) {
    die("ID akun tidak valid.");
}

// Ambil data akun berdasarkan ID
$row = $func->getDataById('akun', 'id_akun', $id_akun);
if (!$row) {
    die("Akun tidak ditemukan.");
}

$bidang_lama = $row['bidang'] ?? null;
$role_lama = $row['role'];

// Proses pembaruan data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $namaLengkap = htmlspecialchars(trim($_POST['namaLengkap'] ?? ''));
    $username = htmlspecialchars(trim($_POST['username'] ?? ''));
    $password = trim($_POST['password'] ?? ''); // Kosongkan jika tidak diubah

    // Jika password tidak diisi, gunakan password lama
    $hashedPassword = !empty($password) ? hash('sha256', $password) : $row['password'];

    // Panggil fungsi updateAccount
    $response = $func->updateAccount($id_akun, $username, $hashedPassword, $namaLengkap, $role_lama, $bidang_lama);

    if ($response['response'] == 'positive') {
        echo "<script>
            alert('Akun berhasil diperbarui!');
            window.location.href = '?req=akun&pages=list';
        </script>";
    } else {
        echo "<script>
            alert('" . htmlspecialchars($response['alert']) . "');
            window.location.href = '?req=akun&pages=edit&id=" . $id_akun . "';
        </script>";
    }
    exit();
}
?>

<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Edit Akun</h4>
            <p class="card-description">Formulir untuk mengedit data akun.</p>
            <form class="forms-sample" method="POST" action="">
                <input type="hidden" name="id_akun" value="<?= $id_akun ?>">

                <div class="form-group">
                    <label for="namaLengkap">Nama Lengkap</label>
                    <input type="text" class="form-control" id="namaLengkap" name="namaLengkap" value="<?= htmlspecialchars($row['namaLengkap']) ?>" placeholder="Masukkan Nama Lengkap" required>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($row['username']) ?>" placeholder="Masukkan Username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password <small>(Kosongkan jika tidak ingin mengganti)</small></label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password Baru (Opsional)">
                </div>

                <!-- <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="" disabled>Pilih role akun</option>
                        <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="user" <?= $row['role'] == 'user' ? 'selected' : '' ?>>User</option>
                    </select>
                </div>

                <div class="form-group" id="bidangField" style="display: <?= $row['role'] == 'user' ? 'block' : 'none' ?>;">
                    <label for="bidang">Bidang</label>
                    <select class="form-select" id="bidang" name="bidang">
                        <option value="" <?= !$row['bidang'] ? 'selected' : '' ?>>Pilih bidang</option>
                        <option value="perencanaan" <?= $row['bidang'] == 'perencanaan' ? 'selected' : '' ?>>Perencanaan</option>
                        <option value="umum" <?= $row['bidang'] == 'umum' ? 'selected' : '' ?>>Umum</option>
                        <option value="pelayanan" <?= $row['bidang'] == 'pelayanan' ? 'selected' : '' ?>>Pelayanan</option>
                        <option value="pemerintahan" <?= $row['bidang'] == 'pemerintahan' ? 'selected' : '' ?>>Pemerintahan</option>
                        <option value="pembangunan" <?= $row['bidang'] == 'pembangunan' ? 'selected' : '' ?>>Pembangunan</option>
                        <option value="ketentraman" <?= $row['bidang'] == 'ketentraman' ? 'selected' : '' ?>>Ketentraman</option>
                    </select>
                </div> -->

                <button type="submit" name="submit" class="btn btn-primary me-2">Simpan</button>
                <a href="?req=akun&pages=list" class="btn btn-light">Batal</a>
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