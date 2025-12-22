<?php
// Load config dulu
require_once '../config/database.php';
require_once '../config/functions.php';

$page_title = 'Dashboard';
require_once 'includes/header.php';

// Get Statistics
$total_users = fetch_single("SELECT COUNT(*) as total FROM users WHERE role = 'user'")['total'];
$total_kamar = fetch_single("SELECT COUNT(*) as total FROM kamar")['total'];
$total_reservasi = fetch_single("SELECT COUNT(*) as total FROM reservasi")['total'];
$pending_pembatalan = fetch_single("SELECT COUNT(*) as total FROM pembatalan WHERE status_pengajuan = 'pending'")['total'];

// Revenue calculation
$total_revenue = fetch_single("SELECT IFNULL(SUM(total_harga), 0) as total FROM reservasi WHERE status IN ('lunas', 'selesai')")['total'];

// Reservasi hari ini
$today_reservations = fetch_single("SELECT COUNT(*) as total FROM reservasi WHERE DATE(created_at) = CURDATE()")['total'];

// Get data for charts
// 1. Reservasi per bulan (last 6 months)
$reservasi_per_bulan = fetch_all("
    SELECT 
        DATE_FORMAT(created_at, '%M %Y') as bulan,
        COUNT(*) as total
    FROM reservasi
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY YEAR(created_at), MONTH(created_at)
    ORDER BY created_at ASC
");

// 2. Status reservasi
$status_reservasi = fetch_all("
    SELECT 
        status,
        COUNT(*) as total
    FROM reservasi
    GROUP BY status
");

// 3. Recent reservations
$recent_reservations = fetch_all("
    SELECT 
        r.*,
        u.nama_lengkap,
        k.nama_kamar,
        k.tipe_kamar
    FROM reservasi r
    JOIN users u ON r.id_user = u.id_user
    JOIN kamar k ON r.id_kamar = k.id_kamar
    ORDER BY r.created_at DESC
    LIMIT 5
");
?>

<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-start-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-primary text-white me-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Users</div>
                        <div class="h4 mb-0"><?php echo $total_users; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-start-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success text-white me-3">
                        <i class="fas fa-bed"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Rooms</div>
                        <div class="h4 mb-0"><?php echo $total_kamar; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-start-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-info text-white me-3">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Reservations</div>
                        <div class="h4 mb-0"><?php echo $total_reservasi; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-start-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-warning text-white me-3">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Pending Cancellations</div>
                        <div class="h4 mb-0"><?php echo $pending_pembatalan; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue Card -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-money-bill-wave fa-3x text-success mb-3"></i>
                <h6 class="text-muted">Total Revenue</h6>
                <h3 class="text-success"><?php echo format_rupiah($total_revenue); ?></h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-calendar-day fa-3x text-primary mb-3"></i>
                <h6 class="text-muted">Today's Reservations</h6>
                <h3 class="text-primary"><?php echo $today_reservations; ?></h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-percentage fa-3x text-info mb-3"></i>
                <h6 class="text-muted">Average Rate</h6>
                <h3 class="text-info">
                    <?php 
                    $avg = $total_reservasi > 0 ? $total_revenue / $total_reservasi : 0;
                    echo format_rupiah($avg); 
                    ?>
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart: Reservations per Month -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-line me-2"></i>Reservations Trend (Last 6 Months)
            </div>
            <div class="card-body">
                <div id="reservasiChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
    
    <!-- Chart: Reservation Status -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-pie me-2"></i>Reservation Status
            </div>
            <div class="card-body">
                <div id="statusChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Reservations -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list me-2"></i>Recent Reservations
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($recent_reservations) > 0): ?>
                                <?php foreach ($recent_reservations as $res): ?>
                                    <tr>
                                        <td>#<?php echo $res['id_reservasi']; ?></td>
                                        <td><?php echo $res['nama_lengkap']; ?></td>
                                        <td><?php echo $res['nama_kamar']; ?> <small class="text-muted">(<?php echo $res['tipe_kamar']; ?>)</small></td>
                                        <td><?php echo format_tanggal($res['tgl_checkin'], 'd M Y'); ?></td>
                                        <td><?php echo format_tanggal($res['tgl_checkout'], 'd M Y'); ?></td>
                                        <td><?php echo format_rupiah($res['total_harga']); ?></td>
                                        <td>
                                            <?php
                                            $badge_class = [
                                                'menunggu_bayar' => 'warning',
                                                'menunggu_verifikasi' => 'info',
                                                'lunas' => 'success',
                                                'batal' => 'danger',
                                                'selesai' => 'secondary'
                                            ];
                                            $class = $badge_class[$res['status']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?php echo $class; ?>"><?php echo ucfirst(str_replace('_', ' ', $res['status'])); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No reservations yet</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Data untuk chart
const reservasiData = <?php echo json_encode($reservasi_per_bulan); ?>;
const statusData = <?php echo json_encode($status_reservasi); ?>;

// Chart 1: Reservasi per Bulan (Line Chart)
Highcharts.chart('reservasiChart', {
    chart: {
        type: 'line'
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: reservasiData.map(item => item.bulan)
    },
    yAxis: {
        title: {
            text: 'Number of Reservations'
        },
        allowDecimals: false
    },
    series: [{
        name: 'Reservations',
        data: reservasiData.map(item => parseInt(item.total)),
        color: '#667eea'
    }],
    credits: {
        enabled: false
    }
});

// Chart 2: Status Reservasi (Pie Chart)
Highcharts.chart('statusChart', {
    chart: {
        type: 'pie'
    },
    title: {
        text: ''
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
        }
    },
    series: [{
        name: 'Reservations',
        data: statusData.map(item => ({
            name: item.status.charAt(0).toUpperCase() + item.status.slice(1).replace('_', ' '),
            y: parseInt(item.total)
        }))
    }],
    credits: {
        enabled: false
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>