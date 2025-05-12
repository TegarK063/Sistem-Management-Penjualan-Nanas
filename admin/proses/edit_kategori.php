<?php
include_once("koneksi.php");

class Kategori
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function cekKategori($kategori_menu, $id)
    {
        $query = $this->conn->prepare("SELECT kategori_menu FROM tb_kategori WHERE kategori_menu = ? AND id_kategori != ?");
        $query->bind_param("si", $kategori_menu, $id);
        $query->execute();
        $result = $query->get_result();
        return $result->num_rows > 0;
    }

    public function updateKategori($id, $jenis_menu, $kategori_menu)
    {
        $query = $this->conn->prepare("UPDATE tb_kategori SET jenis_menu = ?, kategori_menu = ? WHERE id_kategori = ?");
        $query->bind_param("isi", $jenis_menu, $kategori_menu, $id);
        return $query->execute();
    }
}

// Inisialisasi koneksi database
$db = new Database();
$conn = $db->getConnection();

// Ambil data dari form
$id = (isset($_POST['id'])) ? htmlentities($_POST['id']) : "";
$jenis_menu = (isset($_POST['jenis_menu'])) ? htmlentities($_POST['jenis_menu']) : "";
$kategori_menu = (isset($_POST['kategori_menu'])) ? htmlentities($_POST['kategori_menu']) : "";

if (!empty($_POST['submit_kategori'])) {
    $kategori = new Kategori($conn);

    // Periksa apakah kategori menu sudah ada kecuali yang sedang di-edit
    if ($kategori->cekKategori($kategori_menu, $id)) {
        echo '<script>
                alert("Kategori menu Telah Ada");
                window.location.href = "../kategori";
            </script>';
    } else {
        // Update kategori
        if ($kategori->updateKategori($id, $jenis_menu, $kategori_menu)) {
            echo '<script>
                    alert("Data Berhasil Update");
                    window.location.href = "../kategori";
                </script>';
        } else {
            echo '<script>
                    alert("Data Gagal Update");
                    window.history.back();
                </script>';
        }
    }
}
