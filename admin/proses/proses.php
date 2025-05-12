<?php
session_start();
include("Koneksi.php");

class Login
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function validateLogin($username, $password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM tb_admin WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

$db = new Database();
$login = new Login($db->getConnection());

$username = isset($_POST['username']) ? htmlentities($_POST['username']) : "";
$password = isset($_POST['password']) ? htmlentities($_POST['password']) : "";

if (!empty($_POST["submit_validate"])) {
    $hasil = $login->validateLogin($username, $password);
    if ($hasil) {
        $_SESSION['username_nanas'] = $username;
        $_SESSION['level_nanas'] = $hasil['level'];
        if ($_SESSION['level_nanas'] == 3) {
            header("Location:../../pelanggan/homep.php");
        } else {
            header('Location:../home');
        }
    } else {
        echo '<script>
            alert("Username atau Password Anda Salah!");
            window.location = "../login";
        </script>';
    }
}
exit();
