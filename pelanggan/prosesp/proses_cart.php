<?php
include "../../admin/proses/koneksi.php"; // Pastikan file ini terhubung dengan database

$db = new Database();
$conn = $db->getConnection();
$id_cart = (isset($_POST['id_cart'])) ? htmlentities($_POST['id_cart']) : "";
$jumlah_edit = (isset($_POST['jumlah_edit'])) ? intval($_POST['jumlah_edit']) : 0;
$harga = (isset($_POST['harga'])) ? intval($_POST['harga']) : 0;
$total_harga = ($harga * $jumlah_edit);
$query = mysqli_query($conn, "UPDATE tb_cart SET jumlah = '$jumlah_edit', total_harga = '$total_harga' where id_cart = '$id_cart'");

header('Location:../cart.php');
exit();
