<?php
// Load config dulu
require_once '../../config/database.php';
require_once '../../config/functions.php';

$page_title = 'Manage Cancellations';
require_once '../includes/header.php';

require_staff();

// Get all cancellations with reservation and user info
$cancellations = fetch_all("
    SELECT 
        p.*,
        r.tgl_checkin,
        r.tgl_checkout,
        r.total_harga,
        u.nama_lengkap,
        u.email,
        k.nama_kamar
    FROM pembatalan p
    JOIN reservasi r ON p.id_reservasi = r.id_reservasi
    JOIN users u ON r.id_user = u.id_user
    JOIN kamar k ON r.id_kamar = k.id_kamar
    ORDER BY p.tgl_pengajuan DESC
");

// Count pending
$pending_count = 0;
foreach ($cancellations as $cancel) {
    if ($cancel['status_pengajuan'] == 'pending') $pending_count++;
}
?>

<div class="row mb-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                <h3><?php echo $pending_count; ?></h3>
                <p class="text-muted mb-0">Pending Requests</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-times-circle me-2"></i>Cancellation Requests
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="cancellationsTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Reservation ID</th>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Check-in Date</th>
                                <th>Refund Amount</th>
                                <th>Request Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cancellations as $cancel): ?>
                            <tr>
                                <td><strong>#<?php echo $cancel['id_batal']; ?></strong></td>
                                <td><a href="../reservasi/detail.php?id=<?php echo $cancel['id_reservasi']; ?>">#<?php echo $cancel['id_reservasi']; ?></a></td>
                                <td>
                                    <?php echo htmlspecialchars($cancel['nama_lengkap']); ?><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($cancel['email']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($cancel['nama_kamar']); ?></td>
                                <td><?php echo format_tanggal($cancel['tgl_checkin'], 'd M Y'); ?></td>
                                <td><strong><?php echo format_rupiah($cancel['total_harga']); ?></strong></td>
                                <td><?php echo format_tanggal($cancel['tgl_pengajuan'], 'd M Y, H:i'); ?></td>
                                <td>
                                    <?php
                                    $badge_class = [
                                        'pending' => 'warning',
                                        'disetujui' => 'success',
                                        'ditolak' => 'danger'
                                    ];
                                    $class = $badge_class[$cancel['status_pengajuan']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $class; ?>"><?php echo ucfirst($cancel['status_pengajuan']); ?></span>
                                </td>
                                <td>
                                    <button onclick="viewDetail(<?php echo $cancel['id_batal']; ?>)" class="btn btn-sm btn-info" title="View Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php if ($cancel['status_pengajuan'] == 'pending'): ?>
                                    <button onclick="approveCancellation(<?php echo $cancel['id_batal']; ?>)" class="btn btn-sm btn-success" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button onclick="rejectCancellation(<?php echo $cancel['id_batal']; ?>)" class="btn btn-sm btn-danger" title="Reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancellation Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent">
                <div class="text-center">
                    <div class="spinner-border" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Cancellation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="rejectForm">
                    <input type="hidden" id="reject_id" name="id">
                    <div class="mb-3">
                        <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea name="catatan_admin" class="form-control" rows="4" required 
                                  placeholder="Explain why this cancellation is rejected..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="saveReject()">Reject Request</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#cancellationsTable').DataTable({
        order: [[0, 'desc']],
        pageLength: 10
    });
});

function viewDetail(id) {
    $('#detailContent').html('<div class="text-center"><div class="spinner-border" role="status"></div></div>');
    $('#detailModal').modal('show');
    
    $.ajax({
        url: 'detail.php',
        type: 'GET',
        data: { id: id },
        success: function(response) {
            $('#detailContent').html(response);
        },
        error: function() {
            $('#detailContent').html('<div class="alert alert-danger">Error loading detail</div>');
        }
    });
}

function approveCancellation(id) {
    if (confirm('Are you sure you want to APPROVE this cancellation request? This will update the reservation status to "Cancelled".')) {
        $.ajax({
            url: 'approve.php',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Error approving cancellation');
            }
        });
    }
}

function rejectCancellation(id) {
    $('#reject_id').val(id);
    $('#rejectModal').modal('show');
}

function saveReject() {
    const formData = $('#rejectForm').serialize();
    
    if (!$('textarea[name="catatan_admin"]').val().trim()) {
        alert('Please provide a reason for rejection');
        return;
    }
    
    $.ajax({
        url: 'reject.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert(response.message);
                location.reload();
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('Error rejecting cancellation');
        }
    });
}
</script>

<?php require_once '../includes/footer.php'; ?>