<?php
include_once "proses/koneksi.php";
// session_start(); // Pastikan session dimulai jika belum

// Membuat koneksi ke database melalui kelas Database
$db = new Database();
$conn = $db->getConnection(); // Ambil koneksi aktif

$hasil = [];
$query = $conn->query("SELECT * FROM tb_kategori");
while ($record = $query->fetch_assoc()) {
    $hasil[] = $record;
}

$level_nanas = isset($_SESSION['level_nanas']) ? $_SESSION['level_nanas'] : 0; // Level user, default 0 jika tidak ada
?>


<div class="col-lg-9 mt-2">
    <div class="card">
        <div class="card-header">
            Halaman Kategori
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col d-flex justify-content-end">
                    <?php if ($level_nanas <= 1): ?>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal_Tambah_User"> Tambah Kategori</button>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Modal Tambah Kategori-->
            <div class="modal fade" id="Modal_Tambah_User" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Kategori</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="proses/input_kategori.php" method="post">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" name="jenis_menu" id="">
                                                <option value="1">Mentahan</option>
                                                <option value="2">Olahan</option>
                                            </select>
                                            <label for="floatingInput">Jenis Menu</label>
                                            <div class="invalid-feedback">
                                                Silahkan Masukkan Jenis Menu.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="kategori menu" name="kategori_menu" required>
                                            <label for="floatingInput">Kategori Menu</label>
                                            <div class="invalid-feedback">
                                                Silahkan Masukkan Ketegori Menu Anda.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="submit_kategori" value="12345">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir Tambah Kategori -->

            <?php
            foreach ($hasil as $row) {
            ?>

                <!-- Modal Edit-->
                <div class="modal fade" id="Modal_Edit<?php echo $row['id_kategori'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data Kategori</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="needs-validation" novalidate action="proses/edit_kategori.php" method="post">
                                    <input type="hidden" value="<?php echo $row["id_kategori"] ?>" name="id">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" aria-label="Default select example" name="jenis_menu" id="">
                                                    <?php
                                                    $data = array(1 => "Mentahan", 2 => "Olahan");
                                                    foreach ($data as $key => $value) {
                                                        if ($row['jenis_menu'] == $key) {
                                                            echo "<option selected value='$key'>$value</option>";
                                                        } else {
                                                            echo "<option value='$key'>$value</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <label for="floatingInput">Jenis Menu</label>
                                                <div class="invalid-feedback">
                                                    Silahkan Masukkan Jenis Menu.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="floatingInput" placeholder="kategori menu" name="kategori_menu" required value="<?php echo $row["kategori_menu"] ?>">
                                                <label for="floatingInput">Kategori Menu</label>
                                                <div class="invalid-feedback">
                                                    Silahkan Masukkan Ketegori Menu Anda.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="submit_kategori" value="12345">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Akhir Edit -->

                <!-- Modal Delete-->
                <div class="modal fade" id="Modal_Delete<?php echo $row['id_kategori'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md modal-fullscreen-md-down">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Kategori</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="needs-validation" novalidate action="proses/delete_kategori.php" method="post">
                                    <input type="hidden" value="<?php echo $row['id_kategori'] ?>" name="id">
                                    Apakah Anda Ingin Menghapus Kategori <b><?php echo $row['kategori_menu'] ?></b>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger" name="delete_kategori" value="12345">Delete</button>
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
            if (empty($hasil)) {
                echo "Data User Yang Dicari Tidak Ada";
            } else {

            ?>
                <!-- Table Daftar Kategori -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Jenis Menu</th>
                                <th scope="col">Kategori Menu</th>
                                <?php if ($level_nanas <= 1): ?>
                                    <th scope="col">Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($hasil as $row) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $no++; ?></th>
                                    <td><?php echo ($row['jenis_menu'] == 1) ? "Mentahan" : "Olahan" ?></td>
                                    <td><?php echo $row['kategori_menu'] ?></td>
                                    <td class="d-flex">
                                        <?php if ($level_nanas <= 1): ?>
                                            <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#Modal_Edit<?php echo $row['id_kategori'] ?>"><i class="bi bi-pencil-square"></i></i></button>
                                            <button class="btn btn-danger btn-sm me-1" data-bs-toggle="modal" data-bs-target="#Modal_Delete<?php echo $row['id_kategori'] ?>"><i class="bi bi-trash3"></i></button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- Akhir Table Kategori -->
            <?php
            }
            ?>
        </div>
    </div>
</div>