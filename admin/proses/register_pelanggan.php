<?php
include("Koneksi.php");

class Register
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function checkUsernameExists($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM tb_admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function addUser($nama, $username, $nohp, $password, $level)
    {
        $stmt = $this->conn->prepare("INSERT INTO tb_admin (nama, username, nohp, password, level) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $nama, $username, $nohp, $password, $level);
        return $stmt->execute();
    }
}

$db = new Database();
$register = new Register($db->getConnection());

$nama = isset($_POST['nama']) ? htmlentities($_POST['nama']) : "";
$username = isset($_POST['username']) ? htmlentities($_POST['username']) : "";
$nohp = isset($_POST['nohp']) ? htmlentities($_POST['nohp']) : "";
$password = isset($_POST['password']) ? htmlentities($_POST['password']) : "";
$level = 3; // Level default pelanggan

if (!empty($_POST['submit_validate'])) {
    if ($register->checkUsernameExists($username)) {
        echo '<script>
            alert("Username Telah Ada");
            window.location.href = "../register";
        </script>';
    } else {
        if ($register->addUser($nama, $username, $nohp, $password, $level)) {
            echo '<script>
                alert("Data Berhasil Dimasukkan");
                window.location.href = "../login";
            </script>';
        } else {
            echo '<script>
                alert("Data Gagal Dimasukkan");
            </script>';
        }
    }
}
