<?php

// cek apakah ada request GET
if (isset($_GET['req'])) {
    // menangkap get
    $pages = $_GET['req'];

    if (isset($_GET['pages'])) {
        $subpages = $_GET['pages'];
        switch ($pages) {
            case 'akun':
                switch ($subpages) {
                    case 'list':
                        include 'akun/list-akun.php';
                        break;
                    case 'edit':
                        include 'akun/edit-akun.php';
                        break;
                    case 'daftar':
                        include 'akun/register.php';
                        break;
                    case 'akunku':
                        include 'akun/akunku.php';
                        break;
                    case 'hapus':
                        include 'akun/delete-akun.php';
                        break;

                    default:
                        include 'home.php';
                        break;
                }
                break;
            case 'bidang':
                switch ($subpages) {
                    case 'perencanaan':
                        include 'bidang/perencanaan.php';
                        break;
                    case 'tambah-perencanaan':
                        include 'bidang/tambah-perencanaan.php';
                        break;
                    case 'umum':
                        include 'bidang/umum.php';
                        break;
                    case 'tambah-umum':
                        include 'bidang/tambah-umum.php';
                        break;
                    case 'pelayanan':
                        include 'bidang/pelayanan.php';
                        break;
                    case 'tambah-pelayanan':
                        include 'bidang/tambah-pelayanan.php';
                        break;
                    case 'pemerintahan':
                        include 'bidang/pemerintahan.php';
                        break;
                    case 'tambah-pemerintahan':
                        include 'bidang/tambah-pemerintahan.php';
                        break;
                    case 'pembangunan':
                        include 'bidang/pembangunan.php';
                        break;
                    case 'tambah-pembangunan':
                        include 'bidang/tambah-pembangunan.php';
                        break;
                    case 'ketentraman':
                        include 'bidang/ketentraman.php';
                        break;
                    case 'tambah-ketentraman':
                        include 'bidang/tambah-ketentraman.php';
                        break;
                    case 'hapus':
                        include 'bidang/hapus-dokumen.php';
                        break;

                    default:
                        include 'home.php';
                        break;
                }
                break;
        }
    } else {
        include 'home.php';
    }
} else {
    include 'home.php';
}
