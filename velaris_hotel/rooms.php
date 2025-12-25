<?php
session_start();
require_once "config/database.php";

$db = new Koneksi();
$conn = $db->getKoneksi();

// ambil semua kamar yang tersedia
$kamar = $conn->query("SELECT * FROM kamar WHERE stok > 0");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rooms | Velaris Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/rooms.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<header class="header">
    <div class="logo">VELARIS HOTEL</div>

    <nav>
        <a href="index.php">Home</a>
        <a href="rooms.php" class="active">Room</a>
        <a href="experience.php">Experience</a>
        <a href="contact.php">Contact</a>
        <a href="booking.php" class="btn-booking">Booking Now</a>
    </nav>
</header>

<!-- ================= HERO ================= -->
<section class="hero">
    <img src="assets/img/rooms-hero.jpg" alt="Rooms">
</section>

<!-- ================= ROOMS LIST ================= -->
<section class="rooms-container">

<?php while($row = $kamar->fetch_assoc()): ?>
    <div class="room-card">
        <img src="uploads/kamar/<?= $row['foto_kamar']; ?>" alt="<?= $row['nama_kamar']; ?>">

        <div class="room-info">
            <h3><?= $row['nama_kamar']; ?></h3>
            <p><?= substr($row['deskripsi'], 0, 120); ?>...</p>

            <div class="room-actions">
                <span class="price">
                    Rp <?= number_format($row['harga'], 0, ',', '.'); ?> / night
                </span>

                <a href="booking.php?kamar=<?= $row['id_kamar']; ?>" class="btn">
                    Reserve Now
                </a>
            </div>
        </div>
    </div>
<?php endwhile; ?>

</section>

<!-- ================= FOOTER ================= -->
<footer class="footer">
    <p>&copy; <?= date('Y'); ?> Velaris Hotel. All Rights Reserved.</p>
</footer>

</body>
</html>
