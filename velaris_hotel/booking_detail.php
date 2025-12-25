<?php
session_start();
require_once "config/database.php";

/**
 * HARUS LOGIN
 */
if (!isset($_SESSION['customer_id'])) {
    header("Location: auth/login.php");
    exit;
}

/**
 * AMBIL ID RESERVASI
 */
$id_reservasi = $_GET['id_reservasi'] ?? null;

if (!$id_reservasi) {
    die("ID reservasi tidak ditemukan.");
}

/**
 * AMBIL DATA RESERVASI + KAMAR
 */
$stmt = $conn->prepare("
    SELECT r.*, k.nama_kamar, k.foto_kamar, k.harga
    FROM reservasi r
    JOIN kamar k ON r.id_kamar = k.id_kamar
    WHERE r.id_reservasi = ? 
      AND r.id_user = ?
");
$stmt->bind_param("ii", $id_reservasi, $_SESSION['customer_id']);
$stmt->execute();
$reservasi = $stmt->get_result()->fetch_assoc();

if (!$reservasi) {
    die("Reservasi tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Booking Detail | Velaris Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .detail-container {
            max-width: 900px;
            margin: 60px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,.1);
        }

        .detail-header {
            display: flex;
            gap: 20px;
        }

        .detail-header img {
            width: 300px;
            border-radius: 10px;
        }

        .detail-info h2 {
            margin-bottom: 10px;
        }

        .detail-info p {
            margin: 6px 0;
        }

        .total {
            font-size: 20px;
            margin-top: 15px;
            font-weight: bold;
        }

        .actions {
            margin-top: 30px;
            display: flex;
            gap: 15px;
        }

        .btn-cancel {
            background: #e74c3c;
            color: #fff;
            padding: 12px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-cancel:hover {
            background: #c0392b;
        }

        .btn-back {
            background: #3498db;
            color: #fff;
            padding: 12px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-back:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<header class="header">
    <h3>VELARIS HOTEL</h3>
    <div>👤 <?= htmlspecialchars($_SESSION['customer_name']); ?></div>
</header>

<!-- DETAIL RESERVASI -->
<section class="detail-container">

    <h1>Booking Detail</h1>
    <hr><br>

    <div class="detail-header">
        <img src="uploads/kamar/<?= $reservasi['foto_kamar']; ?>" alt="<?= $reservasi['nama_kamar']; ?>">

        <div class="detail-info">
            <h2><?= $reservasi['nama_kamar']; ?></h2>

            <p><strong>Check-in:</strong> <?= date('d M Y', strtotime($reservasi['checkin'])); ?></p>
            <p><strong>Check-out:</strong> <?= date('d M Y', strtotime($reservasi['checkout'])); ?></p>
            <p><strong>Jumlah Malam:</strong> <?= $reservasi['jumlah_malam']; ?> malam</p>

            <p><strong>Status:</strong> 
                <span style="color:green;font-weight:bold;">
                    <?= strtoupper($reservasi['status']); ?>
                </span>
            </p>

            <div class="total">
                Total Pembayaran: Rp <?= number_format($reservasi['total_harga'], 0, ',', '.'); ?>
            </div>
        </div>
    </div>

    <!-- ACTION -->
    <div class="actions">
        <a href="index.php" class="btn-back">Kembali ke Home</a>

        <?php if ($reservasi['status'] !== 'cancelled'): ?>
            <a 
                href="pembatalan.php?id_reservasi=<?= $reservasi['id_reservasi']; ?>" 
                class="btn-cancel"
                onclick="return confirm('Yakin ingin membatalkan reservasi ini?');"
            >
                Batalkan Reservasi
            </a>
        <?php endif; ?>
    </div>

</section>

<footer class="footer">
    <p>&copy; <?= date('Y'); ?> Velaris Hotel</p>
</footer>

</body>
</html>
