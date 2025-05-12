<?php

$profile = $_SESSION['username_nanas'] ?? 'Guest';
if (!$profile == "Guest") {
    $query = mysqli_query($conn, "SELECT * FROM tb_admin WHERE username='$_SESSION[username_nanas]'");
    $records = mysqli_fetch_array($query);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, Opera */
        }

        .btn1 {
            background-color: whitesmoke;
        }

        .gold-active:active {
            background-color: gold;
            color: black;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.25rem;
        }

        .nav-link {
            font-weight: 500;
        }

        .bi-clipboard-check:hover,
        .bi-cart:hover {
            color: white;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top" style="background-color: gold;">
        <div class="container-lg">
            <!-- Logo dan Brand -->
            <img src="../admin/assets/img/nnas.png" alt="Logo Nanas" width="40" height="40">
            <a class="navbar-brand" href="homep.php">Nanas Subang</a>

            <!-- Tombol toggle untuk layar kecil -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Navbar -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Menu Kiri -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produkp.php">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php"><i class="bi bi-cart" style="font-size: 17px;"></i></a>
                    </li>
                </ul>

                <!-- Form Pencarian -->
                <form action="produkp.php" class="d-flex me-auto" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Nama Barang" aria-label="Nama Barang" name="cari">
                        <button class="btn btn-outline-success btn1" type="submit">Telusuri</button>
                    </div>
                </form>

                <!-- Menu Kanan -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="pesanan.php"><i class="bi bi-clipboard-check" style="font-size: 17px;"></i></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- Menampilkan username atau Guest -->
                            <?php
                            echo $profile; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ($profile !== "Guest"): ?>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#Modal_Profile "><i class="bi bi-person"></i> Profil</a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#Modal_Riset_Pass"><i class="bi bi-sliders"></i> Riset Password</a></li>
                                <li><a class="dropdown-item" href="logoutp.php"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="../admin/login.php"><i class="bi bi-person-down"></i> Login</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Modal Riset Password -->
    <div class="modal fade" id="Modal_Riset_Pass" tabindex="-1" aria-labelledby="Modal_Riset_Pass_Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="Modal_Riset_Pass_Label">Riset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="proses_riset_password.php" method="post">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Lama</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Riset profil -->
    <div class="modal fade" id="Modal_Profile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-fullscreen-md-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Profil</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" novalidate action="prosesp/ubah_profile.php" method="post">
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>