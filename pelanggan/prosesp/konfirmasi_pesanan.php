<?php
include "../../admin/proses/koneksi.php"; // Pastikan file ini terhubung dengan database

$db = new Database(); // Create an instance of the Database class
$conn = $db->getConnection(); // Get the connection

$id_pesanan = isset($_POST['id_pesanan']) ? $_POST['id_pesanan'] : null;


$data = mysqli_query($conn, "select * from tb_pesanan where id_pesanan = '$id_pesanan'");
$row = mysqli_fetch_array($data);

$time_pesan = $row['time'];
$time_sampai = $row['waktu_sampai'];
$id_user = $row['id_user'];
$email = $row['email'];
$nama = $row['nama'];
$nohp = $row['nohp'];
$nama_penerima = $row['nama_penerima'];
$alamat_tujuan = $row['alamat_tujuan'];
$metode_bayar = $row['metode_bayar'];
$total = $row['total'];
$status_pembayaran = "Salesai";

// Menggunakan prepared statement untuk memasukkan data ke tb_transaksi
$stmt_insert = $conn->prepare("INSERT INTO tb_transaksi (id_pesanan, time_pesan, time_sampai, id_user, email, nama, nohp, nama_penerima, alamat_tujuan, metode_bayar, total, status_pembayaran) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt_insert->bind_param("ssssssssssss", $id_pesanan, $time_pesan, $time_sampai, $id_user, $email, $nama, $nohp, $nama_penerima, $alamat_tujuan, $metode_bayar, $total, $status_pembayaran);
$stmt_insert->execute();
$stmt_insert->close();

// Prepared statement untuk UPDATE
$stmt_update = $conn->prepare("UPDATE tb_detail_pesanan SET status_pembayaran = ? WHERE id_pesanan = ?");
$stmt_update->bind_param("ss", $status_pembayaran, $id_pesanan);
$stmt_update->execute();
$stmt_update->close();

// Prepared statement untuk DELETE
$stmt_delete = $conn->prepare("DELETE FROM tb_pesanan WHERE id_pesanan = ?");
$stmt_delete->bind_param("s", $id_pesanan);
$stmt_delete->execute();
$stmt_delete->close();

echo '<script>alert("Data Berhasil Diperbarui");window.location.href = "../pesanan.php";</script>';

exit();
