<?php
include_once "koneksi.php";

$db = new Database();
$conn = $db->getConnection();

$hasil = [];
$query = mysqli_query($conn, "SELECT * FROM tb_transaksi");
while ($record = mysqli_fetch_array($query)) {
    $hasil[] = $record;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h3 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        @media print {
            body {
                margin: 0;
            }

            table {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <h3>Laporan Transaksi</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu Transaksi</th>
                <th>Nama Pemesan</th>
                <th>Nama Penerima</th>
                <th>Alamat Tujuan</th>
                <th>Metode Pembayaran</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($hasil as $row) {
                echo "<tr>
                <td>{$no}</td>
                <td>{$row['time_sampai']}</td>
                <td>{$row['nama']}</td>
                <td>{$row['nama_penerima']}</td>
                <td>{$row['alamat_tujuan']}</td>
                <td>{$row['metode_bayar']}</td>
                <td>Rp. " . number_format($row['total'], 0, ',', '.') . "</td>
            </tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>

    <script>
        window.print();
    </script>

</body>

</html>