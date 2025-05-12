<?php
include_once "koneksi.php";

$db = new Database();
$conn = $db->getConnection();

if (isset($_GET['id'])) {
    $id_transaksi = $_GET['id'];

    // Hapus detail pemesanan berdasarkan id_transaksi
    $query1 = "DELETE FROM tb_detail_pesanan WHERE id_pesanan IN (SELECT id_pesanan FROM tb_transaksi WHERE id_transaksi = ?)";
    $stmt1 = $conn->prepare($query1);
    $stmt1->bind_param("i", $id_transaksi);
    $stmt1->execute();

    // Hapus laporan transaksi
    $query2 = "DELETE FROM tb_transaksi WHERE id_transaksi = ?";
    $stmt2 = $conn->prepare($query2);
    $stmt2->bind_param("i", $id_transaksi);
    $stmt2->execute();

    if ($stmt2->affected_rows > 0) {
        echo "<script>alert('Data berhasil dihapus.'); window.location='../laporan';</script>";
    } else {
        echo "<script>alert('Data gagal dihapus.'); window.location='../laporan';</script>";
    }
} else {
    echo "<script>alert('ID transaksi tidak ditemukan.'); window.location='../laporan';</script>";
}
