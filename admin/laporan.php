<?php
include_once "proses/koneksi.php";

$db = new Database();
$conn = $db->getConnection();

$hasil = [];
$query = mysqli_query($conn, "SELECT * FROM tb_transaksi");
while ($record = mysqli_fetch_array($query)) {
    $hasil[] = $record;
}

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
            Halaman Laporan
        </div>
        <div class="card-body">
            <div class="row">
                <div style="display: flex; justify-content: end;">
                    <button class="btn btn-success" onclick="window.open('proses/cetak.php','_blank')">
                        <i class="bi bi-printer"></i> Cetak Laporan
                    </button>
                </div>
            </div>
            <?php
            if (empty($hasil)) {
                echo "Pesanan Kosong";
            } else {
                foreach ($hasil as $row) {
            ?>
                    <!-- Modal View-->
                    <div class="modal fade" id="Modal_Detail_Pesanan<?php echo $row['id_transaksi'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <?php

                        $id_pesanan = $row['id_pesanan'];
                        $detail_pesanan = [];
                        $query_detail_pesanan = mysqli_query($conn, "SELECT * FROM tb_detail_pesanan where id_pesanan = '$id_pesanan'");
                        while ($record = mysqli_fetch_array($query_detail_pesanan)) {
                            $detail_pesanan[] = $record;
                        }
                        $ambiltotal = mysqli_query($conn, "SELECT SUM(total_harga) AS total from tb_detail_pesanan where id_pesanan = '$id_pesanan'");
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
                                        <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                                        <input type="hidden" name="nama" value="<?php echo $nama; ?>">
                                        <input type="hidden" name="nohp" value="<?php echo $nohp; ?>">
                                        <input type="hidden" name="email" value="<?php echo $email; ?>">
                                        <input type="hidden" name="total" value="<?php echo $alltotal['total']; ?>">
                                        <a href="proses/invoicea.php?id=<?php echo $row['id_transaksi']; ?>" target="_blank" class="btn btn-secondary btn-sm">
                                            <i class="bi bi-printer"></i> Invoice
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Akhir reset -->
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
                                <th scope="col">Waktu Transaksi</th>
                                <th scope="col">Nama Pemesan</th>
                                <th scope="col">Nama Penerima</th>
                                <th scope="col">Alamat Tujuan</th>
                                <th scope="col">Metode Pembayaran</th>
                                <th scope="col">Total Harga</th>
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
                                    <td><?php echo $row['time_sampai'] ?></td>
                                    <td><?php echo $row['nama'] ?></td>
                                    <td><?php echo $row['nama_penerima'] ?></td>
                                    <td><?php echo $row['alamat_tujuan'] ?></td>
                                    <td><?php echo $row['metode_bayar'] ?></td>
                                    <td>Rp. <?php echo number_format($row['total'], 0, ',', '.') ?></td>
                                    <td>
                                        <div class="d-flex">
                                            <button class="btn btn-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#Modal_Detail_Pesanan<?php echo $row['id_transaksi'] ?>"><i class="bi bi-eye"></i></button>
                                            <a href="proses/hapus_laporan.php?id=<?php echo $row['id_transaksi']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
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