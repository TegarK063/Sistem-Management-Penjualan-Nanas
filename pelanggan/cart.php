<?php
session_start();
include "../admin/proses/koneksi.php"; // Pastikan file ini terhubung dengan database

// Cek apakah keranjang kosong
// if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
//     echo "<p>Keranjang Anda kosong!</p>";
//     exit();
// }

$db = new Database();
$conn = $db->getConnection();

// Periksa login
if (empty($_SESSION['username_nanas'])) {
    header('location:../admin/login');
    exit();
}

// Ambil data pengguna yang sedang login
$query = mysqli_query($conn, "SELECT * FROM tb_admin WHERE username = '$_SESSION[username_nanas]'");
$hasil = mysqli_fetch_array($query);
$id_user = $hasil['id_user'];
$email = $hasil['username'];
$nama = $hasil['nama'];
$nohp = $hasil['nohp'];
$alamat = $hasil['alamat'];

if (!$hasil) {
    header('location:../admin/login');
    exit();
}

// mengambil data keranjang
$keranjang = [];
$query_keranjang = mysqli_query($conn, "SELECT * FROM tb_cart where id_user = '$id_user'");
while ($record = mysqli_fetch_array($query_keranjang)) {
    $keranjang[] = $record;
}
$ambiltotal = mysqli_query($conn, "SELECT SUM(total_harga) AS total from tb_cart where id_user = '$id_user'");
$alltotal = mysqli_fetch_assoc($ambiltotal); // Ambil hasil query

