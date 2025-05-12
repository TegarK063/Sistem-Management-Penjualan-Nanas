<?php
include_once "proses/koneksi.php";

// Membuat koneksi ke database melalui kelas Database
$db = new Database();
$conn = $db->getConnection(); // Ambil koneksi aktif

$query = mysqli_query($conn, "SELECT * FROM tb_admin WHERE username='$_SESSION[username_nanas]'");
$records = mysqli_fetch_array($query);
?>

<style>
    .gold-active:active {
        background-color: gold;
        color: black;
    }
</style>

<nav class="navbar navbar-expand sticky-top" style="background-color:gold">
    <div class="container-lg">
        <img src="assets/img/nnas.png" alt="Bootstrap" width="40" height="40">
        <a class="navbar-brand" href=".">Nanas Subang</a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $hasil['username']; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-2">
                        <li><a class="dropdown-item gold-active" href="#" data-bs-toggle="modal" data-bs-target="#Modal_Profile"><i class="bi bi-person"></i> Profil</a></li>
                        <li><a class="dropdown-item gold-active" href="#" data-bs-toggle="modal" data-bs-target="#Modal_Riset_Pass"><i class="bi bi-sliders"></i> Riset Password</a></li>
                        <li><a class="dropdown-item gold-active" href="logout"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Modal Riset Pass -->
<div class="modal fade" id="Modal_Riset_Pass" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Riset Password</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate action="proses/riset_pass.php" method="post">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input disabled type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="username" value="<?php echo $_SESSION['username_nanas'] ?>">
                                <label for="floatingInput">Username</label>
                                <div class="invalid-feedback">
                                    Silahkan Masukkan Username Anda.
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input required type="password" class="form-control" id="floatingPassword" name="passwordlama">
                                <label for=" floatingInput">Password Lama</label>
                                <div class="invalid-feedback">
                                    Silahkan Masukkan Password Lama.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input required type="password" class="form-control" id="floatingPassword" name="passwordbaru">
                                <label for=" floatingInput">Password Baru</label>
                                <div class="invalid-feedback">
                                    Silahkan Masukkan Password Baru.
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input required type="password" class="form-control" id="floatingInput" name="ulangipasswordbaru">
                                <label for="floatingPassword">Ulangi Password Baru</label>
                                <div class="invalid-feedback">
                                    Silahkan Masukkan Ulang Password Baru Anda.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="ubah_pass" value="12345">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Akhir Riset Pass -->

<!-- Modal Riset profil -->
<div class="modal fade" id="Modal_Profile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Profil</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate action="proses/ubah_profile.php" method="post">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input disabled type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="username" value="<?php echo $_SESSION['username_nanas'] ?>">
                                <label for="floatingInput">Username</label>
                                <div class="invalid-feedback">
                                    Silahkan Masukkan Username Anda.
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input required type="text" class="form-control" id="floatingNama" name="nama" value="<?php echo $records['nama'] ?>">
                                <label for=" floatingInput">Nama</label>
                                <div class="invalid-feedback">
                                    Silahkan Masukkan Nama Anda.
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input required type="number" class="form-control" id="floatingInput" name="nohp" value="<?php echo $records['nohp'] ?>">
                                <label for=" floatingInput">No Hp</label>
                                <div class="invalid-feedback">
                                    Silahkan Masukkan No Hp Baru Anda.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="" style="height: 100px" name="alamat" required><?php echo $records['alamat'] ?></textarea>
                                <label for="floatingPassword">Alamat Anda</label>
                                <div class="invalid-feedback">
                                    Silahkan Masukkan Alamat Baru Anda.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="ubah_profil" value="12345">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Akhir Riset profil -->