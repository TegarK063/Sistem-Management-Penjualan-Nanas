<?php
include("koneksi.php");

class MenuDeleter
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function deleteMenu($id, $foto)
    {
        // Prepare the delete statement
        $stmt = $this->conn->prepare("DELETE FROM tb_daftar_menu WHERE id = ?");
        $stmt->bind_param("s", $id); // Assuming id is a string, change to "i" for integers if needed

        // Execute the statement
        if ($stmt->execute()) {
            // Delete the associated image file
            if (file_exists("../assets/img/$foto")) {
                unlink("../assets/img/$foto");
            }
            echo '<script>
                alert("Data Berhasil Dihapus");
                window.location.href = "../produk";
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

// Create an instance of MenuDeleter
$menuDeleter = new MenuDeleter($conn);

// Get the ID and photo from POST data
$id = (isset($_POST['id'])) ? htmlentities($_POST['id']) : "";
$foto = (isset($_POST['foto'])) ? htmlentities($_POST['foto']) : "";

// Check if the delete request was made
if (!empty($_POST['delete_menu'])) {
    $menuDeleter->deleteMenu($id, $foto);
}

// Close the connection
$conn->close();
