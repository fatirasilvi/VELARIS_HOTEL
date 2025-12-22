<?php
// Load config dulu
require_once '../../config/database.php';
require_once '../../config/functions.php';

$page_title = 'Manage Rooms';
require_once '../includes/header.php';

require_staff();

// Get all rooms
$rooms = fetch_all("SELECT * FROM kamar ORDER BY id_kamar DESC");
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-bed me-2"></i>Room Management</span>
                <a href="create.php" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i>Add New Room
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="roomsTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Photo</th>
                                <th>Room Name</th>
                                <th>Type</th>
                                <th>Price/Night</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rooms as $room): ?>
                            <tr>
                                <td><?php echo $room['id_kamar']; ?></td>
                                <td>
                                    <?php if ($room['foto_kamar']): ?>
                                        <img src="../../uploads/kamar/<?php echo $room['foto_kamar']; ?>" 
                                             alt="Room Photo" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No Photo</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($room['nama_kamar']); ?></strong></td>
                                <td><span class="badge bg-info"><?php echo htmlspecialchars($room['tipe_kamar']); ?></span></td>
                                <td><?php echo format_rupiah($room['harga']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $room['stok'] > 0 ? 'success' : 'danger'; ?>">
                                        <?php echo $room['stok']; ?> units
                                    </span>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?php echo $room['id_kamar']; ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteRoom(<?php echo $room['id_kamar']; ?>)" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
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

<script>
$(document).ready(function() {
    $('#roomsTable').DataTable({
        order: [[0, 'desc']],
        pageLength: 10
    });
});

function deleteRoom(id) {
    if (confirm('Are you sure you want to delete this room?')) {
        $.ajax({
            url: 'delete.php',
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
                alert('Error deleting room');
            }
        });
    }
}
</script>

<?php require_once '../includes/footer.php'; ?>