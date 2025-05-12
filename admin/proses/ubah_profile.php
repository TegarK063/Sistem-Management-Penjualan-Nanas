<?php
session_start();
include_once("koneksi.php");

class Profil
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function changeProfil($nama, $nohp, $alamat, $username)
    {
        // Query dengan placeholder untuk parameter
        $stmt = $this->conn->prepare("UPDATE tb_admin SET nama = ?, nohp = ?, alamat = ? WHERE username = ?");
        if (!$stmt) {
            return "Error preparing statement: " . $this->conn->error;
        }

        // Bind parameter dengan tipe data yang sesuai
        $stmt->bind_param("ssss", $nama, $nohp, $alamat, $username);

        // Eksekusi query
        if ($stmt->execute()) {
            return "Profil berhasil diperbarui!";
        } else {
            return "Gagal memperbarui profil. Error: " . $stmt->error;
        }
    }
}

// Membuat instance dari kelas Database
$db = new Database();
$conn = $db->getConnection();

// Cek koneksi
if ($conn === null) {
    die("Connection failed: " . mysqli_connect_error());
}

// Membuat instance dari kelas Profil
$profil = new Profil($conn);

// Cek jika form di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['ubah_profil'])) {
    $nama = isset($_POST['nama']) ? htmlentities($_POST['nama']) : "";
    $nohp = isset($_POST['nohp']) ? htmlentities($_POST['nohp']) : "";
    $alamat = isset($_POST['alamat']) ? htmlentities($_POST['alamat']) : "";

    // Pastikan user login
    if (!isset($_SESSION['username_nanas'])) {
        die("Anda harus login untuk mengubah profil.");
    }

    // Panggil fungsi untuk mengubah profil
    $message = $profil->changeProfil($nama, $nohp, $alamat, $_SESSION['username_nanas']);

    // Tampilkan pesan ke pengguna
    echo '<script>
            alert("' . $message . '");
            window.history.back();
        </script>';
} else {
    echo '<script>
            alert("Terjadi Kesalahan");
            </script>';
    header('location:../home');
}

// Tutup koneksi
$conn->close();