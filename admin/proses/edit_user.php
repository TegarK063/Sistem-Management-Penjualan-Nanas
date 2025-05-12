<?php
include("koneksi.php");

class User
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function updateUser($data)
    {
        $id_user = htmlentities($data['id_user']);
        $nama = htmlentities($data['nama']);
        $username = htmlentities($data['username']);
        $nohp = htmlentities($data['nohp']);
        $level = htmlentities($data['level']);
        $alamat = htmlentities($data['alamat']);

        // Check if the username already exists
        $stmt = $this->conn->prepare("SELECT * FROM tb_admin WHERE username = ? AND id_user != ?");
        $stmt->bind_param("si", $username, $id_user); // Check for existing username excluding the current user
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<script>
                    alert("Username Telah Ada");
                    window.location.href = "../user";
                    </script>';
        } else {
            // Update the user information
            $stmt = $this->conn->prepare("UPDATE tb_admin SET nama = ?, username = ?, nohp = ?, level = ?, alamat = ? WHERE id_user = ?");
            $stmt->bind_param("sssssi", $nama, $username, $nohp, $level, $alamat, $id_user);
            if ($stmt->execute()) {
                echo '<script>
                        alert("Data Berhasil Update");
                        window.location.href = "../user";
                        </script>';
            } else {
                echo '<script>
                        alert("Data Gagal Update");
                        window.history.back(); // Kembali ke halaman sebelumnya
                        </script>';
            }
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
    $user->updateUser($_POST);
}

// Close the connection
$conn->close();
