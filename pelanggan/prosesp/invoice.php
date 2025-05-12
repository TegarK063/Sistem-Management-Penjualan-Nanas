<?php
session_start();
include_once "../../admin/proses/koneksi.php"; // Pastikan file ini terhubung dengan database

// Buat koneksi database
$db = new Database();
$conn = $db->getConnection();

$query = mysqli_query($conn, "SELECT * FROM tb_admin WHERE username = '$_SESSION[username_nanas]'");
$hasil = mysqli_fetch_array($query);
$id_user = $hasil['id_user'];
$email = $hasil['username'];
$nama = $hasil['nama'];
$nohp = $hasil['nohp'];
$alamat = $hasil['alamat'];

// mengambil data pesanan selesai
$pesanan_selesai = [];
$id_pesanan = isset($_POST['id_pes']) ? $_POST['id_pes'] : '';
$query_pesanan_selesai = mysqli_query($conn, "SELECT * FROM tb_transaksi where id_pesanan = '$id_pesanan'");
while ($record_selesai = mysqli_fetch_array($query_pesanan_selesai)) {
    $pesanan_selesai[] = $record_selesai;
}
?>

<form method="POST" action="prosesp/invoice.php">
    <?php foreach ($pesanan_selesai as $row_pesanan_selesai) { ?>
        <!-- Modal View Selesai-->
        <div class="modal fade" id="Modal_Detail_Pesanan_Selesai<?php echo $row_pesanan_selesai['id_pesanan'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <?php
            $id_pesanan_selesai = $row_pesanan_selesai['id_pesanan'];
            $detail_pesanan = [];
            $query_detail_pesanan = mysqli_query($conn, "SELECT * FROM tb_detail_pesanan where id_pesanan = '$id_pesanan_selesai'");
            while ($record = mysqli_fetch_array($query_detail_pesanan)) {
                $detail_pesanan[] = $record;
            }
            $ambiltotal = mysqli_query($conn, "SELECT SUM(total_harga) AS total from tb_detail_pesanan where id_pesanan = '$id_pesanan_selesai'");
            $alltotal = mysqli_fetch_assoc($ambiltotal); // Ambil hasil query
            ?>

            <style>
                .invoice-container {
                    font-family: Arial, sans-serif;
                    border: 1px solid #ddd;
                    padding: 20px;
                    width: 700px;
                    margin: auto;
                }

                .invoice-header {
                    margin-bottom: 20px;
                }

                .invoice-header h1 {
                    font-size: 24px;
                    margin: 0;
                    text-align: center;
                }

                .details-section {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }

                .details-section p {
                    margin: 5px 0;
                }

                .table-container {
                    margin-top: 20px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }

                table th,
                table td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }

                table th {
                    background-color: #f5f5f5;
                }

                .total-row {
                    font-weight: bold;
                }

                .footer-section {
                    margin-top: 20px;
                }
            </style>

            <div class="invoice-container">
                <div class="invoice-header">
                    <h1>Invoice Pesanan</h1>
                </div>

                <div class="details-section">
                    <div>
                        <p>Nama: <strong><?php echo $nama; ?></strong></p>
                        <p>No HP: <strong><?php echo $nohp; ?></strong></p>
                        <p>Email: <strong><?php echo $email; ?></strong></p>
                    </div>
                    <div>
                        <p>Nama Penerima: <strong><?php echo $row_pesanan_selesai['nama_penerima']; ?></strong></p>
                        <p>Alamat Tujuan: <strong><?php echo $row_pesanan_selesai['alamat_tujuan']; ?></strong></p>
                    </div>
                </div>

                <div class="table-container">
                    <p><strong>Produk yang Dipesan:</strong></p>
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detail_pesanan as $row) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['nama_menu']); ?></td>
                                    <td>Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                    <td><?php echo htmlspecialchars($row['jumlah']); ?></td>
                                    <td>Rp. <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php } ?>
                            <tr class="total-row">
                                <td colspan="3" class="text-end">Total</td>
                                <td>Rp. <?php echo number_format($alltotal['total'], 0, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="footer-section">
                    <p>Metode Pembayaran: <strong><?php echo $row_pesanan_selesai['metode_bayar']; ?></strong></p>
                    <p>Status Pesanan: <strong><?php echo $row_pesanan_selesai['status_pembayaran']; ?></strong></p>
                </div>
            </div>

            <script>
                window.print();
            </script>

            <div class="footer" style="text-align: center">
                <p>Terima kasih telah berbelanja dengan kami!</p>
            </div>
        </div>
    <?php } ?>
</form>