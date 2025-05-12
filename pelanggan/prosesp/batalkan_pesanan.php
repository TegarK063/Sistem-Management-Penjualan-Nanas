<?php
include("../../admin/proses/koneksi.php");

$db = new Database(); // Create an instance of the Database class
$conn = $db->getConnection(); // Get the connection

class MenuHapusPesanan
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function updatestok($id_pesanan)
    {
        $db = new Database(); // Create an instance of the Database class
        $conn = $db->getConnection(); // Get the connection

        $query_detail_pesanan = mysqli_query($conn, "SELECT * FROM tb_detail_pesanan WHERE id_pesanan = '$id_pesanan'");
        $data_detail_pesanan = mysqli_fetch_all($query_detail_pesanan, MYSQLI_ASSOC);
        foreach ($data_detail_pesanan as $row) {
            $id = $row['id'];
            $jumlah = $row['jumlah'];

            $query_check_stok = mysqli_query($conn, "SELECT stok FROM tb_daftar_menu WHERE id = '$id'");
            $stok_data = mysqli_fetch_assoc($query_check_stok);
            $stok_sekarang = $stok_data['stok'];
            $stok_baru = $stok_sekarang + $jumlah;
            mysqli_query($conn, "UPDATE tb_daftar_menu SET stok = '$stok_baru' WHERE id = '$id'");
        }
    }


    public function hapusPesanan($id_pesanan)
    {
        $db = new Database(); // Create an instance of the Database class
        $conn = $db->getConnection(); // Get the connection
        mysqli_query($conn, "DELETE FROM tb_pesanan WHERE id_pesanan = '$id_pesanan'");
    }
}

$menuHapusPesanan = new MenuHapusPesanan($conn);

$id_pesanan = (isset($_POST['id_pesanan'])) ? htmlentities($_POST['id_pesanan']) : "";

$menuHapusPesanan->updatestok($id_pesanan);
$menuHapusPesanan->hapusPesanan($id_pesanan);

header('Location:../pesanan.php');
exit();