// include "navbar.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .keterangan{
            display: grid;
            grid-template-columns: max-content auto;
            padding: 10px;
            gap: 0px 20px;
        }
        .btn{
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <?php include "navbar.php"; ?>
    <div class="container mt-5">
        <h2>Keranjang Belanja</h2>
        <!-- Modal Tabel -->
        <table class="table table-bordered">
                <?php
                if (empty($keranjang)) {
                    echo "Keranjang Kosong";
                ?>
                <br><br><a href="homep.php" class="btn btn-warning">Kembali</a>
                <a href="pesanan.php" class="btn btn-primary">Lihat Pesanan</a>
                <?php
                exit();
                ?>
                <?php
                } else {
                ?>
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($keranjang as $row) {
                        $stok_di_keranjang = $row['jumlah'];
                        $id = $row['id'];
                        $data_menu = mysqli_query($conn, "SELECT stok FROM tb_daftar_menu WHERE id = '$id'");
                        $row_data_menu = mysqli_fetch_assoc($data_menu);
                        $stok_tersedia = $row_data_menu['stok'];
                ?>

                        <tr>
                            <td><?php echo htmlspecialchars($row['nama_menu']); ?></td>
                            <td>Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                            <?php if($stok_di_keranjang <= $stok_tersedia) : ?>
                            <td><?php echo htmlspecialchars($row['jumlah']); ?></td>
                            <?php elseif ($stok_di_keranjang > $stok_tersedia): ?>
                            <td><?php echo htmlspecialchars($row['jumlah']); ?> <b>(yang tersedia : <?php echo $stok_tersedia ?>)</b></td>
                            <?php endif; ?>
                            <td>Rp. <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                            <td>
                                <!-- Tombol Edit Barang -->
                                <form action="prosesp/edit_cart.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_cart" value="<?php echo $row['id_cart']; ?>">
                                    <button type="submit" class="btn btn-sm btn-success" value="123">Edit</button>
                                </form>
                                <!-- Tombol Hapus Barang -->
                                <form action="prosesp/hapus_cart.php?" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_cart" value="<?php echo $row['id_cart']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                        <td><strong>Rp. <?php echo number_format($alltotal['total'], 0, ',', '.'); ?></strong></td>
                        <td></td>
                    </tr>
            </tbody>
        </table>
        <?php
        $cek_stok = 0;
        foreach ($keranjang as $row) {
            $stok_di_keranjang = $row['jumlah'];
            $id = $row['id'];
            $data_menu = mysqli_query($conn, "SELECT stok FROM tb_daftar_menu WHERE id = '$id'");
            $row_data_menu = mysqli_fetch_assoc($data_menu);
            $stok_tersedia = $row_data_menu['stok'];
            if($stok_di_keranjang > $stok_tersedia) :
                $cek_stok = $cek_stok + 1;
            endif;
        }
        if ($cek_stok > 0) : ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal_Checkout" disabled>Checkout</button>
        <?php elseif ($cek_stok == 0): ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal_Checkout">Checkout</button>
        <?php endif; ?>
        <!-- <a href="checkout.php" class="btn btn-primary" data-bs-target="#Modal_Tambah_Menu">Checkout</a> -->
        <a href="pesanan.php" class="btn btn-primary">Lihat Pesanan</a> 
            <?php
            }
            ?>
        <a href="homep.php" class="btn btn-warning">Kembali</a>
        
        <!-- Modal Checkout-->
        <form method="POST" action="prosesp/checkout.php">
            <div class="modal fade" id="Modal_Checkout" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Pesanan</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="keterangan">
                                    <div class="item-keterangan">
                                        <p>Nama</p>
                                        <p>No HP</p>
                                        <p>Email</p>
                                    </div>
                                    <div class="item-keterangan">
                                        <p>: <?php echo $nama; ?></p>
                                        <p>: <?php echo $nohp; ?></p>
                                        <p>: <?php echo $email; ?></p>
                                    </div>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" placeholder="nama_penerima" name="nama_penerima" required>
                                    <label for="floatingInput">Nama Penerima</label>
                                    <div class="invalid-feedback">
                                        Silahkan Masukkan Nama yang akan menerima pesanan.
                                    </div>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="alamat_tujuan" required>
                                    <label for="floatingInput">Alamat Tujuan</label>
                                    <div class="invalid-feedback">
                                        Silahkan Masukkan Alamat Tujuan.
                                    </div>
                                </div>
                                <p>Produk yang dipesan :</p>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        if (empty($keranjang)) {
                                            echo "Keranjang masing kosong";
                                        } else {
                                            foreach ($keranjang as $row) {
                                                $stok_di_keranjang = $row['jumlah'];
                                                $id = $row['id'];
                                                $data_menu = mysqli_query($conn, "SELECT stok FROM tb_daftar_menu WHERE id = '$id'");
                                                $row_data_menu = mysqli_fetch_assoc($data_menu);
                                                $stok_tersedia = $row_data_menu['stok'];
                                        ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['nama_menu']); ?></td>
                                                    <td>Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                                    <?php if($stok_di_keranjang <= $stok_tersedia) : ?>
                                                    <td><?php echo htmlspecialchars($row['jumlah']); ?></td>
                                                    <?php elseif ($stok_di_keranjang > $stok_tersedia): ?>
                                                    <td><?php echo htmlspecialchars($row['jumlah']); ?> <b>(yang tersedia : <?php echo $stok_tersedia ?>)</b></td>
                                                    <?php endif; ?>
                                                    <td>Rp. <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Total</strong></td>
                                                <td><strong>Rp. <?php echo number_format($alltotal['total'], 0, ',', '.'); ?></strong></td>
                                            </tr>
                                    </tbody>
                                </table>
                                <div class="form-floating mb-3">
                                    <select class="form-select" aria-label="Default select example" name="metode_bayar" id="metodeBayar">
                                        <option selected hidden value="">Pilih Metode Pembayaran</option>
                                        <option value="Dana">Dana</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                    <label for="floatingInput">Metode Pembayaran</label>
                                    <div class="invalid-feedback">
                                        Silahkan Pilih Menu Kategori.
                                    </div>
                                </div>

                                <!-- Div untuk menampilkan nomor rekening -->
                                <div id="noRekening" style="display: none; margin-top: 10px; margin-left: 10px;">
                                    <label for="rekening">Nomor Rekening:</label>
                                    <p id="rekening">081234567890</p>
                                </div>


                                <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                                <input type="hidden" name="nama" value="<?php echo $nama; ?>">
                                <input type="hidden" name="nohp" value="<?php echo $nohp; ?>">
                                <input type="hidden" name="email" value="<?php echo $email; ?>">
                                <input type="hidden" name="total" value="<?php echo $alltotal['total']; ?>">
                                <button type="submit" name="tambah_keranjang" class="btn btn-success">
                                    <i class="bi bi-cart"></i> Pesan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
                                        <?php
                                        }
                                        ?>
    </div>

    <!-- script untuk menampilkan norekening -->
    <script>
    document.getElementById("metodeBayar").addEventListener("change", function () {
        const metode = this.value;
        const noRekeningDiv = document.getElementById("noRekening");
        
        if (metode === "Dana") {
            noRekeningDiv.style.display = "block"; // Tampilkan nomor rekening
        } else {
            noRekeningDiv.style.display = "none"; // Sembunyikan jika bukan Dana
        }
    });
    </script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
</body>

</html>