<?php
// Asumsi koneksi sudah dibuat
// include 'config/db_mysqli.php';

$current_subpage = isset($_GET['pages']) ? $_GET['pages'] : '';
$search_dokumen = isset($_GET['search']) ? $_GET['search'] : '';
$search_date = isset($_GET['date']) ? $_GET['date'] : '';

$conditions = [];

// Tambahkan filter untuk bidangDokumen = 'Sub Bagian Pelayanan Publik'
$conditions[] = "bidangDokumen = 'Seksi Pelayanan Publik'";

// Bersihkan dan validasi input
if (!empty($search_dokumen)) {
    $search_dokumen = mysqli_real_escape_string($conn, $search_dokumen);
    $conditions[] = "namaDokumen LIKE '%$search_dokumen%'";
}

if (!empty($search_date)) {
    $date = DateTime::createFromFormat('Y-m-d', $search_date);
    if ($date && $date->format('Y-m-d') === $search_date) {
        $conditions[] = "DATE(tanggalDokumen) = '$search_date'";
    }
}

// Bangun query
$sql = "SELECT 
    id_dokumen,
    nomorDokumen, 
    jenisDokumen AS jenis, 
    tahun, 
    namaDokumen, 
    tanggalDokumen AS tanggal,
    create_at, 
    kategoriDokumen AS kategori, 
    size, 
    lokasiDokumen
FROM dokumen
";

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY tanggalDokumen DESC";

// Eksekusi query
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Query error: " . mysqli_error($conn);
}

// Pisahkan data berdasarkan kategori
$surat_masuk = [];
$surat_keluar = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['kategori'] === 'Surat Masuk') {
            $surat_masuk[] = $row;
        } elseif ($row['kategori'] === 'Surat Keluar') {
            $surat_keluar[] = $row;
        }
    }
}

