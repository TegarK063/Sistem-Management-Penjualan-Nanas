<?php
include_once("koneksi.php");

class Kategori
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function cekKategori($kategori_menu)
    {
        $query = $this->conn->prepare("SELECT kategori_menu FROM tb_kategori WHERE kategori_menu = ?");
        $query->bind_param("s", $kategori_menu);
        $query->execute();
        $result = $query->get_result();
        return $result->num_rows > 0;
    }

    public function tambahKategori($jenis_menu, $kategori_menu)
    {
        $query = $this->conn->prepare("INSERT INTO tb_kategori (jenis_menu, kategori_menu) VALUES (?, ?)");
        $query->bind_param("is", $jenis_menu, $kategori_menu);
        return $query->execute();
    }
}

// Inisialisasi koneksi database
$db = new Database();
$conn = $db->getConnection();

// Ambil data dari form
$jenis_menu = (isset($_POST['jenis_menu'])) ? htmlentities($_POST['jenis_menu']) : "";
$kategori_menu = (isset($_POST['kategori_menu'])) ? htmlentities($_POST['kategori_menu']) : "";

if (!empty($_POST['submit_kategori'])) {
    $kategori = new Kategori($conn);

    // Periksa apakah kategori sudah ada
    if ($kategori->cekKategori($kategori_menu)) {
        echo '<script>
                alert("Kategori Telah Ada");
                window.location.href = "../kategori";
            </script>';
    } else {
        // Tambah kategori baru
        if ($kategori->tambahKategori($jenis_menu, $kategori_menu)) {
            echo '<script>
                    alert("Data Berhasil Dimasukkan");
                    window.location.href = "../kategori";
                </script>';
        } else {
            echo '<script>
                    alert("Data Gagal Dimasukkan");
                </script>';
        }
    }
}
