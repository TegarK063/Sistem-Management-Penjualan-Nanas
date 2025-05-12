<?php
include("../../admin/proses/koneksi.php");

class MenuHapusCart
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }
    public function hapusCart($id_cart)
    {
        $db = new Database(); // Create an instance of the Database class
        $conn = $db->getConnection(); // Get the connection
        mysqli_query($conn, "DELETE FROM tb_cart WHERE id_cart = '$id_cart'");
    }
}

$menuHapusCart = new MenuHapusCart($conn);

$id_cart = (isset($_POST['id_cart'])) ? htmlentities($_POST['id_cart']) : "";
$menuHapusCart->hapusCart($id_cart);

header('Location:../cart.php');
exit();
