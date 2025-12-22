<?php
// Load config dulu
require_once '../../config/database.php';
require_once '../../config/functions.php';

$page_title = 'Edit Experience';
require_once '../includes/header.php';

require_staff();

$error = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get experience data
$experience = fetch_single("SELECT * FROM experiences WHERE id_experience = $id");
if (!$experience) {
    redirect('index.php', 'Experience not found', 'danger');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_aktivitas = sanitize($_POST['nama_aktivitas']);
    $deskripsi = sanitize($_POST['deskripsi']);
    $harga = sanitize($_POST['harga']);
    
    // Validasi
    if (empty($nama_aktivitas) || $harga === '') {
        $error = 'Activity name and price are required';
    } elseif (!is_numeric($harga) || $harga < 0) {
        $error = 'Price must be a valid number';
    } else {
        $foto = $experience['foto']; // Pakai foto lama sebagai default
        
        // Upload foto baru jika ada
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] != UPLOAD_ERR_NO_FILE) {
            $upload = upload_file($_FILES['foto'], '../../uploads/experiences/', ['jpg', 'jpeg', 'png', 'gif']);
            
            if ($upload['success']) {
                // Hapus foto lama
                if ($experience['foto'] && file_exists('../../uploads/experiences/' . $experience['foto'])) {
                    delete_file('../../uploads/experiences/' . $experience['foto']);
                }
                $foto = $upload['filename'];
            } else {
                $error = $upload['message'];
            }
        }
        
        if (!$error) {
            // Update experience
            $sql = "UPDATE experiences SET 
                    nama_aktivitas = '" . escape($nama_aktivitas) . "',
                    deskripsi = '" . escape($deskripsi) . "',
                    harga = '" . escape($harga) . "',
                    foto = '" . escape($foto) . "'
                    WHERE id_experience = $id";
            
            if (execute($sql)) {
                log_activity("Updated experience: $nama_aktivitas (ID: $id)");
                redirect('index.php', 'Experience updated successfully', 'success');
            } else {
                $error = 'Failed to update experience';
            }
        }
    }
}
?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-edit me-2"></i>Edit Experience
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Activity Name <span class="text-danger">*</span></label>
                        <input type="text" name="nama_aktivitas" class="form-control" required 
                               value="<?php echo htmlspecialchars($experience['nama_aktivitas']); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Price (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="harga" class="form-control" required min="0" step="0.01"
                               value="<?php echo $experience['harga']; ?>">
                        <small class="text-muted">Set to 0 for free activities</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="deskripsi" class="form-control" rows="4"><?php echo htmlspecialchars($experience['deskripsi']); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Current Photo</label>
                        <?php if ($experience['foto']): ?>
                            <div class="mb-2">
                                <img src="../../uploads/experiences/<?php echo $experience['foto']; ?>" 
                                     alt="Experience Photo" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No photo uploaded</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Upload New Photo</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <small class="text-muted">Leave blank to keep current photo. Max 5MB.</small>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Experience
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>