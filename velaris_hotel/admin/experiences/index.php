<?php
// Load config dulu
require_once '../../config/database.php';
require_once '../../config/functions.php';

$page_title = 'Manage Experiences';
require_once '../includes/header.php';

require_staff();

// Get all experiences
$experiences = fetch_all("SELECT * FROM experiences ORDER BY id_experience DESC");
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-star me-2"></i>Experience Management</span>
                <a href="create.php" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i>Add New Experience
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="experiencesTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Photo</th>
                                <th>Activity Name</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($experiences as $exp): ?>
                            <tr>
                                <td><?php echo $exp['id_experience']; ?></td>
                                <td>
                                    <?php if ($exp['foto']): ?>
                                        <img src="../../uploads/experiences/<?php echo $exp['foto']; ?>" 
                                             alt="Experience Photo" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No Photo</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($exp['nama_aktivitas']); ?></strong></td>
                                <td>
                                    <?php if ($exp['harga'] > 0): ?>
                                        <?php echo format_rupiah($exp['harga']); ?>
                                    <?php else: ?>
                                        <span class="badge bg-success">Free</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                    $desc = htmlspecialchars($exp['deskripsi']);
                                    echo strlen($desc) > 50 ? substr($desc, 0, 50) . '...' : $desc;
                                    ?>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?php echo $exp['id_experience']; ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteExperience(<?php echo $exp['id_experience']; ?>)" class="btn btn-sm btn-danger">
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
    $('#experiencesTable').DataTable({
        order: [[0, 'desc']],
        pageLength: 10
    });
});

function deleteExperience(id) {
    if (confirm('Are you sure you want to delete this experience?')) {
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
                alert('Error deleting experience');
            }
        });
    }
}
</script>

<?php require_once '../includes/footer.php'; ?>