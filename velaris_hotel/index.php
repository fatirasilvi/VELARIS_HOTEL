<?php
session_start();
require_once "config/database.php";

$db = new Koneksi();
$conn = $db->getKoneksi();

// ambil data
$kamar = $conn->query("SELECT * FROM kamar LIMIT 3");
$experience = $conn->query("SELECT * FROM experiences LIMIT 2");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Velaris Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ================= NAVBAR ================= -->
<header class="navbar">
    <div class="container">
        <h1 class="logo">VELARIS HOTEL</h1>

        <nav>
            <a href="index.php" class="active">Home</a>
            <a href="rooms.php">Room</a>
            <a href="experience.php">Experience</a>
            <a href="contact.php">Contact</a>

            <!-- BUTTON BOOKING -->
            <a href="booking.php" class="btn-booking">Booking Now</a>
        </nav>
    </div>
</header>

<!-- ================= HERO ================= -->
<section class="hero">
    <img src="assets/img/hero.jpg" alt="Velaris Hotel">
    <div class="hero-overlay">
        <h2>VELARIS HOTEL – INDONESIA</h2>
        <p>Experience luxury and comfort with unforgettable moments</p>
    </div>
</section>

<!-- ================= ABOUT ================= -->
<section class="about container">
    <div class="about-text">
        <h3>VELARIS HOTEL<br>INDONESIA</h3>
        <p>
            We believe in providing extraordinary hospitality,
            combining luxury, comfort, and unforgettable experiences
            for every guest.
        </p>
        <a href="#" class="btn-outline">Hotel Details</a>
    </div>

    <div class="about-info">
        <ul>
            <li>📍 Bali, Indonesia</li>
            <li>🛏 120+ Rooms</li>
            <li>⭐ Luxury Experience</li>
        </ul>
    </div>
</section>

<!-- ================= ROOMS ================= -->
<section class="rooms container">
    <h2 class="section-title">Rooms & Suites</h2>

    <div class="room-grid">
        <?php while($r = $kamar->fetch_assoc()): ?>
            <div class="room-card">
                <img src="uploads/kamar/<?= $r['foto_kamar'] ?>" alt="<?= $r['nama_kamar'] ?>">
                <div class="room-info">
                    <h3><?= $r['nama_kamar'] ?></h3>
                    <p>Rp <?= number_format($r['harga'], 0, ',', '.') ?> / malam</p>
                    <a href="#" class="btn-small">Discover More</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- ================= EXPERIENCE ================= -->
<section class="experience container">
    <h2 class="section-title">Experience</h2>

    <div class="experience-grid">
        <?php while($e = $experience->fetch_assoc()): ?>
            <div class="experience-card">
                <img src="uploads/experiences/<?= $e['foto'] ?>" alt="<?= $e['nama_aktivitas'] ?>">
                <h3><?= $e['nama_aktivitas'] ?></h3>
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
