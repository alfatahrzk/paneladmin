<?php
// Start session for CSRF or user authorization (if applicable)
session_start();

// Check if required GET parameters are set
if (!isset($_GET['id']) || !isset($_GET['bidang'])) {
    echo "<script>
        alert('Parameter ID atau bidang tidak valid');
        window.location.href='?req=bidang&pages=dashboard';
    </script>";
    exit();
}

// Sanitize inputs
$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$bidang = htmlspecialchars(trim($_GET['bidang']));

// Validate inputs
if (!is_numeric($id) || empty($bidang)) {
    echo "<script>
        alert('ID dokumen atau bidang tidak valid');
        window.location.href='?req=bidang&pages=$bidang';
    </script>";
    exit();
}

// Include database controller
include_once '../../config/controller.php';

// Optional: Add CSRF token validation (implement based on your setup)
// if (!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
//     echo "<script>
//         alert('Permintaan tidak valid');
//         window.location.href='?req=bidang&pages=$bidang';
//     </script>";
//     exit();
// }

// Optional: Add authorization check (implement based on your authentication system)
// if (!isUserAuthorized()) {
//     echo "<script>
//         alert('Anda tidak memiliki izin untuk menghapus dokumen');
//         window.location.href='?req=bidang&pages=$bidang';
//     </script>";
//     exit();
// }

try {
    // Fetch document record to get file path
    $result = $func->getDataById('dokumen', 'id_dokumen', $id);

    if ($result && !empty($result['lokasiDokumen'])) {
        $file_path = $result['lokasiDokumen'];

        // Attempt to delete the database record
        $delete = $func->delete('dokumen', 'id_dokumen', $id);

        if ($delete) {
            // Attempt to delete the file from the file system
            if (file_exists($file_path)) {
                if (!unlink($file_path)) {
                    // Log error (optional, implement logging based on your setup)
                    // error_log("Gagal menghapus file: $file_path");
                    echo "<script>
                        alert('Dokumen dihapus dari database, tetapi gagal menghapus file');
                        window.location.href='?req=bidang&pages=$bidang';
                    </script>";
                    exit();
                }
            }

            echo "<script>
                alert('Dokumen berhasil dihapus');
                window.location.href='?req=bidang&pages=$bidang';
            </script>";
            exit();
        } else {
            echo "<script>
                alert('Gagal menghapus dokumen dari database');
                window.location.href='?req=bidang&pages=$bidang';
            </script>";
            exit();
        }
    } else {
        echo "<script>
            alert('Dokumen tidak ditemukan');
            window.location.href='?req=bidang&pages=$bidang';
        </script>";
        exit();
    }
} catch (Exception $e) {
    // Log error (optional)
    // error_log("Error in hapus_dokumen.php: " . $e->getMessage());
    echo "<script>
        alert('Terjadi kesalahan saat menghapus dokumen');
        window.location.href='?req=bidang&pages=$bidang';
    </script>";
    exit();
}
