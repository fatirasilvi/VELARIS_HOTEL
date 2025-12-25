<?php
session_start();

$id_reservasi = $_GET['id_reservasi'] ?? null;

if (!$id_reservasi) {
    die("ID reservasi tidak valid.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Berhasil | Crystalkuta Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/payment.css">
    <style>
        .success-container {
            max-width: 600px;
            margin: 80px auto;
            text-align: center;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .success-icon {
            font-size: 64px;
            color: #2ecc71;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 25px;
            background: #2ecc71;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .btn:hover {
            background: #27ae60;
        }
    </style>
</head>
<body>

<header class="header">
    <h3>Crystalkuta Hotel ★★★★</h3>
</header>

<section class="success-container">
    <div class="success-icon">✅</div>

    <h1>Pembayaran Berhasil</h1>
    <p>
        Terima kasih atas reservasi Anda.<br>
        Pembayaran telah berhasil diproses.
    </p>

    <p>
        Tim kami akan segera menghubungi Anda melalui email untuk detail reservasi.
    </p>

    <a href="booking_detail.php?id_reservasi=<?= $id_reservasi; ?>" class="btn-primary">Lihat Detail Booking</a>
</section>

<footer class="footer">
    <p>&copy; <?= date('Y'); ?> Crystalkuta Hotel</p>
</footer>

</body>
</html>
