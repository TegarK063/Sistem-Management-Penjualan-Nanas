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

// mengambil data pesanan
$pesanan = [];
$query_pesanan = mysqli_query($conn, "SELECT * FROM tb_pesanan where id_user = '$id_user'");
while ($record = mysqli_fetch_array($query_pesanan)) {
    $pesanan[] = $record;
}
// mengambil data pesanan selesai
$pesanan_selesai = [];
$query_pesanan_selesai = mysqli_query($conn, "SELECT * FROM tb_transaksi where id_user = '$id_user'");
while ($record_selesai = mysqli_fetch_array($query_pesanan_selesai)) {
    $pesanan_selesai[] = $record_selesai;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .keterangan {
            display: grid;
            grid-template-columns: max-content auto;
            padding: 10px;
            gap: 0px 20px;
        }

        .btn {
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <?php include "navbar.php"; ?>
    <div class="container mt-5">
        <h2>Daftar Pesanan</h2>
        <!-- <?php
                // if (empty($keranjang)) {
                //     echo "Anda belum memesan apapun";
                ?>
        <br><br><a href="homep.php" class="btn btn-warning">Kembali</a>
        <a href="cart.php" class="btn btn-primary">Lihat cart</a>
        <?php
        // exit();
        // } else {}
        ?> -->

        <!-- Modal Tabel -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-nowrap">
                        <th scope="col">No</th>
                        <th scope="col">Waktu Memesan</th>
                        <th scope="col">Nama Penerima</th>
                        <th scope="col">Alamat Tujuan</th>
                        <th scope="col">Metode Bayar</th>
                        <th scope="col">Total</th>
                        <th scope="col">Status Pesanan</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($pesanan as $row_pesanan) {
                        $status = $row_pesanan['status_pembayaran'];
                    ?>
                        <tr>
                            <th scope="row"><?php echo $no++; ?></th>
                            <td><?php echo $row_pesanan['time'] ?></td>
                            <td><?php echo $row_pesanan['nama_penerima'] ?></td>
                            <td><?php echo $row_pesanan['alamat_tujuan'] ?></td>
                            <td><?php echo $row_pesanan['metode_bayar'] ?></td>
                            <td>Rp. <?php echo number_format($row_pesanan['total'], 0, ',', '.'); ?></td>
                            <td><?php echo $row_pesanan['status_pembayaran'] ?></td>
                            <td>
                                <div class="d-flex">
                                    <button class="btn btn-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#Modal_Detail_Pesanan<?php echo $row_pesanan['id_pesanan'] ?>"><i class="bi bi-eye"></i></button>
                                    <?php if ($status == "Sudah Sampai Di Tempat Tujuan"): ?>
                                        <button class="btn btn-success btn-sm me-1" data-bs-toggle="modal" data-bs-target="#Modal_Konfirmasi_Pesanan<?php echo $row_pesanan['id_pesanan'] ?>"><i class="bi">Konfirmasi Pesanan</i></button>
                                    <?php else: ?>
                                        <button class="btn btn-success btn-sm me-1" data-bs-toggle="modal" data-bs-target="#Modal_Konfirmasi_Pesanan<?php echo $row_pesanan['id_pesanan'] ?>" disabled><i class="bi">Konfirmasi Pesanan</i></button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                    <?php
                    $no = $no;
                    foreach ($pesanan_selesai as $row_pesanan_selesai) {
                    ?>

                        <tr>
                            <th scope="row"><?php echo $no++; ?></th>
                            <td><?php echo $row_pesanan_selesai['time_pesan'] ?></td>
                            <td><?php echo $row_pesanan_selesai['nama_penerima'] ?></td>
                            <td><?php echo $row_pesanan_selesai['alamat_tujuan'] ?></td>
                            <td><?php echo $row_pesanan_selesai['metode_bayar'] ?></td>
                            <td>Rp. <?php echo number_format($row_pesanan_selesai['total'], 0, ',', '.'); ?></td>
                            <td><?php echo $row_pesanan_selesai['status_pembayaran'] ?></td>
                            <td>
                                <div class="d-flex">
                                    <button class="btn btn-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#Modal_Detail_Pesanan_Selesai<?php echo $row_pesanan_selesai['id_pesanan'] ?>"><i class="bi bi-eye"></i></button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>



        <?php foreach ($pesanan_selesai as $row_pesanan_selesai) { ?>
            <form method="POST" action="prosesp/invoice.php">
                <!-- Modal View Selesai-->
                <div class="modal fade" id="Modal_Detail_Pesanan_Selesai<?php echo $row_pesanan_selesai['id_pesanan'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <?php

                    $id_pesanan_selesai = $row_pesanan_selesai['id_pesanan'];
                    $detail_pesanan = [];
                    $query_detail_pesanan = mysqli_query($conn, "SELECT * FROM tb_detail_pesanan where id_pesanan = '$id_pesanan_selesai'");
                    while ($record = mysqli_fetch_array($query_detail_pesanan)) {
                        $detail_pesanan[] = $record;
                    }
                    $ambiltotal = mysqli_query($conn, "SELECT SUM(total_harga) AS total from tb_detail_pesanan where id_pesanan = '$id_pesanan_selesai'");
                    $alltotal = mysqli_fetch_assoc($ambiltotal); // Ambil hasil query

                    ?>
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
                                        <input type="text" class="form-control" id="floatingInput" placeholder="nama_penerima" name="nama_penerima" value="<?php echo $row_pesanan_selesai['nama_penerima'] ?>" disabled>
                                        <label for="floatingInput">Nama Penerima</label>
                                        <div class="invalid-feedback">
                                            Silahkan Masukkan Nama yang akan menerima pesanan.
                                        </div>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="alamat_tujuan" value="<?php echo $row_pesanan_selesai['alamat_tujuan'] ?>" disabled>
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
                                            if (empty($detail_pesanan)) {
                                                echo "Pesanan kosong";
                                            } else {
                                                foreach ($detail_pesanan as $row) {
                                            ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['nama_menu']); ?></td>
                                                        <td>Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                                        <td><?php echo htmlspecialchars($row['jumlah']); ?></td>
                                                        <td>Rp. <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                                    <td><strong>Rp. <?php echo number_format($alltotal['total'], 0, ',', '.'); ?></strong></td>
                                                </tr>
                                        </tbody>
                                    <?php
                                            }
                                    ?>
                                    </table>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="metode_bayar" value="<?php echo $row_pesanan_selesai['metode_bayar'] ?>" disabled>
                                        <label for="floatingInput">Metode Pembayaran</label>
                                        <div class="invalid-feedback">
                                            Silahkan Masukkan Alamat Tujuan.
                                        </div>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="status_pembayaran" value="<?php echo $row_pesanan_selesai['status_pembayaran'] ?>" disabled>
                                        <label for="floatingInput">Status Pesanan</label>
                                        <div class="invalid-feedback">
                                            Silahkan Masukkan Alamat Tujuan.
                                        </div>
                                    </div>
                                    <form action="prosesp/invoice.php">
                                        <input type="hidden" name="id_pes" value="<?php echo $row_pesanan_selesai['id_pesanan']; ?>">
                                        <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                                        <input type="hidden" name="nama" value="<?php echo $nama; ?>">
                                        <input type="hidden" name="nohp" value="<?php echo $nohp; ?>">
                                        <input type="hidden" name="email" value="<?php echo $email; ?>">
                                        <input type="hidden" name="total" value="<?php echo $alltotal['total']; ?>">
                                        <button type="submit" name="invoice" class="btn btn-secondary"><i class="bi bi-printer"></i>Invoice</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </form>

            <!-- Modal View-->
            <form method="POST" action="prosesp/batalkan_pesanan.php">
                <?php
                foreach ($pesanan as $row_pesanan) {

                    $id_pesanan = $row_pesanan['id_pesanan'];
                    $status = $row_pesanan['status_pembayaran'];
                    $detail_pesanan = [];
                    $query_detail_pesanan = mysqli_query($conn, "SELECT * FROM tb_detail_pesanan where id_pesanan = '$id_pesanan'");
                    while ($record = mysqli_fetch_array($query_detail_pesanan)) {
                        $detail_pesanan[] = $record;
                    }
                    $ambiltotal = mysqli_query($conn, "SELECT SUM(total_harga) AS total from tb_detail_pesanan where id_pesanan = '$id_pesanan'");
                    $alltotal = mysqli_fetch_assoc($ambiltotal); // Ambil hasil query

                ?>
                    <div class="modal fade" id="Modal_Detail_Pesanan<?php echo $row_pesanan['id_pesanan'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                            <input type="text" class="form-control" id="floatingInput" placeholder="nama_penerima" name="nama_penerima" value="<?php echo $row_pesanan['nama_penerima'] ?>" disabled>
                                            <label for="floatingInput">Nama Penerima</label>
                                            <div class="invalid-feedback">
                                                Silahkan Masukkan Nama yang akan menerima pesanan.
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="alamat_tujuan" value="<?php echo $row_pesanan['alamat_tujuan'] ?>" disabled>
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
                                                if (empty($detail_pesanan)) {
                                                    echo "Pesanan kosong";
                                                } else {
                                                    foreach ($detail_pesanan as $row) {
                                                ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($row['nama_menu']); ?></td>
                                                            <td>Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                                            <td><?php echo htmlspecialchars($row['jumlah']); ?></td>
                                                            <td>Rp. <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                                                        <td><strong>Rp. <?php echo number_format($alltotal['total'], 0, ',', '.'); ?></strong></td>
                                                    </tr>
                                            </tbody>
                                        <?php
                                                }
                                        ?>
                                        </table>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="metode_bayar" value="<?php echo $row_pesanan['metode_bayar'] ?>" disabled>
                                            <label for="floatingInput">Metode Pembayaran</label>
                                            <div class="invalid-feedback">
                                                Silahkan Masukkan Alamat Tujuan.
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="status_pembayaran" value="<?php echo $row_pesanan['status_pembayaran'] ?>" disabled>
                                            <label for="floatingInput">Status Pesanan</label>
                                            <div class="invalid-feedback">
                                                Silahkan Masukkan Alamat Tujuan.
                                            </div>
                                        </div>
                                        <input type="hidden" name="id_pesanan" value="<?php echo $id_pesanan; ?>">
                                        <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                                        <input type="hidden" name="nama" value="<?php echo $nama; ?>">
                                        <input type="hidden" name="nohp" value="<?php echo $nohp; ?>">
                                        <input type="hidden" name="email" value="<?php echo $email; ?>">
                                        <input type="hidden" name="total" value="<?php echo $alltotal['total']; ?>">
                                        <?php if ($status == "Menunggu Pembayaran"): ?>
                                            <button type="submit" name="batalkan_pesanan" class="btn btn-danger">
                                                <i class="bi bi-cart"></i> Batalkan Pesanan
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" name="batalkan_pesanan" class="btn btn-danger" disabled>
                                                <i class="bi bi-cart"></i> Batalkan Pesanan
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>


            <!-- Modal Konfirmasi Pesanan-->
            <form method="POST" action="prosesp/konfirmasi_pesanan.php">
                <div class="modal fade" id="Modal_Konfirmasi_Pesanan<?php echo $row_pesanan['id_pesanan'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                        <input type="text" class="form-control" id="floatingInput" placeholder="nama_penerima" name="nama_penerima" value="<?php echo $row_pesanan['nama_penerima'] ?>" disabled>
                                        <label for="floatingInput">Nama Penerima</label>
                                        <div class="invalid-feedback">
                                            Silahkan Masukkan Nama yang akan menerima pesanan.
                                        </div>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="alamat_tujuan" value="<?php echo $row_pesanan['alamat_tujuan'] ?>" disabled>
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
                                            if (empty($detail_pesanan)) {
                                                echo "Pesanan kosong";
                                            } else {
                                                foreach ($detail_pesanan as $row) {
                                            ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['nama_menu']); ?></td>
                                                        <td>Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                                        <td><?php echo htmlspecialchars($row['jumlah']); ?></td>
                                                        <td>Rp. <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                                    <td><strong>Rp. <?php echo number_format($alltotal['total'], 0, ',', '.'); ?></strong></td>
                                                </tr>
                                        </tbody>
                                    <?php
                                            }
                                    ?>
                                    </table>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="metode_bayar" value="<?php echo $row_pesanan['metode_bayar'] ?>" disabled>
                                        <label for="floatingInput">Metode Pembayaran</label>
                                        <div class="invalid-feedback">
                                            Silahkan Masukkan Alamat Tujuan.
                                        </div>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="status_pembayaran" value="<?php echo $row_pesanan['status_pembayaran'] ?>" disabled>
                                        <label for="floatingInput">Status Pesanan</label>
                                        <p style="margin-top: 10px; margin-left: 10px;">waktu sampai : <?php echo $row_pesanan['waktu_sampai'] ?></p>
                                        <div class="invalid-feedback">
                                            Silahkan Masukkan Alamat Tujuan.
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_pesanan" value="<?php echo $row_pesanan['id_pesanan']; ?>">
                                    <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                                    <input type="hidden" name="nama" value="<?php echo $nama; ?>">
                                    <input type="hidden" name="nohp" value="<?php echo $nohp; ?>">
                                    <input type="hidden" name="email" value="<?php echo $email; ?>">
                                    <input type="hidden" name="total" value="<?php echo $alltotal['total']; ?>">
                                    <button type="submit" name="konfirmasi_pesanan" class="btn btn-success">
                                        <i class="bi bi-cart"></i> Konfirmasi Pesanan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                }
            ?>
            </form>




            <a href="cart.php" class="btn btn-primary">Lihat Keranjang</a>
            <a href="homep.php" class="btn btn-warning">Kembali</a>
    </div>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
</body>

</html>