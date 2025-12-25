<?php
session_start();

/**
 * AMANKAN HALAMAN (WAJIB LOGIN)
 */
require_once "middleware/customer_auth.php";
require_once "config/database.php";

/**
 * Ambil parameter dari booking
 */
$id_kamar = $_GET['id_kamar'] ?? null;
$checkin  = $_GET['checkin'] ?? null;
$checkout = $_GET['checkout'] ?? null;

if (!$id_kamar || !$checkin || !$checkout) {
    die("Data reservasi tidak lengkap.");
}

/**
 * Ambil data kamar
 */
$stmt = $conn->prepare("SELECT * FROM kamar WHERE id_kamar = ?");
$stmt->bind_param("i", $id_kamar);
$stmt->execute();
$kamar = $stmt->get_result()->fetch_assoc();

if (!$kamar) {
    die("Kamar tidak ditemukan.");
}

/**
 * Hitung jumlah malam
 */
$checkin_date  = new DateTime($checkin);
$checkout_date = new DateTime($checkout);
$jumlah_malam  = $checkout_date->diff($checkin_date)->days;

if ($jumlah_malam <= 0) {
    die("Tanggal check-out harus setelah check-in.");
}

/**
 * Hitung total harga
 */
$total_harga = $jumlah_malam * $kamar['harga'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reservation Summary - Velaris Hotel</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<header class="header">
    <h2>VELARIS HOTEL</h2>
    <div>
        👤 <?= htmlspecialchars($_SESSION['customer_name']); ?>
    </div>
</header>

<!-- ================= RESERVATION SUMMARY ================= -->
<section class="reservation-container">
    <h1>Reservation Summary</h1>

    <div class="reservation-box">
        <img src="uploads/kamar/<?= htmlspecialchars($kamar['foto_kamar']); ?>" 
             alt="<?= htmlspecialchars($kamar['nama_kamar']); ?>">

        <div class="reservation-info">
            <h2><?= htmlspecialchars($kamar['nama_kamar']); ?></h2>

            <p><strong>Check-in:</strong> <?= date("d M Y", strtotime($checkin)); ?></p>
            <p><strong>Check-out:</strong> <?= date("d M Y", strtotime($checkout)); ?></p>
            <p><strong>Jumlah Malam:</strong> <?= $jumlah_malam; ?> malam</p>

            <hr>

            <p><strong>Harga / malam:</strong> 
                Rp <?= number_format($kamar['harga'], 0, ',', '.'); ?>
            </p>

            <h3>Total: Rp <?= number_format($total_harga, 0, ',', '.'); ?></h3>

            <!-- ================= FORM KE PAYMENT ================= -->
            <form action="payment.php" method="POST">
                <input type="hidden" name="id_kamar" value="<?= $id_kamar; ?>">
                <input type="hidden" name="checkin" value="<?= $checkin; ?>">
                <input type="hidden" name="checkout" value="<?= $checkout; ?>">
                <input type="hidden" name="total_harga" value="<?= $total_harga; ?>">

                <button type="submit" class="btn-primary">
                    Proceed to Payment
                </button>
            </form>
        </div>
    </div>
</section>

</body>
</html>
