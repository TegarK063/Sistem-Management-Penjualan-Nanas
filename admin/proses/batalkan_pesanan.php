<?php
include_once "koneksi.php";

$db = new Database();
$conn = $db->getConnection();

if (isset($_POST['batalkan_pesanan'])) {
    $id_pesanan = $_POST['id_pesanan'];

    // Ambil detail pesanan untuk mengembalikan stok
    $query_detail = mysqli_query($conn, "SELECT * FROM tb_detail_pesanan WHERE id_pesanan = '$id_pesanan'");
    while ($row = mysqli_fetch_assoc($query_detail)) {
        $id = $row['id'];
        $jumlah = $row['jumlah'];

        // Kembalikan stok barang
        $update_stok = mysqli_query($conn, "UPDATE tb_daftar_menu SET stok = stok + $jumlah WHERE id = '$id'");
        if (!$update_stok) {
            die("Gagal mengembalikan stok barang: " . mysqli_error($conn));
        }
    }

    // Hapus detail pesanan
    $delete_detail = mysqli_query($conn, "DELETE FROM tb_detail_pesanan WHERE id_pesanan = '$id_pesanan'");
    if (!$delete_detail) {
        die("Gagal menghapus detail pesanan: " . mysqli_error($conn));
    }

    // Hapus pesanan utama
    $delete_pesanan = mysqli_query($conn, "DELETE FROM tb_pesanan WHERE id_pesanan = '$id_pesanan'");
    if (!$delete_pesanan) {
        die("Gagal menghapus pesanan: " . mysqli_error($conn));
    }

    // Redirect dengan pesan sukses
    echo '<script>
            alert("Data Pesanan Berhasil Di Batalkan");
            </script>';
    header("Location:../pesanan");
    exit();
} else {
    echo '<script>
            alert("Data Pesanan Gagal Di Batalkan");
            </script>';
    header("Location:../pesanan");
    exit();
}
