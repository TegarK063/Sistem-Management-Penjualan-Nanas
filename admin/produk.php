<?php
include_once "proses/koneksi.php";

$db = new Database();
$conn = $db->getConnection();

$hasil = [];
$query = mysqli_query($conn, "SELECT * FROM tb_daftar_menu
    LEFT JOIN tb_kategori ON tb_kategori.id_kategori = tb_daftar_menu.kategori");
while ($record = mysqli_fetch_array($query)) {
    $hasil[] = $record;
}

$pilih_kategori = mysqli_query($conn, "SELECT id_kategori,kategori_menu FROM tb_kategori");

$level_nanas = isset($_SESSION['level_nanas']) ? $_SESSION['level_nanas'] : 0; // Level user, default 0 jika tidak ada
?>

<div class="col-lg-9 mt-2">
    <div class="card">
        <div class="card-header">
            Halaman Produk
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col d-flex justify-content-end">
                    <?php if ($level_nanas <= 1): ?>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal_Tambah_Menu"> Tambah Menu</button>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Modal Tambah Menu-->
            <div class="modal fade" id="Modal_Tambah_Menu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Menu</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="proses/input_menu.php" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="input-group mb-3">
                                            <input type="File" class="form-control py-3" id="uploadFoto" placeholder="nama mu" name="foto" required>
                                            <label class="input-group-text" for="uploadFoto">Upload Foto</label>
                                            <div class="invalid-feedback">
                                                Silahkan Masukkan File Foto.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="nama menu" name="nama_menu" required>
                                            <label for="floatingInput">Nama Menu</label>
                                            <div class="invalid-feedback">
                                                Silahkan Masukkan Nama Menu.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="keterangan">
                                            <label for="floatingInput">Keterangan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" aria-label="Default select example" name="kategori_menu" required>
                                                <option selected hidden value="">Pilih Kategori Menu</option>
                                                <?php foreach ($pilih_kategori as $hasil1)
                                                    echo "<option value=" . $hasil1['id_kategori'] . ">$hasil1[kategori_menu]</option>";
                                                ?>
                                            </select>
                                            <label for="floatingInput">Kategori</label>
                                            <div class="invalid-feedback">
                                                Silahkan Pilih Menu Kategori.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input required type="number" class="form-control" id="floatingInput" placeholder="Harga" name="harga">
                                            <label for="floatingInput">Harga</label>
                                            <div class="invalid-feedback">
                                                Silahkan Isi Harga.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input required type="number" class="form-control" id="floatingInput" placeholder="stok" name="stok">
                                            <label for="floatingInput">Stok</label>
                                            <div class="invalid-feedback">
                                                Silahkan Isi Stok.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="submit_menu" value="12345">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir Tambah Menu -->

            <?php
            if (empty($hasil)) {
                echo "Data Menu Yang Dicari Tidak Ada";
            } else {
                foreach ($hasil as $row) {
            ?>
                    <!-- Modal View-->
                    <div class="modal fade" id="Modal_View_Menu<?php echo $row['id'] ?>" tabindex=" -1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Menu</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="needs-validation" novalidate action="proses/input_menu.php" method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-floating mb-3">
                                                    <input disabled type="text" class="form-control" id="floatingInput" placeholder="nama menu" name="nama_menu" value="<?php echo $row['nama_menu'] ?>">
                                                    <label for="floatingInput">Nama Menu</label>
                                                    <div class="invalid-feedback">
                                                        Silahkan Masukkan Nama Menu.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-floating mb-3">
                                                    <input disabled type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="keterangan" value="<?php echo $row['keterangan'] ?>">
                                                    <label for="floatingInput">Keterangan</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-floating mb-3">
                                                    <select disabled class="form-select" aria-label="Default select example" name="kategori_menu">
                                                        <option selected hidden value="">Pilih Kategori Menu</option>
                                                        <?php foreach ($pilih_kategori as $hasil1)
                                                            if ($row['kategori'] == $hasil1['id_kategori']) {
                                                                echo "<option selected value=" . $hasil1['id_kategori'] . ">$hasil1[kategori_menu]</option>";
                                                            } else {
                                                                echo "<option value=" . $hasil1['id_kategori'] . ">$hasil1[kategori_menu]</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                    <label for="floatingInput">Kategori</label>
                                                    <div class="invalid-feedback">
                                                        Silahkan Pilih Menu Kategori.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-floating mb-3">
                                                    <input disabled type="number" class="form-control" id="floatingInput" placeholder="Harga" name="harga" value="<?php echo number_format($row['harga'], 0, ',', '.') ?>">
                                                    <label for="floatingInput">Harga</label>
                                                    <div class="invalid-feedback">
                                                        Silahkan Isi Harga.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-floating mb-3">
                                                    <input disabled type="number" class="form-control" id="floatingInput" placeholder="stok" name="stok" value="<?php echo $row['stok'] ?>">
                                                    <label for="floatingInput">Stok</label>
                                                    <div class="invalid-feedback">
                                                        Silahkan Isi Stok.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Akhir View -->

                    <!-- Modal Edit-->
                    <div class="modal fade" id="Modal_Edit_Menu<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Menu</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="needs-validation" novalidate action="proses/edit_menu.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="input-group mb-3">
                                                    <input type="File" class="form-control py-3" id="uploadFoto" placeholder="nama mu" name="foto">
                                                    <label class="input-group-text" for="uploadFoto">Upload Foto</label>
                                                    <div class="invalid-feedback">
                                                        Silahkan Masukkan File Foto.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="floatingInput" placeholder="nama menu" name="nama_menu" value="<?php echo $row['nama_menu'] ?>" required>
                                                    <label for="floatingInput">Nama Menu</label>
                                                    <div class="invalid-feedback">
                                                        Silahkan Masukkan Nama Menu.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="floatingInput" placeholder="keterangan" name="keterangan" value="<?php echo $row['keterangan'] ?>">
                                                    <label for="floatingInput">Keterangan</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-floating mb-3">
                                                    <select class="form-select" aria-label="Default select example" name="kategori_menu">
                                                        <option selected hidden value="">Pilih Kategori Menu</option>
                                                        <?php foreach ($pilih_kategori as $hasil1)
                                                            if ($row['kategori'] == $hasil1['id_kategori']) {
                                                                echo "<option selected value=" . $hasil1['id_kategori'] . ">$hasil1[kategori_menu]</option>";
                                                            } else {
                                                                echo "<option value=" . $hasil1['id_kategori'] . ">$hasil1[kategori_menu]</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                    <label for="floatingInput">Kategori</label>
                                                    <div class="invalid-feedback">
                                                        Silahkan Pilih Menu Kategori.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-floating mb-3">
                                                    <input required type="number" class="form-control" id="floatingInput" placeholder="Harga" name="harga" value="<?php echo $row['harga'] ?>">
                                                    <label for="floatingInput">Harga</label>
                                                    <div class="invalid-feedback">
                                                        Silahkan Isi Harga.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-floating mb-3">
                                                    <input required type="number" class="form-control" id="floatingInput" placeholder="stok" name="stok" value="<?php echo $row['stok'] ?>">
                                                    <label for="floatingInput">Stok</label>
                                                    <div class="invalid-feedback">
                                                        Silahkan Isi Stok.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" name="submit_menu" value="12345">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Akhir Edit -->

                    <!-- Modal Delete-->
                    <div class="modal fade" id="Modal_Delete<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-fullscreen-md-down">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Menu</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="needs-validation" novalidate action="proses/delete_menu.php" method="post">
                                        <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
                                        <input type="hidden" value="<?php echo $row['foto'] ?>" name="foto">
                                        <div class="col-lg-12">
                                            Apakah Ingin Menghapus Menu <b><?php echo $row['nama_menu'] ?></b>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger" name="delete_menu" value="12345">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Akhir Delete -->

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
                                <th scope="col">Foto Menu</th>
                                <th scope="col">Nama Menu</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Jenis Menu</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Stok</th>
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
                                    <td>
                                        <div style="width: 70px">
                                            <img src="assets/img/<?php echo $row['foto'] ?>" class="img-thumbnail" alt="...">
                                        </div>

                                    </td>
                                    <td><?php echo $row['nama_menu'] ?></td>
                                    <td><?php echo $row['keterangan'] ?></td>
                                    <td><?php echo ($row['jenis_menu'] == 1) ? "Mentahan" : "Olahan" ?></td>
                                    <td><?php echo $row['kategori_menu'] ?></td>
                                    <td>Rp. <?php echo number_format($row['harga'], 0, ',', '.') ?></td>
                                    <td><?php echo $row['stok'] ?></td>
                                    <td>
                                        <div class="d-flex">
                                            <button class="btn btn-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#Modal_View_Menu<?php echo $row['id'] ?>"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#Modal_Edit_Menu<?php echo $row['id'] ?>"><i class="bi bi-pencil-square"></i></i></button>
                                            <?php if ($level_nanas <= 1): ?>
                                                <button class="btn btn-danger btn-sm me-1" data-bs-toggle="modal" data-bs-target="#Modal_Delete<?php echo $row['id'] ?>"><i class="bi bi-trash3"></i></button>
                                            <?php endif; ?>
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