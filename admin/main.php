<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Periksa apakah sesi sudah diset
if (empty($_SESSION['username_nanas'])) {
    header('location:login');
    exit();
}

if ($_SESSION['level_nanas'] > 2) {
    header('location:login');
    session_start();
    session_destroy();
    exit();
}

// Panggil koneksi OOP
include_once "proses/koneksi.php";

class Main
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getUserData($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM tb_admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

// Inisialisasi koneksi
$db = new Database();
$main = new Main($db->getConnection());

// Ambil data pengguna berdasarkan sesi username
$username = $_SESSION['username_nanas'];
$hasil = $main->getUserData($username);
if (!$hasil) {
    echo "Data pengguna tidak ditemukan!";
    exit();
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Penjualan Nanas Subang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }
        .nav-pills .nav-link {
            color: black;
        }

        .nav-pills .nav-link.active {
            color: black;
            background-color: gold;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <?php include "header.php"; ?>
    <!-- end Header -->
    <!-- home_pelanggan -->
    <div class="container-lg">
        <div class="row mb-5">
            <!-- Side Bar -->
            <?php include "sidebar.php"; ?>
            <!-- end Sidebar -->

            <!-- Content -->
            <?php
            if (isset($page)) {
                include $page;
            } else {
                echo "<div class='alert alert-warning'>Halaman tidak ditemukan!</div>";
            }
            ?>
            <!-- end content -->
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        (() => {
            'use strict'

            const forms = document.querySelectorAll('.needs-validation')

            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>

</html>