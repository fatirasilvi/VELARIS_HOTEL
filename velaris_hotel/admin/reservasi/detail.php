<?php
// Load config dulu
require_once '../../config/database.php';
require_once '../../config/functions.php';

$page_title = 'Reservation Detail';
require_once '../includes/header.php';

require_staff();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get reservation detail
$reservasi = fetch_single("
    SELECT 
        r.*,
        u.nama_lengkap,
        u.email,
        u.no_hp,
        k.nama_kamar,
        k.tipe_kamar,
        k.harga as harga_kamar,
        k.foto_kamar
    FROM reservasi r
    JOIN users u ON r.id_user = u.id_user
    JOIN kamar k ON r.id_kamar = k.id_kamar
    WHERE r.id_reservasi = $id
");

if (!$reservasi) {
    redirect('index.php', 'Reservation not found', 'danger');
}

// Get experiences booked
$experiences = fetch_all("
    SELECT 
        e.nama_aktivitas,
        e.harga,
        re.jumlah
    FROM reservasi_experience re
    JOIN experiences e ON re.id_experience = e.id_experience
    WHERE re.id_reservasi = $id
");

// Calculate nights
$checkin = new DateTime($reservasi['tgl_checkin']);
$checkout = new DateTime($reservasi['tgl_checkout']);
$nights = $checkin->diff($checkout)->days;
?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-file-invoice me-2"></i>Reservation #<?php echo $reservasi['id_reservasi']; ?>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Guest Information</h6>
                        <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($reservasi['nama_lengkap']); ?></p>
                        <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($reservasi['email']); ?></p>
                        <p class="mb-1"><strong>Phone:</strong> <?php echo htmlspecialchars($reservasi['no_hp']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Booking Information</h6>
                        <p class="mb-1"><strong>Check-in:</strong> <?php echo format_tanggal($reservasi['tgl_checkin'], 'd F Y'); ?></p>
                        <p class="mb-1"><strong>Check-out:</strong> <?php echo format_tanggal($reservasi['tgl_checkout'], 'd F Y'); ?></p>
                        <p class="mb-1"><strong>Duration:</strong> <?php echo $nights; ?> night(s)</p>
                        <p class="mb-1"><strong>Booked on:</strong> <?php echo format_tanggal($reservasi['created_at'], 'd M Y, H:i'); ?></p>
                    </div>
                </div>

                <hr>

                <h6 class="text-muted mb-3">Room Details</h6>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <?php if ($reservasi['foto_kamar']): ?>
                            <img src="../../uploads/kamar/<?php echo $reservasi['foto_kamar']; ?>" 
                                 alt="Room" class="img-fluid rounded">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-9">
                        <h5><?php echo htmlspecialchars($reservasi['nama_kamar']); ?></h5>
                        <p class="mb-1"><span class="badge bg-info"><?php echo htmlspecialchars($reservasi['tipe_kamar']); ?></span></p>
                        <p class="mb-1"><strong>Quantity:</strong> <?php echo $reservasi['jumlah_kamar']; ?> room(s)</p>
                        <p class="mb-1"><strong>Price per night:</strong> <?php echo format_rupiah($reservasi['harga_kamar']); ?></p>
                    </div>
                </div>

                <?php if (count($experiences) > 0): ?>
                <hr>
                <h6 class="text-muted mb-3">Additional Experiences</h6>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $exp_total = 0;
                        foreach ($experiences as $exp): 
                            $subtotal = $exp['harga'] * $exp['jumlah'];
                            $exp_total += $subtotal;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($exp['nama_aktivitas']); ?></td>
                            <td><?php echo $exp['jumlah']; ?></td>
                            <td><?php echo format_rupiah($exp['harga']); ?></td>
                            <td><?php echo format_rupiah($subtotal); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>

                <hr>

                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Room Subtotal:</strong></td>
                                <td class="text-end"><?php echo format_rupiah($reservasi['harga_kamar'] * $nights * $reservasi['jumlah_kamar']); ?></td>
                            </tr>
                            <?php if (isset($exp_total) && $exp_total > 0): ?>
                            <tr>
                                <td><strong>Experiences Subtotal:</strong></td>
                                <td class="text-end"><?php echo format_rupiah($exp_total); ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr class="table-primary">
                                <td><strong>Total Amount:</strong></td>
                                <td class="text-end"><h5 class="mb-0"><?php echo format_rupiah($reservasi['total_harga']); ?></h5></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if ($reservasi['bukti_bayar']): ?>
                <hr>
                <h6 class="text-muted mb-3">Payment Proof</h6>
                <img src="../../uploads/bukti_bayar/<?php echo $reservasi['bukti_bayar']; ?>" 
                     alt="Payment Proof" class="img-fluid rounded" style="max-width: 400px;">
                <?php endif; ?>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i>Status
            </div>
            <div class="card-body">
                <?php
                $badge_class = [
                    'menunggu_bayar' => 'warning',
                    'menunggu_verifikasi' => 'info',
                    'lunas' => 'success',
                    'batal' => 'danger',
                    'selesai' => 'secondary'
                ];
                $class = $badge_class[$reservasi['status']] ?? 'secondary';
                $status_text = ucfirst(str_replace('_', ' ', $reservasi['status']));
                ?>
                <h4 class="text-center mb-3">
                    <span class="badge bg-<?php echo $class; ?>"><?php echo $status_text; ?></span>
                </h4>

                <div class="d-grid">
                    <button class="btn btn-primary" onclick="updateStatus(<?php echo $reservasi['id_reservasi']; ?>)">
                        <i class="fas fa-edit me-1"></i>Update Status
                    </button>
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
                    <input type="hidden" id="reservasi_id" name="id" value="<?php echo $reservasi['id_reservasi']; ?>">
                    <div class="mb-3">
                        <label class="form-label">New Status</label>
                        <select name="status" id="new_status" class="form-select" required>
                            <option value="menunggu_bayar" <?php echo $reservasi['status'] == 'menunggu_bayar' ? 'selected' : ''; ?>>Waiting for Payment</option>
                            <option value="menunggu_verifikasi" <?php echo $reservasi['status'] == 'menunggu_verifikasi' ? 'selected' : ''; ?>>Waiting for Verification</option>
                            <option value="lunas" <?php echo $reservasi['status'] == 'lunas' ? 'selected' : ''; ?>>Paid</option>
                            <option value="batal" <?php echo $reservasi['status'] == 'batal' ? 'selected' : ''; ?>>Cancelled</option>
                            <option value="selesai" <?php echo $reservasi['status'] == 'selesai' ? 'selected' : ''; ?>>Completed</option>
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