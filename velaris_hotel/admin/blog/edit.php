<?php
// Load config dulu
require_once '../../config/database.php';
require_once '../../config/functions.php';

$page_title = 'Edit Article';
require_once '../includes/header.php';

require_staff();

$error = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get blog data
$blog = fetch_single("SELECT * FROM blog WHERE id_blog = $id");
if (!$blog) {
    redirect('index.php', 'Article not found', 'danger');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = sanitize($_POST['judul']);
    $isi_konten = $_POST['isi_konten']; // Jangan sanitize karena bisa ada HTML
    $penulis = sanitize($_POST['penulis']);
    
    // Validasi
    if (empty($judul) || empty($isi_konten) || empty($penulis)) {
        $error = 'All fields are required';
    } else {
        $gambar = $blog['gambar']; // Pakai gambar lama sebagai default
        
        // Upload gambar baru jika ada
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] != UPLOAD_ERR_NO_FILE) {
            $upload = upload_file($_FILES['gambar'], '../../uploads/blog/', ['jpg', 'jpeg', 'png', 'gif']);
            
            if ($upload['success']) {
                // Hapus gambar lama
                if ($blog['gambar'] && file_exists('../../uploads/blog/' . $blog['gambar'])) {
                    delete_file('../../uploads/blog/' . $blog['gambar']);
                }
                $gambar = $upload['filename'];
            } else {
                $error = $upload['message'];
            }
        }
        
        if (!$error) {
            // Update blog
            $sql = "UPDATE blog SET 
                    judul = '" . escape($judul) . "',
                    isi_konten = '" . escape($isi_konten) . "',
                    gambar = '" . escape($gambar) . "',
                    penulis = '" . escape($penulis) . "'
                    WHERE id_blog = $id";
            
            if (execute($sql)) {
                log_activity("Updated blog article: $judul (ID: $id)");
                redirect('index.php', 'Article updated successfully', 'success');
            } else {
                $error = 'Failed to update article';
            }
        }
    }
}
?>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-edit me-2"></i>Edit Article
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Article Title <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" required 
                               value="<?php echo htmlspecialchars($blog['judul']); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Author Name <span class="text-danger">*</span></label>
                        <input type="text" name="penulis" class="form-control" required 
                               value="<?php echo htmlspecialchars($blog['penulis']); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Current Thumbnail</label>
                        <?php if ($blog['gambar']): ?>
                            <div class="mb-2">
                                <img src="../../uploads/blog/<?php echo $blog['gambar']; ?>" 
                                     alt="Blog Image" class="img-thumbnail" style="max-width: 300px;">
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No image uploaded</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Upload New Thumbnail</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                        <small class="text-muted">Leave blank to keep current image. Max 5MB.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Article Content <span class="text-danger">*</span></label>
                        <textarea name="isi_konten" class="form-control" rows="15" required><?php echo htmlspecialchars($blog['isi_konten']); ?></textarea>
                        <small class="text-muted">You can use basic HTML tags</small>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Article
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>