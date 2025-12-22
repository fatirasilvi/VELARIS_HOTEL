<?php
// Load config dulu
require_once '../../config/database.php';
require_once '../../config/functions.php';

$page_title = 'Manage Reservations';
require_once '../includes/header.php';

require_staff();

// Get all reservations with user and room info
$reservations = fetch_all("
    SELECT 
        r.*,
        u.nama_lengkap,
        u.email,
        u.no_hp,
        k.nama_kamar,
        k.tipe_kamar
    FROM reservasi r
    JOIN users u ON r.id_user = u.id_user
    JOIN kamar k ON r.id_kamar = k.id_kamar
    ORDER BY r.created_at DESC
");
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-calendar-check me-2"></i>Reservation Management
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="reservationsTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Qty</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $res): ?>
                            <tr>
                                <td><strong>#<?php echo $res['id_reservasi']; ?></strong></td>
                                <td>
                                    <?php echo htmlspecialchars($res['nama_lengkap']); ?><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($res['email']); ?></small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($res['nama_kamar']); ?><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($res['tipe_kamar']); ?></small>
                                </td>
                                <td><?php echo format_tanggal($res['tgl_checkin'], 'd M Y'); ?></td>
                                <td><?php echo format_tanggal($res['tgl_checkout'], 'd M Y'); ?></td>
                                <td><span class="badge bg-info"><?php echo $res['jumlah_kamar']; ?> room(s)</span></td>
                                <td><strong><?php echo format_rupiah($res['total_harga']); ?></strong></td>
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
                                    $status_text = ucfirst(str_replace('_', ' ', $res['status']));
                                    ?>
                                    <span class="badge bg-<?php echo $class; ?>"><?php echo $status_text; ?></span>
                                </td>
                                <td>
                                    <a href="detail.php?id=<?php echo $res['id_reservasi']; ?>" class="btn btn-sm btn-info" title="View Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button onclick="updateStatus(<?php echo $res['id_reservasi']; ?>)" class="btn btn-sm btn-warning" title="Update Status">
                                        <i class="fas fa-edit"></i>
                                    </button>
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

<!-- Modal Update Status -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Reservation Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    <input type="hidden" id="reservasi_id" name="id">
                    <div class="mb-3">
                        <label class="form-label">New Status</label>
                        <select name="status" id="new_status" class="form-select" required>
                            <option value="menunggu_bayar">Waiting for Payment</option>
                            <option value="menunggu_verifikasi">Waiting for Verification</option>
                            <option value="lunas">Paid</option>
                            <option value="batal">Cancelled</option>
                            <option value="selesai">Completed</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveStatus()">Save Status</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#reservationsTable').DataTable({
        order: [[0, 'desc']],
        pageLength: 10
    });
});

function updateStatus(id) {
    $('#reservasi_id').val(id);
    $('#statusModal').modal('show');
}

function saveStatus() {
    const formData = $('#statusForm').serialize();
    
    $.ajax({
        url: 'update_status.php',
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
            alert('Error updating status');
        }
    });
}
</script>

<?php require_once '../includes/footer.php'; ?>