<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Gagal | Crystalkuta Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/payment.css">
    <style>
        .error-container {
            max-width: 600px;
            margin: 80px auto;
            text-align: center;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .error-icon {
            font-size: 64px;
            color: #e74c3c;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 25px;
            background: #e74c3c;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>

<header class="header">
    <h3>Crystalkuta Hotel ★★★★</h3>
</header>

<section class="error-container">
    <div class="error-icon">❌</div>

    <h1>Pembayaran Gagal</h1>
    <p>
        Maaf, pembayaran Anda tidak dapat diproses.
    </p>

    <p>
        Pastikan:
        <br>• Nomor kartu diisi dengan benar
        <br>• Bukti transfer telah diunggah
    </p>

    <a href="booking.php" class="btn">Coba Lagi</a>
</section>

<footer class="footer">
    <p>&copy; <?= date('Y'); ?> Crystalkuta Hotel</p>
</footer>

</body>
</html>
