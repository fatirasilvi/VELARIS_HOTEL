<?php
// Load config dulu
require_once '../../config/database.php';
require_once '../../config/functions.php';

$page_title = 'Add New Room';
require_once '../includes/header.php';

require_staff();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kamar = sanitize($_POST['nama_kamar']);
    $tipe_kamar = sanitize($_POST['tipe_kamar']);
    $harga = sanitize($_POST['harga']);
    $deskripsi = sanitize($_POST['deskripsi']);
    $stok = (int)$_POST['stok'];
    
    // Validasi
    if (empty($nama_kamar) || empty($tipe_kamar) || empty($harga) || empty($stok)) {
        $error = 'All required fields must be filled';
    } elseif (!is_numeric($harga) || $harga < 0) {
        $error = 'Price must be a valid number';
    } elseif ($stok < 0) {
        $error = 'Stock must be a valid number';
    } else {
        $foto_kamar = '';
        
        // Upload foto jika ada
        if (isset($_FILES['foto_kamar']) && $_FILES['foto_kamar']['error'] != UPLOAD_ERR_NO_FILE) {
            $upload = upload_file($_FILES['foto_kamar'], '../../uploads/kamar/', ['jpg', 'jpeg', 'png', 'gif']);
            
            if ($upload['success']) {
                $foto_kamar = $upload['filename'];
            } else {
                $error = $upload['message'];
            }
        }
        
        if (!$error) {
            // Insert room
            $sql = "INSERT INTO kamar (nama_kamar, tipe_kamar, harga, deskripsi, foto_kamar, stok) 
                    VALUES ('" . escape($nama_kamar) . "', 
                            '" . escape($tipe_kamar) . "', 
                            '" . escape($harga) . "', 
                            '" . escape($deskripsi) . "', 
                            '" . escape($foto_kamar) . "', 
                            $stok)";
            
            if (insert($sql)) {
                log_activity("Added new room: $nama_kamar");
                redirect('index.php', 'Room added successfully', 'success');
            } else {
                $error = 'Failed to add room';
            }
        }
    }
}
?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-bed me-2"></i>Add New Room
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Room Name <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kamar" class="form-control" required 
                               value="<?php echo isset($_POST['nama_kamar']) ? htmlspecialchars($_POST['nama_kamar']) : ''; ?>"
                               placeholder="e.g., Deluxe Ocean View 101">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Room Type <span class="text-danger">*</span></label>
                            <select name="tipe_kamar" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="Standard" <?php echo (isset($_POST['tipe_kamar']) && $_POST['tipe_kamar'] == 'Standard') ? 'selected' : ''; ?>>Standard</option>
                                <option value="Deluxe" <?php echo (isset($_POST['tipe_kamar']) && $_POST['tipe_kamar'] == 'Deluxe') ? 'selected' : ''; ?>>Deluxe</option>
                                <option value="Suite" <?php echo (isset($_POST['tipe_kamar']) && $_POST['tipe_kamar'] == 'Suite') ? 'selected' : ''; ?>>Suite</option>
                                <option value="VIP" <?php echo (isset($_POST['tipe_kamar']) && $_POST['tipe_kamar'] == 'VIP') ? 'selected' : ''; ?>>VIP</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price per Night (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control" required min="0" step="0.01"
                                   value="<?php echo isset($_POST['harga']) ? htmlspecialchars($_POST['harga']) : ''; ?>"
                                   placeholder="350000">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Stock/Availability <span class="text-danger">*</span></label>
                        <input type="number" name="stok" class="form-control" required min="0"
                               value="<?php echo isset($_POST['stok']) ? htmlspecialchars($_POST['stok']) : ''; ?>"
                               placeholder="5">
                        <small class="text-muted">Number of available units for this room type</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="deskripsi" class="form-control" rows="4" 
                                  placeholder="Room facilities and description..."><?php echo isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : ''; ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Room Photo</label>
                        <input type="file" name="foto_kamar" class="form-control" accept="image/*">
                        <small class="text-muted">Max 5MB. Supported: JPG, JPEG, PNG, GIF</small>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Save Room
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>