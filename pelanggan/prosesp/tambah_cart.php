<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



<!-- Toast Notifikasi -->
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3">
    <div id="productExistsToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Informasi</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Produk sudah ada di keranjang.
        </div>
    </div>
</div>

<?php
include("../../admin/proses/koneksi.php");

$db = new Database(); // Create an instance of the Database class
$conn = $db->getConnection(); // Get the connection


$id_user = (isset($_POST['id_user'])) ? htmlentities($_POST['id_user']) : "";
$id = (isset($_POST['id'])) ? htmlentities($_POST['id']) : "";
$nama_menu = (isset($_POST['nama_menu'])) ? htmlentities($_POST['nama_menu']) : "";
$jumlah = 1;
$harga = (isset($_POST['harga'])) ? htmlentities($_POST['harga']) : "";
$total_harga = (isset($_POST['harga'])) ? htmlentities($_POST['harga']) : "";

$data = mysqli_query($conn, "select * from tb_cart where id_user = '$id_user' and id = '$id'");
$cekdata = mysqli_num_rows($data);

// cek apakah produk sudah ada di cart
if ($cekdata > 0) {
    echo '<script>
            var toastEl = document.getElementById("productExistsToast");
            var toast = new bootstrap.Toast(toastEl);
            toast.show();
            setTimeout(function() {
                window.location.href = "../homep.php";
            }, 3000);
          </script>';
} else {
    $query = mysqli_query($conn, "INSERT INTO tb_cart (id_user, id, nama_menu, jumlah, harga, total_harga) VALUES ('$id_user', '$id', '$nama_menu', '$jumlah', '$harga', '$total_harga')");
    header('Location:../homep.php');
}
exit();
?>