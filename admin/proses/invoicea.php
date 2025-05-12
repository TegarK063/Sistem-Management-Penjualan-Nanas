<?php
include_once "koneksi.php";

$db = new Database();
$conn = $db->getConnection();

// Ambil ID transaksi dari URL
$id_transaksi = isset($_GET['id']) ? $_GET['id'] : null;

if ($id_transaksi) {
    // Ambil data transaksi
    $query_transaksi = mysqli_query($conn, "SELECT * FROM tb_transaksi WHERE id_transaksi = '$id_transaksi'");
    $transaksi = mysqli_fetch_assoc($query_transaksi);

    // Ambil detail pesanan
    $id_pesanan = $transaksi['id_pesanan'];
    $query_detail = mysqli_query($conn, "SELECT * FROM tb_detail_pesanan WHERE id_pesanan = '$id_pesanan'");
    $detail_pesanan = [];
    while ($row = mysqli_fetch_assoc($query_detail)) {
        $detail_pesanan[] = $row;
    }

    // Hitung total harga
    $query_total = mysqli_query($conn, "SELECT SUM(total_harga) AS total FROM tb_detail_pesanan WHERE id_pesanan = '$id_pesanan'");
    $total = mysqli_fetch_assoc($query_total)['total'];
} else {
    die("ID Transaksi tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</head>

<body>
    <div class="header">
        <h1>Invoice</h1>
        <p>Tanggal: <?php echo date("Y-m-d H:i:s"); ?></p>
    </div>

    <h2>Informasi Transaksi</h2>
    <p>Nama Pemesan: <b><?php echo htmlspecialchars($transaksi['nama']); ?></b></p>
    <p>Nama Penerima: <b><?php echo htmlspecialchars($transaksi['nama_penerima']); ?></b></p>
    <p>Alamat Tujuan: <b><?php echo htmlspecialchars($transaksi['alamat_tujuan']); ?></b></p>
    <p>Metode Pembayaran: <b><?php echo htmlspecialchars($transaksi['metode_bayar']); ?></b></p>

    <h2>Detail Pesanan</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detail_pesanan as $item) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['nama_menu']); ?></td>
                    <td>Rp. <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($item['jumlah']); ?></td>
                    <td>Rp. <?php echo number_format($item['total_harga'], 0, ',', '.'); ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total</strong></td>
                <td><strong>Rp. <?php echo number_format($total, 0, ',', '.'); ?></strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Terima kasih telah berbelanja dengan kami!</p>
    </div>
</body>

</html>