<?php
include("koneksi.php");

class User
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function deleteUser($id_user)
    {
        $id_user = htmlentities($id_user);

        // Prepare the DELETE statement
        $stmt = $this->conn->prepare("DELETE FROM tb_admin WHERE id_user = ?");
        $stmt->bind_param("i", $id_user);

        if ($stmt->execute()) {
            echo '<script>
                    alert("Data Berhasil Dihapus");
                    window.location.href = "../user";
                    </script>';
        } else {
            echo '<script>
                    alert("Data Gagal Dihapus");
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
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['delete_user'])) {
    $user->deleteUser($_POST['id_user']);
}

// Close the connection
$conn->close();
