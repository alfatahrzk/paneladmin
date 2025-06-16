<?php

$bidang = 'Seksi Pembangunan dan Pemberdayaan Masyarakat';
$nomorDokumen = '';
$jenisDokumen = '';
$tahunDokumen = '';
$namaDokumen = '';
$tanggalDokumen = '';
$kategoriDokumen = '';
$error = '';

if (isset($_POST['submit'])) {
    $nomorDokumen = isset($_POST['nomorDokumen']) ? trim($_POST['nomorDokumen']) : '';
    $jenisDokumen = isset($_POST['jenisDokumen']) ? trim($_POST['jenisDokumen']) : '';
    $tahunDokumen = isset($_POST['tahunDokumen']) ? trim($_POST['tahunDokumen']) : '';
    $namaDokumen = isset($_POST['namaDokumen']) ? trim($_POST['namaDokumen']) : '';
    $tanggalDokumen = isset($_POST['tanggalDokumen']) ? trim($_POST['tanggalDokumen']) : '';
    $kategoriDokumen = isset($_POST['kategoriDokumen']) ? trim($_POST['kategoriDokumen']) : '';

    $target_dir = "assets/dokument/seksi_pembangunan_dan_pemberdayaan_masyarakat/";
    if (!is_dir($target_dir)) {
        if (!mkdir($target_dir, 0775, true)) {
            $error = 'Gagal membuat direktori tujuan. Periksa izin direktori!';
        }
    }

    if (!$error && (!isset($_FILES['fileDokumen']) || $_FILES['fileDokumen']['error'] == UPLOAD_ERR_NO_FILE)) {
        $error = 'File dokumen harus dipilih!';
    } elseif (!$error) {
        $file_name = basename($_FILES['fileDokumen']['name']);
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $file_size = $_FILES['fileDokumen']['size'];
        $file_name_new = bin2hex(random_bytes(16)) . '-seksi_pembangunan_dan_pemberdayaan_masyarakat.' . $file_type;
        $target_file = $target_dir . $file_name_new;

        $allowed_types = ['pdf', 'PDF', 'doc', 'DOC', 'docx', 'DOCX', 'txt', 'TXT', 'ppt', 'PPT', 'pptx', 'PPTX'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($file_type, $allowed_types)) {
            $error = 'Hanya file dokumen (pdf, docx, ppt yang diizinkan';
        } elseif ($file_size > $max_size) {
            $error = 'Ukuran file maksimal 5MB!';
        } elseif (empty($nomorDokumen) || empty($jenisDokumen) || empty($tahunDokumen) || empty($namaDokumen) || empty($tanggalDokumen) || empty($kategoriDokumen)) {
            $error = 'Semua field harus diisi!';
        } else {
            if (move_uploaded_file($_FILES['fileDokumen']['tmp_name'], $target_file)) {
                $sql = "INSERT INTO dokumen (nomorDokumen, jenisDokumen, tahun, namaDokumen, tanggalDokumen, kategoriDokumen, size, lokasiDokumen, bidangDokumen, create_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'sssssssss', $nomorDokumen, $jenisDokumen, $tahunDokumen, $namaDokumen, $tanggalDokumen, $kategoriDokumen, $file_size, $target_file, $bidang);

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);
                    echo "<script>
                        alert('Dokumen berhasil ditambahkan!');
                        window.location.href='?req=bidang&pages=pembangunan';
                    </script>";
                    exit;
                } else {
                    unlink($target_file);
                    $error = 'Gagal menyimpan ke database: ' . mysqli_error($conn);
                }
                mysqli_stmt_close($stmt);
            } else {
                $error = 'Gagal mengunggah file! Periksa izin direktori: ' . realpath($target_dir);
            }
        }
    }
}
?>

<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Tambah Dokumen Seksi Pembangunan dan Pemberdayaan Masyarakat</h4>
            <p class="card-description">Formulir untuk menambahkan dokumen baru ke Seksi Pembangunan dan Pemberdayaan Masyarakat.</p>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form class="forms-sample" method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nomorDokumen">Nomor Dokumen</label>
                    <input type="text" class="form-control" id="nomorDokumen" name="nomorDokumen" placeholder="Masukkan Nomor Dokumen" value="<?= htmlspecialchars($nomorDokumen) ?>" required>
                </div>
                <div class="form-group">
                    <label for="jenisDokumen">Jenis Dokumen</label>
                    <input type="text" class="form-control" id="jenisDokumen" name="jenisDokumen" placeholder="Masukkan Jenis Dokumen" value="<?= htmlspecialchars($jenisDokumen) ?>" required>
                </div>
                <div class="form-group">
                    <label for="tahunDokumen">Tahun</label>
                    <input type="number" class="form-control" id="tahunDokumen" name="tahunDokumen" placeholder="Masukkan Tahun (YYYY)" min="2000" max="2099" value="<?= htmlspecialchars($tahunDokumen) ?>" required>
                </div>
                <div class="form-group">
                    <label for="namaDokumen">Nama Dokumen</label>
                    <input type="text" class="form-control" id="namaDokumen" name="namaDokumen" placeholder="Masukkan Judul atau Nama Dokumen" value="<?= htmlspecialchars($namaDokumen) ?>" required>
                </div>
                <div class="form-group">
                    <label for="tanggalDokumen">Tanggal Dokumen</label>
                    <input type="date" class="form-control" id="tanggalDokumen" name="tanggalDokumen" value="<?= htmlspecialchars($tanggalDokumen) ?>" required>
                </div>
                <div class="form-group">
                    <label for="kategoriDokumen">Kategori</label>
                    <select class="form-select" id="kategoriDokumen" name="kategoriDokumen" required>
                        <option value="" <?= empty($kategoriDokumen) ? 'selected' : '' ?>>Pilih Kategori Dokumen</option>
                        <option value="Surat Masuk" <?= $kategoriDokumen == 'Surat Masuk' ? 'selected' : '' ?>>Surat Masuk</option>
                        <option value="Surat Keluar" <?= $kategoriDokumen == 'Surat Keluar' ? 'selected' : '' ?>>Surat Keluar</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Upload File Dokumen (PDF, Word, PPT, maks 5MB)</label>
                    <input type="file" name="fileDokumen" class="file-upload-default" id="fileDokumen" accept=".pdf,.doc,.docx,.ppt,.pptx" required>
                    <div class="input-group col-xs-12">
                        <input type="text" class="form-control file-upload-info" disabled placeholder="Pilih dokumen (PDF, Word, PPT)">
                        <span class="input-group-append">
                            <button class="file-upload-browse btn btn-primary" type="button">Browse</button>
                        </span>
                    </div>
                    <small class="form-text text-muted">Hanya file PDF, Word (.doc/.docx), atau PowerPoint (.ppt/.pptx), maksimum 5MB.</small>
                </div>
                <button type="submit" name="submit" class="btn btn-primary me-2">Simpan</button>
                <button type="reset" class="btn btn-light">Reset</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('.file-upload-browse').on('click', function() {
            var fileInput = $(this).closest('.form-group').find('.file-upload-default');
            fileInput.click();
        });
        $('.file-upload-default').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).closest('.form-group').find('.file-upload-info').val(fileName);
        });
    });
</script>