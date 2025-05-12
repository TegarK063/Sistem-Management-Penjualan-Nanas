<?php
session_start();
include("koneksi.php");

class User
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function changePassword($username, $oldPassword, $newPassword, $confirmNewPassword)
    {
        // Check if new passwords match
        if ($newPassword !== $confirmNewPassword) {
            return "Password baru dan konfirmasi password tidak sama!";
        }

        // Prepare the SELECT statement to check the old password
        $stmt = $this->conn->prepare("SELECT * FROM tb_admin WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $oldPassword);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Prepare the UPDATE statement to change the password
            $stmt = $this->conn->prepare("UPDATE tb_admin SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $newPassword, $username);
            if ($stmt->execute()) {
                return "Password berhasil diperbarui!";
            } else {
                return "Gagal memperbarui password. Coba lagi nanti.";
            }
        } else {
            return "Password lama salah!";
        }
    }
}

// Create an instance of the Database class
$db = new Database();
$conn = $db->getConnection(); // Get the active connection

// Check if the connection was successful
if ($conn === null) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create an instance of the User class
$user = new User($conn);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['ubah_pass'])) {
    $id = isset($_POST['id']) ? htmlentities($_POST['id']) : "";
    $oldPassword = isset($_POST['passwordlama']) ? htmlentities($_POST['passwordlama']) : "";
    $newPassword = isset($_POST['passwordbaru']) ? htmlentities($_POST['passwordbaru']) : "";
    $confirmNewPassword = isset($_POST['ulangipasswordbaru']) ? htmlentities($_POST['ulangipasswordbaru']) : "";

    // Call the changePassword method
    $message = $user->changePassword($_SESSION['username_nanas'], $oldPassword, $newPassword, $confirmNewPassword);

    // Show the message to the user
    echo '<script>
            alert("' . $message . '");
            window.history.back();
        </script>';
} else {
    header('location:../home');
}

// Close the connection
$conn->close();
