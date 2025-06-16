<?php 

// cek apakah ada request GET
if (isset($_GET['pages'])) {
    $page = $_GET['page'];
} else {
    $page = 'home';
}

?>