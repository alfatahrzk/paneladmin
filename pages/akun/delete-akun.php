<?php

include_once '../../config/controller.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $func->getDataById('akun', 'id_akun', $id);

    if ($result) {
        $delete = $func->delete('akun', 'id_akun', $id);
        if ($delete) {
            echo "<script>
                alert('Akun berhasil dihapus');
                window.location.href='?req=akun&pages=list';
            </script>";
        } else {
            echo "<script>
                alert('Gagal menghapus akun');
                window.location.href='?req=akun&pages=list';
            </script>";
        }
    } else {
        echo "<script>
            alert('Akun tidak ditemukan');
            window.location.href='?req=akun&pages=list';
        </script>";
    }
} else {
    echo "<script>
        alert('ID akun tidak valid');
        window.location.href='?req=akun&pages=list';
    </script>";
}
