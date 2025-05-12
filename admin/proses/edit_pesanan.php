<?php
include("koneksi.php");

$db = new Database(); // Create an instance of the Database class
$conn = $db->getConnection(); // Get the connection

$id_pesanan = (isset($_POST['id_pesanan'])) ? htmlentities($_POST['id_pesanan']) : "";
$status_pembayaran = (isset($_POST['status_pembayaran'])) ? htmlentities($_POST['status_pembayaran']) : "";

if ($status_pembayaran == "Sudah Sampai Di Tempat Tujuan"){
    $waktu_saat_ini = date("Y-m-d H:i:s");
    $query = mysqli_query($conn, "UPDATE tb_pesanan SET waktu_sampai='$waktu_saat_ini', status_pembayaran='$status_pembayaran' WHERE id_pesanan= '$id_pesanan'");
}else{
    $query = mysqli_query($conn, "UPDATE tb_pesanan SET status_pembayaran='$status_pembayaran' WHERE id_pesanan= '$id_pesanan'");
}

echo '<script>alert("Data Berhasil Diperbarui");window.location.href = "../pesanan";</script>';