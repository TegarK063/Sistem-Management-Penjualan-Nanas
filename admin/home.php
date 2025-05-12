<?php
include_once "proses/koneksi.php";

// Membuat koneksi ke database melalui kelas Database
$db = new Database();
$conn = $db->getConnection(); // Ambil koneksi aktif

// Hitung jumlah user
$query = mysqli_query($conn, "SELECT COUNT(id_user) AS total_user FROM tb_admin WHERE level= '3'");
$row = mysqli_fetch_assoc($query);
$total_user = $row['total_user'];

$query1 = mysqli_query($conn, "SELECT COUNT(id) AS total_menu FROM tb_daftar_menu");
$row1 = mysqli_fetch_assoc($query1);
$total_menu = $row1['total_menu'];

$query2 = mysqli_query($conn, "SELECT SUM(total) AS total_uang FROM tb_transaksi");
$row2 = mysqli_fetch_assoc($query2);
$total_uang = $row2['total_uang'];

$query3 = mysqli_query($conn, "SELECT count(id_pesanan) AS pesanan FROM tb_pesanan");
$row3 = mysqli_fetch_assoc($query3);
$total_pesanan = $row3['pesanan'];

$query_chart = mysqli_query($conn, "SELECT tb_daftar_menu.nama_menu, tb_daftar_menu.id, SUM(tb_detail_pesanan.jumlah)
AS total_jumlah FROM tb_daftar_menu LEFT JOIN tb_detail_pesanan ON tb_daftar_menu.id = tb_detail_pesanan.id AND tb_detail_pesanan.status_pembayaran = 'Salesai'
GROUP BY tb_daftar_menu.id ORDER BY tb_daftar_menu.id ASC");

// $result_chart = array();
while ($record_chart = mysqli_fetch_assoc($query_chart)) {
    $result_chart[] = $record_chart;
}
$array_menu = array_column($result_chart, 'nama_menu');
$array_menu_quete = array_map(function ($menu) {
    return "'" . $menu . "'";
}, $array_menu);
$string_menu = implode(',', $array_menu_quete);
// echo $string_menu;

$array_jumlah_pesanan = array_column($result_chart, 'total_jumlah');
$string_jumlah_pesanan = implode(',', $array_jumlah_pesanan);
// echo $string_jumlah_pesanan;

?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .display-4 {
        font-weight: bold;
    }

    .card-body-icon {
        position: absolute;
        z-index: 0;
        top: 25px;
        right: 4px;
        opacity: 0.3;
        font-size: 80px;
    }

    .icon-bg {
        font-size: 6rem;
        color: rgba(255, 255, 255, 0.2);
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
    }

    .card-title {
        position: relative;
        z-index: 2;
    }

    /* Tambahan styling untuk memusatkan chart */
    .chart-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        padding: 20px;
    }

    /* Ukuran canvas */
    #myChart {
        max-width: 100%;
        width: 800px;
        height: 500px;
    }
</style>

<div class="col-lg-9 mt-2">
    <div class="card">
        <div class="card-header">
            <b>DASHBOARD</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="row text-white justify-content-end gap-5 px-5 mb-5" style="padding-left: 3rem;">
                    <div class="card" style="background: green; max-width: 19rem; position: relative; overflow: hidden;">
                        <div class="card-body text-center">
                            <div class="body-icon">
                                <i class="bi bi-arrow-down-left-circle-fill icon-bg"></i>
                            </div>
                            <h5 class="card-title text-white" style="font-size: 1.25rem; position: relative; z-index: 2;">
                                PENDAPATAN : Rp.<?php
                                                $total_uang = $total_uang ?? 0;
                                                echo number_format($total_uang, 0, ',', '.') ?>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card -->
            <div class="row text-white justify-content-center gap-5 px-5" style="padding-left: 3rem;">
                <div class="card bg-info" style="width: 15rem;">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="bi bi-person-rolodex"></i>
                        </div>
                        <h5 class="card-title text-white">PELANGGAN</h5>
                        <div class="display-4 text-white"><?php echo $total_user ?></div>
                    </div>
                </div>
                <div class="card bg-warning" style="width: 15rem;">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h5 class="card-title text-white">PRODUK</h5>
                        <div class="display-4 text-white"><?php echo $total_menu ?></div>
                    </div>
                </div>
                <div class="card bg-danger" style="width: 15rem;">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="bi bi-bag-check-fill"></i>
                        </div>
                        <h5 class="card-title text-white">JUMLAH ORDER</h5>
                        <div class="display-4 text-white"><?php echo $total_pesanan ?></div>
                    </div>
                </div>
            </div>
            <!-- end card -->
        </div>
        <div class="card mt-4 border-0 bg-light">
            <div class="chart-container">
                <!-- Canvas untuk chart -->
                <canvas id="myChart"></canvas>
            </div>
            <script>
                const ctx = document.getElementById('myChart').getContext('2d');

                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: [<?php echo $string_menu ?>],
                        datasets: [{
                            label: 'Jumlah Terjual',
                            data: [<?php echo $string_jumlah_pesanan ?>],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.5)',
                                'rgba(54, 162, 235, 0.5)',
                                'rgba(255, 206, 86, 0.5)',
                                'rgba(75, 192, 192, 0.5)',
                                'rgba(153, 102, 255, 0.5)',
                                'rgba(255, 159, 64, 0.5)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>
</div>