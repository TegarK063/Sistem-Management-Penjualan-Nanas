<?php
include("koneksi.php");

class User
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function addUser($data)
    {
        $nama = htmlentities($data['nama']);
        $username = htmlentities($data['username']);
        $nohp = htmlentities($data['nohp']);
        $password = htmlentities($data['password']);
        $level = htmlentities($data['level']);
        $alamat = htmlentities($data['alamat']);

        // Check if the username already exists
        $stmt = $this->conn->prepare("SELECT * FROM tb_admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<script>
                    alert("Username Telah Ada");
                    window.location.href = "../user";
                    </script>';
        } else {
            // Insert the new user
            $stmt = $this->conn->prepare("INSERT INTO tb_admin (nama, username, nohp, password, level, alamat) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $nama, $username, $nohp, $password, $level, $alamat);
            if ($stmt->execute()) {
                echo '<script>
                        alert("Data Berhasil Dimasukkan");
                        window.location.href = "../user";
                        </script>';
            } else {
                echo '<script>
                        alert("Data Gagal Dimasukkan");
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
    $user->addUser($_POST);
}

// Close the connection
$conn->close();
