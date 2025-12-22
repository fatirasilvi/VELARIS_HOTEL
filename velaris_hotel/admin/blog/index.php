<?php
// Load config dulu
require_once '../../config/database.php';
require_once '../../config/functions.php';

$page_title = 'Manage Blog';
require_once '../includes/header.php';

require_staff();

// Get all blog posts
$blogs = fetch_all("SELECT * FROM blog ORDER BY tgl_posting DESC");
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-blog me-2"></i>Blog Management</span>
                <a href="create.php" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i>Add New Article
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="blogTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Published Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($blogs as $blog): ?>
                            <tr>
                                <td><?php echo $blog['id_blog']; ?></td>
                                <td>
                                    <?php if ($blog['gambar']): ?>
                                        <img src="../../uploads/blog/<?php echo $blog['gambar']; ?>" 
                                             alt="Blog Image" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No Image</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($blog['judul']); ?></strong></td>
                                <td><?php echo htmlspecialchars($blog['penulis']); ?></td>
                                <td><?php echo format_tanggal($blog['tgl_posting'], 'd M Y, H:i'); ?></td>
                                <td>
                                    <a href="edit.php?id=<?php echo $blog['id_blog']; ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteBlog(<?php echo $blog['id_blog']; ?>)" class="btn btn-sm btn-danger">
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
    $('#blogTable').DataTable({
        order: [[0, 'desc']],
        pageLength: 10
    });
});

function deleteBlog(id) {
    if (confirm('Are you sure you want to delete this article?')) {
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
                alert('Error deleting article');
            }
        });
    }
}
</script>

<?php require_once '../includes/footer.php'; ?>