<?php
session_start();
require_once "config/database.php";

$db = new Koneksi();
$conn = $db->getKoneksi();

// ambil semua experience
$experiences = $conn->query("SELECT * FROM experiences");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Experience | Velaris Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/experience.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<header class="header">
    <div class="logo">VELARIS HOTEL</div>
    <nav>
        <a href="index.php">Home</a>
        <a href="rooms.php">Room</a>
        <a href="experience.php" class="active">Experience</a>
        <a href="contact.php">Contact</a>
        <a href="booking.php" class="btn-booking">Booking Now</a>
    </nav>
</header>

<!-- ================= HERO ================= -->
<section class="hero">
    <img src="assets/img/experience-hero.jpg" alt="Experience">
</section>

<!-- ================= ACTIVITIES ================= -->
<section class="section">
    <h2>ACTIVITIES</h2>

    <div class="experience-grid">
        <?php while($exp = $experiences->fetch_assoc()): ?>
            <div class="experience-card">
                <img src="uploads/experiences/<?= $exp['foto']; ?>" alt="<?= $exp['nama_aktivitas']; ?>">
                <h3><?= $exp['nama_aktivitas']; ?></h3>
                <p><?= substr($exp['deskripsi'], 0, 120); ?>...</p>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- ================= FOOTER ================= -->
<footer class="footer">
    <p>&copy; <?= date('Y'); ?> Velaris Hotel. All Rights Reserved.</p>
</footer>

</body>
</html>
