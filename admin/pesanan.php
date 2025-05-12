<?php
include_once "proses/koneksi.php";

$db = new Database();
$conn = $db->getConnection();

$hasil = [];
$query = mysqli_query($conn, "SELECT * FROM tb_pesanan");
while ($record = mysqli_fetch_array($query)) {
    $hasil[] = $record;
}

$pilih_kategori = mysqli_query($conn, "SELECT id_kategori,kategori_menu FROM tb_kategori");



?>

<style>
    .keterangan {
        display: grid;
        grid-template-columns: max-content auto;
        padding: 10px;
        gap: 0px 20px;
    }
</style>
<div class="col-lg-9 mt-2">
    <div class="card">
        <div class="card-header">
            Halaman Pesanan Masuk
        </div>
        <div class="card-body">

            <?php
            if (empty($hasil)) {
                echo "Pesanan Kosong";
            } else {
                foreach ($hasil as $row) {
                    $id_pesanan = $row['id_pesanan'];
                    $detail_pesanan = [];
                    $query_detail_pesanan = mysqli_query($conn, "SELECT * FROM tb_detail_pesanan where id_pesanan = '$id_pesanan'");
                    while ($record = mysqli_fetch_array($query_detail_pesanan)) {
                        $detail_pesanan[] = $record;
                    }
                    $ambiltotal = mysqli_query($conn, "SELECT SUM(total_harga) AS total from tb_detail_pesanan where id_pesanan = '$id_pesanan'");
                    $alltotal = mysqli_fetch_assoc($ambiltotal); // Ambil hasil query
            ?>



                    <!-- Modal Edit-->
                    <div class="modal fade" id="Modal_Edit_Pesanan<?php echo $row['id_pesanan'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <form class="needs-validation" action="proses/edit_pesanan.php" method="post">
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
                                                    <p>: <?php echo $row['nama']; ?></p>
                                                    <p>: <?php echo $row['nohp']; ?></p>
                                                    <p>: <?php echo $row['email']; ?></p>
                                                </div>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="floatingInput" placeholder="nama_penerima" name="nama_penerima" value="<?php echo $row['nama_penerima'] ?>" disabled>
                                                <label for="floatingInput">Nama Penerima</label>
                                                <div class="invalid-feedback">
                                                    Silahkan Masukkan Nama yang akan menerima pesanan.
                                                </div>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="alamat_tujuan" value="<?php echo $row['alamat_tujuan'] ?>" disabled>
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
                                                        foreach ($detail_pesanan as $row2) {
                                                    ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($row2['nama_menu']); ?></td>
                                                                <td>Rp. <?php echo number_format($row2['harga'], 0, ',', '.'); ?></td>
                                                                <td><?php echo htmlspecialchars($row2['jumlah']); ?></td>
                                                                <td>Rp. <?php echo number_format($row2['total_harga'], 0, ',', '.'); ?></td>
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
                                                <input type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="metode_bayar" value="<?php echo $row['metode_bayar'] ?>" disabled>
                                                <label for="floatingInput">Metode Pembayaran</label>
                                                <div class="invalid-feedback">
                                                    Silahkan Masukkan Alamat Tujuan.
                                                </div>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <select class="form-select" aria-label="Default select example" name="status_pembayaran">
                                                    <option selected hidden value="<?php echo $row['status_pembayaran'] ?>"><?php echo $row['status_pembayaran'] ?></option>
                                                    <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
                                                    <option value="Dikemas">Dikemas</option>
                                                    <option value="Dalam Perjalanan">Dalam Perjalanan</option>
                                                    <option value="Sudah Sampai Di Tempat Tujuan">Sudah Sampai Di Tempat Tujuan</option>
                                                </select>
                                                <label for="floatingInput">Status Pesanan</label>
                                                <div class="invalid-feedback">
                                                    Silahkan Masukkan Alamat Tujuan.
                                                </div>
                                            </div>
                                            <input type="hidden" name="id_pesanan" value="<?php echo $row['id_pesanan'] ?>">
                                            <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                                            <input type="hidden" name="nama" value="<?php echo $nama; ?>">
                                            <input type="hidden" name="nohp" value="<?php echo $nohp; ?>">
                                            <input type="hidden" name="email" value="<?php echo $email; ?>">
                                            <input type="hidden" name="total" value="<?php echo $alltotal['total']; ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="submit_menu" value="12345">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Modal View-->
                    <div class="modal fade" id="Modal_Detail_Pesanan<?php echo $row['id_pesanan'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                <p>: <?php echo $row['nama']; ?></p>
                                                <p>: <?php echo $row['nohp']; ?></p>
                                                <p>: <?php echo $row['email']; ?></p>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="nama_penerima" name="nama_penerima" value="<?php echo $row['nama_penerima'] ?>" disabled>
                                            <label for="floatingInput">Nama Penerima</label>
                                            <div class="invalid-feedback">
                                                Silahkan Masukkan Nama yang akan menerima pesanan.
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="alamat_tujuan" value="<?php echo $row['alamat_tujuan'] ?>" disabled>
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
                                                    foreach ($detail_pesanan as $row2) {
                                                ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($row2['nama_menu']); ?></td>
                                                            <td>Rp. <?php echo number_format($row2['harga'], 0, ',', '.'); ?></td>
                                                            <td><?php echo htmlspecialchars($row2['jumlah']); ?></td>
                                                            <td>Rp. <?php echo number_format($row2['total_harga'], 0, ',', '.'); ?></td>
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
                                            <input type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="metode_bayar" value="<?php echo $row['metode_bayar'] ?>" disabled>
                                            <label for="floatingInput">Metode Pembayaran</label>
                                            <div class="invalid-feedback">
                                                Silahkan Masukkan Alamat Tujuan.
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="status_pembayaran" value="<?php echo $row['status_pembayaran'] ?>" disabled>
                                            <label for="floatingInput">Status Pesanan</label>
                                            <div class="invalid-feedback">
                                                Silahkan Masukkan Alamat Tujuan.
                                            </div>
                                        </div>
                                        <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                                        <input type="hidden" name="nama" value="<?php echo $nama; ?>">
                                        <input type="hidden" name="nohp" value="<?php echo $nohp; ?>">
                                        <input type="hidden" name="email" value="<?php echo $email; ?>">
                                        <input type="hidden" name="total" value="<?php echo $alltotal['total']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Akhir View -->

                    <!-- Modal batalkan-->
                    <div class="modal fade" id="Modal_Batal_Pesanan<?php echo $row['id_pesanan'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-fullscreen-md-down">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Pembatalan Pesanan</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="needs-validation" novalidate action="proses/batalkan_pesanan.php" method="post">
                                        <input type="hidden" value="<?php echo $row['id_pesanan'] ?>" name="id_pesanan">
                                        <div class="col-lg-12">
                                            Apakah Anda yakin ingin membatalkan pesanan ini?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-danger" name="batalkan_pesanan" value="12345">Batalkan Pesanan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Akhir batalkan -->

                <?php
                }
                ?>
                <?php

                ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-nowrap">
                                <th scope="col">No</th>
                                <th scope="col">Waktu Pemesanan</th>
                                <th scope="col">Nama Pemesan</th>
                                <th scope="col">Metode Pembayaran</th>
                                <th scope="col">Total Harga</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($hasil as $row) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $no++; ?></th>
                                    <td><?php echo $row['time'] ?></td>
                                    <td><?php echo $row['nama'] ?></td>
                                    <td><?php echo $row['metode_bayar'] ?></td>
                                    <td>Rp. <?php echo number_format($row['total'], 0, ',', '.') ?></td>
                                    <td><?php echo $row['status_pembayaran'] ?></td>
                                    <td>
                                        <div class="d-flex">
                                            <button class="btn btn-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#Modal_Detail_Pesanan<?php echo $row['id_pesanan'] ?>"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#Modal_Edit_Pesanan<?php echo $row['id_pesanan'] ?>"><i class="bi bi-pencil-square"></i></button>
                                            <button class="btn btn-danger btn-sm me-1" data-bs-toggle="modal" data-bs-target="#Modal_Batal_Pesanan<?php echo $row['id_pesanan'] ?>"><i class="bi bi-x-circle"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>