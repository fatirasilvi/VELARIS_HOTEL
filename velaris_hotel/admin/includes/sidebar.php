<?php
// Pastikan tidak ada output sebelum tag PHP ini
// File ini di-include oleh header.php yang mungkin melakukan redirect
?>
<style>
    /* Sidebar Styling */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: var(--sidebar-width);
        height: 100vh;
        background: #2c3e50;
        padding-top: 20px;
        overflow-y: auto;
        z-index: 1001;
    }
    
    .sidebar::-webkit-scrollbar {
        width: 5px;
    }
    
    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
    }
    
    .sidebar-brand {
        padding: 0 20px 20px 20px;
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        text-align: center;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        margin-bottom: 20px;
    }
    
    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .sidebar-menu li a {
        display: block;
        padding: 12px 20px;
        color: rgba(255,255,255,0.8);
        text-decoration: none;
        transition: all 0.3s;
        border-left: 3px solid transparent;
    }
    
    .sidebar-menu li a:hover,
    .sidebar-menu li a.active {
        background: rgba(255,255,255,0.1);
        color: white;
        border-left-color: #667eea;
    }
    
    .sidebar-menu li a i {
        width: 25px;
        margin-right: 10px;
    }
</style>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-hotel me-2"></i>Velaris Hotel
    </div>
    
    <ul class="sidebar-menu">
        
        <?php 
        // Deteksi base path untuk link
        $base_path = '';
        $current_path = $_SERVER['PHP_SELF'];
        
        // Kalau file ada di subfolder (users/, kamar/, dll), naik 1 level
        if (preg_match('#/admin/[^/]+/[^/]+\.php#', $current_path)) {
            $base_path = '../';
        }
        
        // Deteksi halaman aktif berdasarkan folder
        $active_page = '';
        if (strpos($current_path, '/users/') !== false) {
            $active_page = 'users';
        } elseif (strpos($current_path, '/kamar/') !== false) {
            $active_page = 'kamar';
        } elseif (strpos($current_path, '/experiences/') !== false) {
            $active_page = 'experiences';
        } elseif (strpos($current_path, '/reservasi/') !== false) {
            $active_page = 'reservasi';
        } elseif (strpos($current_path, '/pembatalan/') !== false) {
            $active_page = 'pembatalan';
        } elseif (strpos($current_path, '/blog/') !== false) {
            $active_page = 'blog';
        } elseif (strpos($current_path, '/log/') !== false) {
            $active_page = 'log';
        } elseif (strpos($current_path, '/admin/index.php') !== false || $current_path == '/velaris_hotel/admin/' || $current_path == '/velaris_hotel/admin/index.php') {
            $active_page = 'dashboard';
        }
        ?>
        
        <li>
            <a href="<?php echo $base_path; ?>index.php" class="<?php echo $active_page == 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-dashboard"></i>Dashboard
            </a>
        </li>
        
        <?php if (is_admin()): ?>
        <li>
            <a href="<?php echo $base_path; ?>users/" class="<?php echo $active_page == 'users' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>Manage Users
            </a>
        </li>
        <?php endif; ?>
        
        <li>
            <a href="<?php echo $base_path; ?>kamar/" class="<?php echo $active_page == 'kamar' ? 'active' : ''; ?>">
                <i class="fas fa-bed"></i>Manage Rooms
            </a>
        </li>
        
        <li>
            <a href="<?php echo $base_path; ?>experiences/" class="<?php echo $active_page == 'experiences' ? 'active' : ''; ?>">
                <i class="fas fa-star"></i>Manage Experiences
            </a>
        </li>
        
        <li>
            <a href="<?php echo $base_path; ?>reservasi/" class="<?php echo $active_page == 'reservasi' ? 'active' : ''; ?>">
                <i class="fas fa-calendar-check"></i>Reservations
            </a>
        </li>
        
        <li>
            <a href="<?php echo $base_path; ?>pembatalan/" class="<?php echo $active_page == 'pembatalan' ? 'active' : ''; ?>">
                <i class="fas fa-times-circle"></i>Cancellations
            </a>
        </li>
        
        <li>
            <a href="<?php echo $base_path; ?>blog/" class="<?php echo $active_page == 'blog' ? 'active' : ''; ?>">
                <i class="fas fa-blog"></i>Manage Blog
            </a>
        </li>
        
        <?php if (is_admin()): ?>
        <li>
            <a href="<?php echo $base_path; ?>log/" class="<?php echo $active_page == 'log' ? 'active' : ''; ?>">
                <i class="fas fa-history"></i>Activity Log
            </a>
        </li>
        <?php endif; ?>
    </ul>
</div>