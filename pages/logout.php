<?php

// mengaktifkan session php
session_start();

// menghapus semua session
session_destroy();
session_abort();

// mengalihkan halaman sambil mengirim pesan logout

echo "<script>alert('Anda telah keluar dari sistem!')</script>";
echo "<script>window.location.href = '../pages/login.php'</script>";
exit();
