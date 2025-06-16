<?php
// Diasumsikan $conn adalah objek koneksi mysqli Anda dari file config.php
// require_once 'config.php'; // atau path ke file koneksi Anda

// Inisialisasi variabel pencarian
$search_username = '';
$search_role = '';
$search_date = '';
$conditions = [];
$params = [];
$param_types = '';

// --- Membangun Query Berdasarkan Input Pencarian ---
$sql = "SELECT id_akun, username, namaLengkap, role, create_at FROM akun"; // Sesuaikan nama tabel jika perlu

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_username = $_GET['search'];
    $conditions[] = "username LIKE ?";
    $params[] = "%" . $search_username . "%";
    $param_types .= 's';
}

if (isset($_GET['role']) && !empty($_GET['role'])) {
    $search_role = $_GET['role'];
    $conditions[] = "role = ?";
    $params[] = $search_role;
    $param_types .= 's';
}

if (isset($_GET['date']) && !empty($_GET['date'])) {
    $search_date = $_GET['date'];
    // Memastikan format tanggal valid sebelum digunakan dalam query
    // Untuk pencarian tanggal yang tepat, gunakan DATE() pada kolom datetime
    if (DateTime::createFromFormat('Y-m-d', $search_date) !== false) {
        $conditions[] = "DATE(create_at) = ?";
        $params[] = $search_date;
        $param_types .= 's';
    }
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY create_at DESC"; // Urutkan berdasarkan tanggal pembuatan terbaru

// --- Eksekusi Query ---
$stmt = $conn->prepare($sql);

if ($stmt) {
    if (!empty($params)) {
        // Spread operator (...) untuk mengirim array $params ke bind_param
        $stmt->bind_param($param_types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Handle error jika prepare statement gagal
    echo "Error preparing statement: " . $conn->error;
    $result = false; // Set result ke false agar tidak error di bawah
}

?>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tabel Akun</h4>
                <p class="card-description"> Tambah Akun <a href="?req=akun&pages=daftar">Tambah</a></p>
                <form action="" method="get">
                    <div class="row g-2 align-items-center mb-3">
                        <div class="col-auto">
                            <input type="hidden" name="req" value="akun">
                            <input type="hidden" name="pages" value="list">
                            <input class="form-control form-control-sm" name="search" type="text" placeholder="Cari Username" aria-label="Cari Username" value="<?php echo htmlspecialchars($search_username); ?>">
                        </div>
                        <div class="col-auto">
                            <select class="form-select form-select-sm" id="role" name="role">
                                <option value="">Semua Role</option>
                                <option value="admin" <?php echo ($search_role === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                <option value="user" <?php echo ($search_role === 'user') ? 'selected' : ''; ?>>User</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <input class="form-control form-control-sm" name="date" type="date" aria-label="Tanggal Buat" value="<?php echo htmlspecialchars($search_date); ?>">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary btn-sm" type="submit">Cari</button>
                        </div>
                        <?php if (!empty($search_username) || !empty($search_role) || !empty($search_date)): ?>
                            <div class="col-auto">
                                <a href="?req=akun&pages=list" class="btn btn-secondary btn-sm">Reset</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Role</th>
                                <th>Tanggal Buat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Periksa apakah $result adalah objek yang valid dan memiliki baris data
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    // Pastikan kolom ada sebelum diakses untuk menghindari Undefined array key
                                    $username = isset($row['username']) ? htmlspecialchars($row['username']) : 'N/A';
                                    $namaLengkap = isset($row['namaLengkap']) ? htmlspecialchars($row['namaLengkap']) : 'N/A';
                                    $role = isset($row['role']) ? htmlspecialchars($row['role']) : 'N/A';
                                    $create_at = isset($row['create_at']) ? htmlspecialchars(date('d-m-Y', strtotime($row['create_at']))) : 'N/A';
                                    $id_akun = isset($row['id_akun']) ? $row['id_akun'] : '#';

                                    echo "<tr>
                                            <td>{$username}</td>
                                            <td>{$namaLengkap}</td>
                                            <td>{$role}</td>
                                            <td>{$create_at}</td>
                                            <td>
                                                <a href='?req=akun&pages=hapus&id={$id_akun}' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus akun ini?\")'>Hapus</a>
                                                
                                                <a href='?req=akun&pages=edit&id={$id_akun}' class='btn btn-warning btn-sm'>Edit</a>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>Tidak ada data akun yang ditemukan.</td></tr>";
                            }
                            // Menutup statement jika sudah dibuka
                            if (isset($stmt) && $stmt) {
                                $stmt->close();
                            }
                            ?>
                        </tbody>
                    </table>
                    <br>
                    <?php
                    // Di sini Anda akan menambahkan logika untuk pagination jika diperlukan
                    // Contoh sederhana (tanpa logika lengkap, hanya placeholder):
                    // if ($result && $result->num_rows > 0) { // Anda perlu menghitung total item untuk pagination yang benar
                    //     echo '<nav aria-label="Page navigation example">';
                    //     echo '  <ul class="pagination">';
                    //     echo '    <li class="page-item"><a class="page-link" href="#"> &lt;- </a></li>';
                    //     echo '    <li class="page-item"><a class="page-link" href="#"> -&gt; </a></li>';
                    //     echo '  </ul>';
                    //     echo '</nav>';
                    // }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>