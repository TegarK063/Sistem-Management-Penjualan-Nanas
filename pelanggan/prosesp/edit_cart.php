<?php
include "../../admin/proses/koneksi.php"; // Pastikan file ini terhubung dengan database

$db = new Database();
$conn = $db->getConnection();

$id_cart = (isset($_POST['id_cart'])) ? htmlentities($_POST['id_cart']) : "";

$query_cart = mysqli_query($conn, "SELECT * FROM tb_cart where id_cart = '$id_cart'");
$record = mysqli_fetch_array($query_cart);
$nama_menu = $record['nama_menu'];
$harga = $record['harga'];
$jumlah = $record['jumlah'];
$id = $record['id'];

$query_daftar_menu = mysqli_query($conn, "SELECT * FROM tb_daftar_menu where id = '$id'");
$record_menu = mysqli_fetch_array($query_daftar_menu);
$stok_tersedia = $record_menu['stok'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Barang</h2>
        <form action="proses_cart.php" method="POST">
            <input type="hidden" name="index" value="<?php echo $index; ?>">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="nama_menu" value="<?php echo htmlspecialchars($nama_menu); ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="text" class="form-control" id="harga" name="harga" value="<?php echo htmlspecialchars($harga); ?>" min="1" disabled>
                <input type="hidden" name="harga" value="<?php echo htmlspecialchars($harga); ?>">
            </div>
            <div class="mb-3">
                <label for="jumlah_edit" class="form-label">Jumlah </label>
                <label for="stok_tersedia" class="form-label">Stok tersedia : <?php echo $stok_tersedia ?></label>
                <input type="number" class="form-control" id="jumlah_edit" name="jumlah_edit" value="<?php echo $jumlah; ?>" min="1" max="<?php echo $stok_tersedia ?>" required>
            </div>
            <input type="hidden" name="id_cart" value="<?php echo $id_cart; ?>">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="../cart.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>