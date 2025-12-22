<?php
// Load config dulu
require_once '../../config/database.php';
require_once '../../config/functions.php';

$page_title = 'Edit Room';
require_once '../includes/header.php';

require_staff();

$error = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get room data
$room = fetch_single("SELECT * FROM kamar WHERE id_kamar = $id");
if (!$room) {
    redirect('index.php', 'Room not found', 'danger');
}

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
        $foto_kamar = $room['foto_kamar']; // Pakai foto lama sebagai default
        
        // Upload foto baru jika ada
        if (isset($_FILES['foto_kamar']) && $_FILES['foto_kamar']['error'] != UPLOAD_ERR_NO_FILE) {
            $upload = upload_file($_FILES['foto_kamar'], '../../uploads/kamar/', ['jpg', 'jpeg', 'png', 'gif']);
            
            if ($upload['success']) {
                // Hapus foto lama
                if ($room['foto_kamar'] && file_exists('../../uploads/kamar/' . $room['foto_kamar'])) {
                    delete_file('../../uploads/kamar/' . $room['foto_kamar']);
                }
                $foto_kamar = $upload['filename'];
            } else {
                $error = $upload['message'];
            }
        }
        
        if (!$error) {
            // Update room
            $sql = "UPDATE kamar SET 
                    nama_kamar = '" . escape($nama_kamar) . "',
                    tipe_kamar = '" . escape($tipe_kamar) . "',
                    harga = '" . escape($harga) . "',
                    deskripsi = '" . escape($deskripsi) . "',
                    foto_kamar = '" . escape($foto_kamar) . "',
                    stok = $stok
                    WHERE id_kamar = $id";
            
            if (execute($sql)) {
                log_activity("Updated room: $nama_kamar (ID: $id)");
                redirect('index.php', 'Room updated successfully', 'success');
            } else {
                $error = 'Failed to update room';
            }
        }
    }
}
?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-edit me-2"></i>Edit Room
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Room Name <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kamar" class="form-control" required 
                               value="<?php echo htmlspecialchars($room['nama_kamar']); ?>">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Room Type <span class="text-danger">*</span></label>
                            <select name="tipe_kamar" class="form-select" required>
                                <option value="Standard" <?php echo $room['tipe_kamar'] == 'Standard' ? 'selected' : ''; ?>>Standard</option>
                                <option value="Deluxe" <?php echo $room['tipe_kamar'] == 'Deluxe' ? 'selected' : ''; ?>>Deluxe</option>
                                <option value="Suite" <?php echo $room['tipe_kamar'] == 'Suite' ? 'selected' : ''; ?>>Suite</option>
                                <option value="VIP" <?php echo $room['tipe_kamar'] == 'VIP' ? 'selected' : ''; ?>>VIP</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price per Night (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control" required min="0" step="0.01"
                                   value="<?php echo $room['harga']; ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Stock/Availability <span class="text-danger">*</span></label>
                        <input type="number" name="stok" class="form-control" required min="0"
                               value="<?php echo $room['stok']; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="deskripsi" class="form-control" rows="4"><?php echo htmlspecialchars($room['deskripsi']); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Current Photo</label>
                        <?php if ($room['foto_kamar']): ?>
                            <div class="mb-2">
                                <img src="../../uploads/kamar/<?php echo $room['foto_kamar']; ?>" 
                                     alt="Room Photo" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No photo uploaded</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Upload New Photo</label>
                        <input type="file" name="foto_kamar" class="form-control" accept="image/*">
                        <small class="text-muted">Leave blank to keep current photo. Max 5MB.</small>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Room
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>