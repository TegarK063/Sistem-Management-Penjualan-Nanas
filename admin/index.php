<?php
session_start();
if (isset($_GET['x']) && $_GET['x'] == 'home') {
    $page = 'home.php';
    include 'main.php';
} elseif (isset($_GET['x']) && $_GET['x'] == 'kategori') {
    $page = "kategori.php";
    include "main.php";
} elseif (isset($_GET['x']) && $_GET['x'] == 'produk') {
    $page = "produk.php";
    include "main.php";
} elseif (isset($_GET['x']) && $_GET['x'] == 'pesanan') {
    $page = "pesanan.php";
    include "main.php";
} elseif (isset($_GET['x']) && $_GET['x'] == 'laporan') {
    $page = "laporan.php";
    include "main.php";
} elseif (isset($_GET['x']) && $_GET['x'] == 'user') {
    if ($_SESSION['level_nanas'] == 1) {
        $page = "user.php";
        include "main.php";
    } else {
        $page = "home.php";
        include "main.php";
    }
} elseif (isset($_GET['x']) && $_GET['x'] == 'login') {
    include "login.php";
} elseif (isset($_GET['x']) && $_GET['x'] == 'logout') {
    include "proses/logout.php";
} elseif (isset($_GET['x']) && $_GET['x'] == 'register') {
    include "register.php";
} else {
    $page = "home.php";
    include "main.php";
}
