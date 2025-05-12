<?php
include("koneksi.php");

class User
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function resetPassword($id_user)
    {
        $id_user = htmlentities($id_user);
        $newPassword = '12345'; // Default password

        // Prepare the UPDATE statement
        $stmt = $this->conn->prepare("UPDATE tb_admin SET password = ? WHERE id_user = ?");
        $stmt->bind_param("si", $newPassword, $id_user);

        if ($stmt->execute()) {
            echo '<script>
                    alert("Data Berhasil Direset");
                    window.location.href = "../user";
                    </script>';
        } else {
            echo '<script>
                    alert("Data Gagal Direset");
                    window.history.back(); // Kembali ke halaman sebelumnya
                    </script>';
        }

        // Close the statement
        $stmt->close();
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
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['submit_user'])) {
    $user->resetPassword($_POST['id_user']);
}

// Close the connection
$conn->close();
