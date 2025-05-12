<?php
include_once("koneksi.php");

class MenuDeleter
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function deleteMenu($id)
    {
        // Check if the category is being used in tb_daftar_menu
        $stmt = $this->conn->prepare("SELECT kategori FROM tb_daftar_menu WHERE kategori = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<script>
                    alert("Kategori menu Telah Ada Pada Daftar Menu, Kategori Tidak Dapat Dihapus");
                    window.location.href = "../kategori";
                </script>';
        } else {
            // If not used, delete the category
            $stmt_delete = $this->conn->prepare("DELETE FROM tb_kategori WHERE id_kategori = ?");
            $stmt_delete->bind_param("s", $id);
            if ($stmt_delete->execute()) {
                echo '<script>
                        alert("Data Berhasil Dihapus");
                        window.location.href = "../kategori";
                    </script>';
            } else {
                echo '<script>
                        alert("Data Gagal Dihapus");
                        window.history.back(); // Kembali ke halaman sebelumnya
                    </script>';
            }
        }

        // Close statements
        $stmt->close();
        if (isset($stmt_delete)) $stmt_delete->close();
    }
}

// Create an instance of the Database class
$db = new Database();
$conn = $db->getConnection(); // Get the active connection

// Create an instance of MenuDeleter
$menuDeleter = new MenuDeleter($conn);

// Get the ID from POST data
$id = (isset($_POST['id'])) ? htmlentities($_POST['id']) : "";

// Check if the delete request was made
if (!empty($_POST['delete_kategori'])) {
    $menuDeleter->deleteMenu($id);
}

// Close the connection
$conn->close();
