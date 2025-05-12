<?php
session_start();
include "../admin/proses/koneksi.php"; // Pastikan file ini terhubung dengan database

// Buat koneksi database
$db = new Database();
$conn = $db->getConnection();

// Periksa login
if (empty($_SESSION['username_nanas'])) {
    $profile = "Guest";
    // header('location:../admin/login');
    // exit();
} else {
    $profile = $_SESSION['username_nanas'];
}


// Ambil data pengguna yang sedang login
$query = mysqli_query($conn, "SELECT * FROM tb_admin WHERE username = '$profile'");
$hasil = mysqli_fetch_array($query);
// $id_user = $hasil['id_user'];


// if (!$hasil) {
//     header('location:../admin/login');
//     exit();
// }

// Tangani tombol "Tambah Keranjang"
if (isset($_POST['tambah_keranjang'])) {
    $id = $_POST['id'];
    $nama_menu = $_POST['nama_menu'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Simpan data produk ke sesi
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Periksa jika produk sudah ada di keranjang
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] += 1; // Tambah kuantitas
    } else {
        $_SESSION['cart'][$id] = [
            'id' => $id,
            'nama_menu' => $nama_menu,
            'harga' => $harga,
            'stok' => $stok,
            'qty' => 1 // Kuantitas default
        ];
    }

    echo "<script>alert('Produk berhasil ditambahkan ke keranjang!');</script>";
}

$query_menu = mysqli_query($conn, "SELECT * FROM tb_daftar_menu
LEFT JOIN tb_kategori ON tb_kategori.id_kategori = tb_daftar_menu.kategori LIMIT 6");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Penjualan Nanas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .banner {
            height: 70vh;
            background: url('../admin/assets/img/banner.jpg') no-repeat center center;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <?php include "navbar.php"; ?>

    <div class="container-fluid banner"></div>

    <!-- Modal Sudah di Keranjang -->
    <div class="modal fade" id="alreadyInCartModal" tabindex="-1" aria-labelledby="alreadyInCartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alreadyInCartModalLabel">Informasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Produk ini sudah ada di keranjang Anda.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="cart.php" class="btn btn-primary">Lihat Keranjang</a>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login Diperlukan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda harus login untuk menambahkan produk ke keranjang.
                </div>
                <div class="modal-footer">
                    <a href="../admin/login" class="btn btn-primary">Login</a>
                </div>
            </div>
        </div>
    </div>


    <!-- Produk -->
    <div class="container-fluid py-2 mt-3 mb-3">
        <div class="container text-center">
            <h3>Produk</h3>
            <div class="row mt-3">
                <div class="row gx-2 gy-3">
                    <?php while ($data = mysqli_fetch_array($query_menu)) { ?>
                        <div class="col-sm-6 col-md-4 mb-3">
                            <div class="card">
                                <img src="../admin/assets/img/<?php echo $data['foto']; ?>" class="card-img-top" alt="<?php echo $data['nama_menu']; ?>" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h4 class="card-title"><?php echo $data['nama_menu']; ?></h4>
                                    <p><?php echo $data['keterangan']; ?></p>
                                    <p>Kategori: <?php echo $data['kategori_menu']; ?></p>
                                    <p>Rp. <?php echo number_format($data['harga'], 0, ',', '.'); ?></p>
                                    <p>Stok: <?php echo $data['stok']; ?></p>
                                    <?php
                                    if ($profile !== "Guest"):
                                        if ($data['stok'] > 0) {
                                            $stok = $data['stok'];
                                        } else {
                                            $stok = "Stok Habis";
                                        } ?>
                                        <form method="POST" action="prosesp/tambah_cart.php">
                                            <input type="hidden" name="profile" value="<?php echo $profile; ?>">
                                            <input type="hidden" name="id_user" value="<?php echo $hasil['id_user']; ?>">
                                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                                            <input type="hidden" name="nama_menu" value="<?php echo $data['nama_menu']; ?>">
                                            <input type="hidden" name="harga" value="<?php echo $data['harga']; ?>">
                                            <input type="hidden" name="stok" value="<?php echo $stok; ?>">
                                            <?php
                                            $id_user = $hasil['id_user'];
                                            $id = $data['id'];
                                            if ($data['stok'] > 0): // Jika stok tersedia 
                                                $datacart = mysqli_query($conn, "select * from tb_cart where id_user = '$id_user' and id = '$id'");
                                                $cekdatacart = mysqli_num_rows($datacart);
                                                if ($cekdatacart > 0): //jika sudah ada di cart 
                                            ?>
                                                    <button type="button" name="tambah_keranjang" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#alreadyInCartModal">
                                                        <i class="bi bi-cart"></i> Sudah ada di keranjang
                                                    </button>
                                                <?php else: //jika belum ada di cart
                                                ?>
                                                    <button type="submit" name="tambah_keranjang" class="btn btn-success">
                                                        <i class="bi bi-cart"></i> Tambah Keranjang
                                                    </button>
                                                <?php endif; ?>
                                            <?php else: // Jika stok habis 
                                            ?>
                                                <button class="btn btn-warning" disabled>
                                                    <i class="bi bi-cart"></i> Stok sedang habis
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    <?php else: ?>
                                        <button type="submit" name="tambah_keranjang" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#loginModal">
                                            <i class="bi bi-cart"></i> Login untuk memesan
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>