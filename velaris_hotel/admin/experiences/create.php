<?php
// Load config dulu
require_once '../../config/database.php';
require_once '../../config/functions.php';

$page_title = 'Add New Experience';
require_once '../includes/header.php';

require_staff();

$error = '';

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
        $foto = '';
        
        // Upload foto jika ada
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] != UPLOAD_ERR_NO_FILE) {
            $upload = upload_file($_FILES['foto'], '../../uploads/experiences/', ['jpg', 'jpeg', 'png', 'gif']);
            
            if ($upload['success']) {
                $foto = $upload['filename'];
            } else {
                $error = $upload['message'];
            }
        }
        
        if (!$error) {
            // Insert experience
            $sql = "INSERT INTO experiences (nama_aktivitas, deskripsi, harga, foto) 
                    VALUES ('" . escape($nama_aktivitas) . "', 
                            '" . escape($deskripsi) . "', 
                            '" . escape($harga) . "', 
                            '" . escape($foto) . "')";
            
            if (insert($sql)) {
                log_activity("Added new experience: $nama_aktivitas");
                redirect('index.php', 'Experience added successfully', 'success');
            } else {
                $error = 'Failed to add experience';
            }
        }
    }
}
?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-star me-2"></i>Add New Experience
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Activity Name <span class="text-danger">*</span></label>
                        <input type="text" name="nama_aktivitas" class="form-control" required 
                               value="<?php echo isset($_POST['nama_aktivitas']) ? htmlspecialchars($_POST['nama_aktivitas']) : ''; ?>"
                               placeholder="e.g., Morning Yoga Session">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Price (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="harga" class="form-control" required min="0" step="0.01"
                               value="<?php echo isset($_POST['harga']) ? htmlspecialchars($_POST['harga']) : ''; ?>"
                               placeholder="100000">
                        <small class="text-muted">Set to 0 for free activities</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="deskripsi" class="form-control" rows="4" 
                                  placeholder="Activity details and information..."><?php echo isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : ''; ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Activity Photo</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <small class="text-muted">Max 5MB. Supported: JPG, JPEG, PNG, GIF</small>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Save Experience
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>