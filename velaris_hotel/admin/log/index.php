<?php
// Load config dulu
require_once '../../config/database.php';
require_once '../../config/functions.php';

$page_title = 'Activity Log';
require_once '../includes/header.php';

// Hanya admin yang bisa akses
require_admin();

// Get all activity logs with user info
$logs = fetch_all("
    SELECT 
        l.*,
        u.nama_lengkap,
        u.email,
        u.role
    FROM log_aktivitas l
    JOIN users u ON l.id_user = u.id_user
    ORDER BY l.waktu DESC
    LIMIT 500
");

// Count total logs
$total_logs = fetch_single("SELECT COUNT(*) as total FROM log_aktivitas")['total'];

// Get today's logs count
$today_logs = fetch_single("SELECT COUNT(*) as total FROM log_aktivitas WHERE DATE(waktu) = CURDATE()")['total'];

// Get logs by user (top 5)
$logs_by_user = fetch_all("
    SELECT 
        u.nama_lengkap,
        u.role,
        COUNT(*) as total
    FROM log_aktivitas l
    JOIN users u ON l.id_user = u.id_user
    GROUP BY l.id_user
    ORDER BY total DESC
    LIMIT 5
");
?>

<div class="row mb-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-history fa-2x text-primary mb-2"></i>
                <h3><?php echo $total_logs; ?></h3>
                <p class="text-muted mb-0">Total Activities</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-calendar-day fa-2x text-success mb-2"></i>
                <h3><?php echo $today_logs; ?></h3>
                <p class="text-muted mb-0">Today's Activities</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x text-info mb-2"></i>
                <h3><?php echo count($logs_by_user); ?></h3>
                <p class="text-muted mb-0">Active Users</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list me-2"></i>Recent Activity Log (Last 500)
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="logTable" class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Role</th>
                                <th>Activity</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?php echo $log['id_log']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($log['nama_lengkap']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($log['email']); ?></small>
                                </td>
                                <td>
                                    <?php
                                    $badge_class = [
                                        'admin' => 'danger',
                                        'staff' => 'warning',
                                        'user' => 'info'
                                    ];
                                    $class = $badge_class[$log['role']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $class; ?>"><?php echo ucfirst($log['role']); ?></span>
                                </td>
                                <td>
                                    <?php 
                                    $aksi = htmlspecialchars($log['aksi']);
                                    
                                    // Add icons based on activity type
                                    if (strpos($aksi, 'Login') !== false) {
                                        echo '<i class="fas fa-sign-in-alt text-success me-1"></i>';
                                    } elseif (strpos($aksi, 'Logout') !== false) {
                                        echo '<i class="fas fa-sign-out-alt text-danger me-1"></i>';
                                    } elseif (strpos($aksi, 'Added') !== false || strpos($aksi, 'Created') !== false) {
                                        echo '<i class="fas fa-plus-circle text-success me-1"></i>';
                                    } elseif (strpos($aksi, 'Updated') !== false || strpos($aksi, 'Modified') !== false) {
                                        echo '<i class="fas fa-edit text-warning me-1"></i>';
                                    } elseif (strpos($aksi, 'Deleted') !== false || strpos($aksi, 'Removed') !== false) {
                                        echo '<i class="fas fa-trash text-danger me-1"></i>';
                                    } elseif (strpos($aksi, 'Approved') !== false) {
                                        echo '<i class="fas fa-check-circle text-success me-1"></i>';
                                    } elseif (strpos($aksi, 'Rejected') !== false) {
                                        echo '<i class="fas fa-times-circle text-danger me-1"></i>';
                                    } else {
                                        echo '<i class="fas fa-info-circle text-info me-1"></i>';
                                    }
                                    
                                    echo $aksi;
                                    ?>
                                </td>
                                <td>
                                    <small><?php echo format_tanggal($log['waktu'], 'd M Y'); ?></small><br>
                                    <small class="text-muted"><?php echo date('H:i:s', strtotime($log['waktu'])); ?></small>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-bar me-2"></i>Top Active Users
            </div>
            <div class="card-body">
                <?php if (count($logs_by_user) > 0): ?>
                    <div class="list-group">
                        <?php foreach ($logs_by_user as $user_log): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo htmlspecialchars($user_log['nama_lengkap']); ?></strong><br>
                                    <small class="text-muted">
                                        <span class="badge bg-<?php echo $user_log['role'] == 'admin' ? 'danger' : ($user_log['role'] == 'staff' ? 'warning' : 'info'); ?>">
                                            <?php echo ucfirst($user_log['role']); ?>
                                        </span>
                                    </small>
                                </div>
                                <span class="badge bg-primary rounded-pill"><?php echo $user_log['total']; ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">No activity yet</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i>About Activity Log
            </div>
            <div class="card-body">
                <p class="small text-muted mb-2">
                    <i class="fas fa-check-circle text-success me-1"></i> Tracks all admin and staff actions
                </p>
                <p class="small text-muted mb-2">
                    <i class="fas fa-clock text-info me-1"></i> Shows last 500 activities
                </p>
                <p class="small text-muted mb-2">
                    <i class="fas fa-lock text-warning me-1"></i> Admin access only
                </p>
                <p class="small text-muted mb-0">
                    <i class="fas fa-database text-primary me-1"></i> Stored permanently in database
                </p>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#logTable').DataTable({
        order: [[0, 'desc']],
        pageLength: 25,
        columnDefs: [
            { orderable: false, targets: [3] } // Activity column not sortable
        ]
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>