// Batasi masing-masing kategori ke 10 data terbaru
$surat_masuk = array_slice($surat_masuk, 0, 10);
$surat_keluar = array_slice($surat_keluar, 0, 10);
?>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tabel Dokumen Seksi Pelayanan Publik</h4>
                <p class="card-description"> Tambah Dokumen <a href="?req=bidang&pages=tambah-<?= htmlspecialchars($current_subpage) ?>">Tambah</a></p>
                <form action="" method="get">
                    <div class="row g-2 align-items-center mb-3">
                        <div class="col-auto">
                            <input type="hidden" name="req" value="bidang">
                            <input type="hidden" name="pages" value="<?= htmlspecialchars($current_subpage) ?>">
                            <input class="form-control form-control-sm" name="search" type="text" placeholder="Cari Nama Dokumen" aria-label="Cari Nama Dokumen" value="<?= htmlspecialchars($search_dokumen) ?>">
                        </div>
                        <div class="col-auto">
                            <input class="form-control form-control-sm" name="date" type="date" aria-label="Tanggal Upload" value="<?= htmlspecialchars($search_date) ?>">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary btn-sm" type="submit">Cari</button>
                        </div>
                        <?php if (!empty($search_dokumen) || !empty($search_date)): ?>
                            <div class="col-auto">
                                <a href="?req=bidang&pages=<?= htmlspecialchars($current_subpage) ?>" class="btn btn-secondary btn-sm">Reset</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>

                <!-- Tabel Surat Masuk -->
                <h5 class="mt-4">Surat Masuk</h5>
                <div class="table-responsive mb-4">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Jenis</th>
                                <th>Tahun</th>
                                <th>Nama Dokumen</th>
                                <th>Tanggal Dokumen</th>
                                <th>Size (KB)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($surat_masuk)) {
                                foreach ($surat_masuk as $row) {
                                    $nomor_dok = isset($row['nomorDokumen']) ? htmlspecialchars($row['nomorDokumen']) : 'N/A';
                                    $jenis_dok = isset($row['jenis']) ? htmlspecialchars($row['jenis']) : 'N/A';
                                    $tahun_dok = isset($row['tahun']) ? htmlspecialchars($row['tahun']) : 'N/A';
                                    $nama_dok = isset($row['namaDokumen']) ? htmlspecialchars($row['namaDokumen']) : 'N/A';
                                    $tgl_upload = isset($row['tanggal']) ? htmlspecialchars($row['tanggal']) : 'N/A';
                                    $size_dok = isset($row['size']) ? htmlspecialchars($row['size']) : 'N/A';
                                    $file_path_dok = isset($row['lokasiDokumen']) ? htmlspecialchars($row['lokasiDokumen']) : '#';
                                    $id_dok = isset($row['id_dokumen']) ? $row['id_dokumen'] : null;

                                    echo "<tr>
                                            <td>{$nomor_dok}</td>
                                            <td>{$jenis_dok}</td>
                                            <td>{$tahun_dok}</td>
                                            <td>{$nama_dok}</td>
                                            <td>{$tgl_upload}</td>
                                            <td>{$size_dok}</td>
                                            <td>";
                                    if ($_SESSION['role'] == 'admin') {
                                        if ($id_dok) {
                                            echo "<a href='?req=bidang&pages=hapus&id=$id_dok&bidang=$current_subpage' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus dokumen ini?')\">Hapus</a> ";
                                        }
                                    }
                                    echo "<a href='{$file_path_dok}' class='btn btn-primary btn-sm' target='_blank'>Lihat</a> ";
                                    echo "<a href='{$file_path_dok}' class='btn btn-success btn-sm' download>Download</a>";
                                    echo "</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center'>Tidak ada data Surat Masuk yang cocok dengan pencarian Anda.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Tabel Surat Keluar -->
                <h5 class="mt-4">Surat Keluar</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Jenis</th>
                                <th>Tahun</th>
                                <th>Nama Dokumen</th>
                                <th>Tanggal Dokumen</th>
                                <th>Size (KB)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($surat_keluar)) {
                                foreach ($surat_keluar as $row) {
                                    $nomor_dok = isset($row['nomorDokumen']) ? htmlspecialchars($row['nomorDokumen']) : 'N/A';
                                    $jenis_dok = isset($row['jenis']) ? htmlspecialchars($row['jenis']) : 'N/A';
                                    $tahun_dok = isset($row['tahun']) ? htmlspecialchars($row['tahun']) : 'N/A';
                                    $nama_dok = isset($row['namaDokumen']) ? htmlspecialchars($row['namaDokumen']) : 'N/A';
                                    $tgl_upload = isset($row['tanggal']) ? htmlspecialchars($row['tanggal']) : 'N/A';
                                    $size_dok = isset($row['size']) ? htmlspecialchars($row['size']) : 'N/A';
                                    $file_path_dok = isset($row['lokasiDokumen']) ? htmlspecialchars($row['lokasiDokumen']) : '#';
                                    $id_dok = isset($row['id_dokumen']) ? $row['id_dokumen'] : null;

                                    echo "<tr>
                                            <td>{$nomor_dok}</td>
                                            <td>{$jenis_dok}</td>
                                            <td>{$tahun_dok}</td>
                                            <td>{$nama_dok}</td>
                                            <td>{$tgl_upload}</td>
                                            <td>{$size_dok}</td>
                                            <td>";
                                    if ($_SESSION['role'] == 'admin') {
                                        if ($id_dok) {
                                            echo "<a href='?req=bidang&pages=hapus&id=$id_dok&bidang=$current_subpage' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus dokumen ini?')\">Hapus</a> ";
                                        }
                                    }
                                    echo "<a href='{$file_path_dok}' class='btn btn-primary btn-sm' target='_blank'>Lihat</a> ";
                                    echo "<a href='{$file_path_dok}' class='btn btn-success btn-sm' download>Download</a>";
                                    echo "</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center'>Tidak ada data Surat Keluar yang cocok dengan pencarian Anda.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <hr>
                <?php
                // Logika untuk pagination jika diperlukan
                // Catatan: Pagination mungkin tidak diperlukan karena data dibatasi ke 10 per tabel
                ?>
            </div>
        </div>
    </div>
</div>