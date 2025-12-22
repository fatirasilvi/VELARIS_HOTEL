<?php
// Load config dulu
require_once '../../config/database.php';
require_once '../../config/functions.php';

$page_title = 'Add New Article';
require_once '../includes/header.php';

require_staff();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = sanitize($_POST['judul']);
    $isi_konten = $_POST['isi_konten']; // Jangan sanitize karena bisa ada HTML
    $penulis = sanitize($_POST['penulis']);
    
    // Validasi
    if (empty($judul) || empty($isi_konten) || empty($penulis)) {
        $error = 'All fields are required';
    } else {
        $gambar = '';
        
        // Upload gambar jika ada
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] != UPLOAD_ERR_NO_FILE) {
            $upload = upload_file($_FILES['gambar'], '../../uploads/blog/', ['jpg', 'jpeg', 'png', 'gif']);
            
            if ($upload['success']) {
                $gambar = $upload['filename'];
            } else {
                $error = $upload['message'];
            }
        }
        
        if (!$error) {
            // Insert blog
            $sql = "INSERT INTO blog (judul, isi_konten, gambar, penulis) 
                    VALUES ('" . escape($judul) . "', 
                            '" . escape($isi_konten) . "', 
                            '" . escape($gambar) . "', 
                            '" . escape($penulis) . "')";
            
            if (insert($sql)) {
                log_activity("Added new blog article: $judul");
                redirect('index.php', 'Article added successfully', 'success');
            } else {
                $error = 'Failed to add article';
            }
        }
    }
}
?>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-blog me-2"></i>Add New Article
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Article Title <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" required 
                               value="<?php echo isset($_POST['judul']) ? htmlspecialchars($_POST['judul']) : ''; ?>"
                               placeholder="Enter article title...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Author Name <span class="text-danger">*</span></label>
                        <input type="text" name="penulis" class="form-control" required 
                               value="<?php echo isset($_POST['penulis']) ? htmlspecialchars($_POST['penulis']) : $_SESSION['nama_lengkap']; ?>"
                               placeholder="Author name">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Thumbnail Image</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                        <small class="text-muted">Max 5MB. Supported: JPG, JPEG, PNG, GIF</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Article Content <span class="text-danger">*</span></label>
                        <textarea name="isi_konten" class="form-control" rows="15" required 
                                  placeholder="Write your article content here..."><?php echo isset($_POST['isi_konten']) ? htmlspecialchars($_POST['isi_konten']) : ''; ?></textarea>
                        <small class="text-muted">You can use basic HTML tags</small>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Publish Article
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>