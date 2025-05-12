<?php
include("koneksi.php");

$db = new Database(); // Create an instance of the Database class
$conn = $db->getConnection(); // Get the connection

$id = (isset($_POST['id'])) ? htmlentities($_POST['id']) : "";
$nama_menu = (isset($_POST['nama_menu'])) ? htmlentities($_POST['nama_menu']) : "";
$keterangan = (isset($_POST['keterangan'])) ? htmlentities($_POST['keterangan']) : "";
$kategori_menu = (isset($_POST['kategori_menu'])) ? htmlentities($_POST['kategori_menu']) : "";
$harga = (isset($_POST['harga'])) ? htmlentities($_POST['harga']) : "";
$stok = (isset($_POST['stok'])) ? htmlentities($_POST['stok']) : "";

$foto = (isset($_POST['foto'])) ? htmlentities($_POST['foto']) : "";

$code_ack = rand(10000, 99999) . "-";
$folder = "../assets/img/" . $code_ack;
$files = $folder . basename($_FILES['foto']['name']);
$imgtype = strtolower(pathinfo($files, PATHINFO_EXTENSION));

if(empty($foto)){
    $query = mysqli_query($conn, "UPDATE tb_daftar_menu SET nama_menu='$nama_menu', keterangan='$keterangan', kategori='$kategori_menu', harga='$harga', stok='$stok' WHERE id= '$id'");
    echo '<script>
    alert("Data Berhasil Diperbarui");
    window.location.href = "../produk";
    </script>';
}else{

    
    if (!empty($_POST['submit_menu'])) {
        // Check if the uploaded file is an image
        $cek = getimagesize($_FILES['foto']['tmp_name']);
        if ($cek === false) {
            $pesan = "Ini bukan file gambar";
            $statusupload = 0;
        } else {
            $statusupload = 1;
            if (file_exists($files)) {
                $pesan = "Maaf file yang dimasukkan tidak ada";
                $statusupload = 0;
            } else {
                if ($_FILES['foto']['size'] > 5000000) { // 5 MB limit
                    $pesan = "Ukuran foto terlalu besar";
                    $statusupload = 0;
                } else {
                    if (!in_array($imgtype, ['jpg', 'png', 'jpeg', 'gif'])) {
                        $pesan = "Hanya diperbolehkan gambar berformat: jpeg, png, jpg, dan gif";
                        $statusupload = 0;
                    }
                }
            }
        }

        if ($statusupload == 0) {
            echo '<script>
                    alert("' . $pesan . ', Gambar tidak dapat diupload");
                    window.location.href = "../produk";
                    </script>';
        } else {
            // Check if the menu name already exists
            $select = mysqli_query($conn, "SELECT * FROM tb_daftar_menu WHERE nama_menu = '$nama_menu' AND id != '$id'");
            if (mysqli_num_rows($select) > 0) {
                echo '<script>
                        alert("Nama Menu Telah Ada");
                        window.location.href = "../produk";
                        </script>';
            } else {
                // Move the uploaded file
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $files)) {
                    // Update the menu in the database
                    $query = mysqli_query($conn, "UPDATE tb_daftar_menu SET foto='" . $code_ack . $_FILES['foto']['name'] . "', nama_menu='$nama_menu', keterangan='$keterangan', kategori='$kategori_menu', harga='$harga', stok='$stok' WHERE id= '$id'");
                    if ($query) {
                        echo '<script>
                        alert("Data Berhasil Diperbarui");
                        window.location.href = "../produk";
                        </script>';
                    } else {
                        echo '<script>
                        alert("Data Gagal Diperbarui");
                        window.location.href = "../produk";
                        </script>';
                    }
                } else {
                    echo '<script>
                    alert("Maaf, Terjadi kesalahan Upload File");
                    window.location.href = "../produk";
                        </script>';
                }
            }
        }
    }
}
echo $pesan;
