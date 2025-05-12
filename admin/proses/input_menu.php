<?php
include("koneksi.php");

class MenuHandler
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function addMenu($data, $file)
    {
        $nama_menu = htmlentities($data['nama_menu']);
        $keterangan = htmlentities($data['keterangan']);
        $kategori_menu = htmlentities($data['kategori_menu']);
        $harga = htmlentities($data['harga']);
        $stok = htmlentities($data['stok']);

        $code_ack = rand(10000, 99999) . "-";
        $folder = "../assets/img/" . $code_ack;
        $files = $folder . basename($file['foto']['name']);
        $imgtype = strtolower(pathinfo($files, PATHINFO_EXTENSION));

        // Validate image
        if (!$this->validateImage($file['foto'], $files, $imgtype)) {
            return;
        }

        // Check if the menu already exists
        if ($this->menuExists($nama_menu)) {
            echo '<script>
                    alert("Nama Menu Telah Ada");
                    window.location.href = "../produk";
                    </script>';
            return;
        }

        // Upload the file
        if (move_uploaded_file($file['foto']['tmp_name'], $files)) {
            // Insert the new menu into the database
            if ($this->insertMenu($code_ack . $file['foto']['name'], $nama_menu, $keterangan, $kategori_menu, $harga, $stok)) {
                echo '<script>
                alert("Data Berhasil Dimasukkan");
                window.location.href = "../produk";
                </script>';
            } else {
                echo '<script>
                alert("Data Gagal Dimasukkan");
                window.location.href = "../produk";
                </script>';
            }
        } else {
            echo '<script>
                alert("Maaf, Terjadi kesalahan Upload File");
                window.location.href = "../produk";
                </script>';
        }
    }

    private function validateImage($image, $filePath, $imgtype)
    {
        $cek = getimagesize($image['tmp_name']);
        if ($cek === false) {
            echo '<script>alert("Ini bukan file gambar");</script>';
            return false;
        }

        if (file_exists($filePath)) {
            echo '<script>alert("Maaf file yang dimasukkan sudah ada");</script>';
            return false;
        }

        if ($image['size'] > 5000000) { // 5 MB limit
            echo '<script>alert("Ukuran foto terlalu besar");</script>';
            return false;
        }

        if (!in_array($imgtype, ['jpg', 'png', 'jpeg', 'gif'])) {
            echo '<script>alert("Hanya diperbolehkan gambar berformat: jpeg, png, jpg, dan gif");</script>';
            return false;
        }

        return true;
    }

    private function menuExists($nama_menu)
    {
        $select = mysqli_query($this->conn, "SELECT * FROM tb_daftar_menu WHERE nama_menu = '$nama_menu'");
        return mysqli_num_rows($select) > 0;
    }

    private function insertMenu($foto, $nama_menu, $keterangan, $kategori_menu, $harga, $stok)
    {
        return mysqli_query($this->conn, "INSERT INTO tb_daftar_menu (foto, nama_menu, keterangan, kategori, harga, stok) VALUES ('$foto', '$nama_menu', '$keterangan', '$kategori_menu', '$harga', '$stok')");
    }
}

// Create an instance of the Database class
$db = new Database();
$conn = $db->getConnection();

// Create an instance of MenuHandler
$menuHandler = new MenuHandler($conn);

// Check if the form is submitted
if (!empty($_POST['submit_menu'])) {
    $menuHandler->addMenu($_POST, $_FILES);
}
