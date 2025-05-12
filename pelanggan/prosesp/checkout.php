<?php
    
include("../../admin/proses/koneksi.php");

$db = new Database();
$conn = $db->getConnection();

$id_user = $_POST['id_user'] ?? '';
$email = $_POST['email'] ?? '';
$nama = $_POST['nama'] ?? '';
$nohp = $_POST['nohp'] ?? '';
$nama_penerima = $_POST['nama_penerima'] ?? '';
$alamat_tujuan = $_POST['alamat_tujuan'] ?? '';
$metode_bayar = $_POST['metode_bayar'] ?? '';
$total = $_POST['total'] ?? '';
$status_pembayaran = "Menunggu Pembayaran";

if (empty($nama_penerima) || empty($alamat_tujuan) || empty($metode_bayar)) {
    echo '<script>alert("Mohon lengkapi data!"); window.location.href = "#";</script>';
    exit();
}

// Mengambil data dari keranjang
$data_cart = mysqli_query($conn, "SELECT * FROM tb_cart WHERE id_user = '$id_user'");
$row_cart = mysqli_fetch_all($data_cart, MYSQLI_ASSOC);

if ($row_cart) {
    // Menyimpan pesanan ke tb_pesanan
    $query = mysqli_query($conn, "INSERT INTO tb_pesanan (id_user, email, nama, nohp, nama_penerima, alamat_tujuan, metode_bayar, total, status_pembayaran) VALUES ('$id_user', '$email', '$nama', '$nohp', '$nama_penerima', '$alamat_tujuan', '$metode_bayar', '$total', '$status_pembayaran')");
    if (!$query) {
        die("Error: " . mysqli_error($conn));
    }

    // Mendapatkan id_pesanan yang baru saja dimasukkan
    $id_pesanan = mysqli_insert_id($conn);

    foreach ($row_cart as $record) {
        $id = $record['id'];
        $nama_menu = $record['nama_menu'];
        $jumlah = $record['jumlah'];
        $harga = $record['harga'];
        $total_harga = $record['total_harga'];
        $query = mysqli_query($conn, "INSERT INTO tb_detail_pesanan (id_pesanan, id_user, id, nama_menu, jumlah, harga, total_harga) VALUES ('$id_pesanan', '$id_user', '$id', '$nama_menu', '$jumlah', '$harga', '$total_harga')");

        $query_check_stok = mysqli_query($conn, "SELECT stok FROM tb_daftar_menu WHERE id = '$id'");
        $stok_data = mysqli_fetch_assoc($query_check_stok);
        $stok_sekarang = $stok_data['stok'];
        $stok_baru = $stok_sekarang - $jumlah;
        $query_update_stok = mysqli_query($conn, "UPDATE tb_daftar_menu SET stok = '$stok_baru' WHERE id = '$id'");
        if (!$query) {
            die("Error: " . mysqli_error($conn));
        }
    }


    // Menghapus data keranjang
    $query = mysqli_query($conn, "DELETE FROM tb_cart WHERE id_user = '$id_user'");
    if (!$query) {
        die("Error: " . mysqli_error($conn));
    }

    echo '<script>alert("Pesanan Diterima, Mohon Bayar Pesanan Anda"); window.location.href = "../homep.php";</script>';
} else {
    echo '<script>alert("Keranjang kosong!"); window.location.href = "../homep.php";</script>';
}
?>
