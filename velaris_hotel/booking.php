<?php
session_start();

/**
 * AMANKAN PARAMETER GET
 * Supaya tidak Undefined array key
 */
$checkin  = $_GET['checkin']  ?? '';
$checkout = $_GET['checkout'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking | Crystalkuta Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/booking.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<header class="header">
    <div class="logo">
        <h3>Crystalkuta Hotel ★★★★</h3>
        <p>Jl. Bypass Ngurah Rai No.999, Kuta</p>
    </div>

    <div class="header-action">
        <?php if (isset($_SESSION['customer_id'])): ?>
            <div class="user-name">
                👤 <?= htmlspecialchars($_SESSION['customer_name']); ?>
            </div>
        <?php else: ?>
            <a href="login.php" class="login-btn">Login</a>
        <?php endif; ?>
    </div>
</header>

<!-- ================= HERO ================= -->
<section class="hero">
    <img src="assets/img/hero-booking.jpg" alt="Hotel Lobby">
</section>

<!-- ================= SEARCH BAR ================= -->
<section class="search-bar">
    <form method="GET">
        <div class="field">
            <label>Check in</label>
            <input type="date" name="checkin" value="<?= $checkin; ?>" required>
        </div>

        <div class="field">
            <label>Check out</label>
            <input type="date" name="checkout" value="<?= $checkout; ?>" required>
        </div>

        <button type="submit" class="search-btn">Search</button>
    </form>
</section>

<!-- ================= ROOM LIST ================= -->
<section class="rooms">

    <!-- ROOM 1 -->
    <div class="room-card">
        <img src="assets/img/room1.jpg" alt="Executive Suite">

        <div class="room-info">
            <h3>EXECUTIVE SUITE</h3>
            <ul>
                <li>LCD Plasma TV with international channels</li>
                <li>King size bed</li>
                <li>Free Wi-Fi</li>
                <li>Coffee & tea making amenities</li>
            </ul>

            <p class="availability">✔ Free cancellation <br> ✔ Book now, pay later</p>
        </div>

        <div class="room-action">
            <p class="price">IDR 1.500.000</p>

            <?php if ($checkin && $checkout): ?>
                <a href="reservasi.php?id_kamar=1&checkin=<?= $checkin; ?>&checkout=<?= $checkout; ?>" class="btn">
                    Select
                </a>
            <?php else: ?>
                <button class="btn disabled" disabled>
                    Select
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- ROOM 2 -->
    <div class="room-card">
        <img src="assets/img/room2.jpg" alt="Family Suite">

        <div class="room-info">
            <h3>FAMILY SUITE</h3>
            <ul>
                <li>2 Bedrooms</li>
                <li>King size bed</li>
                <li>Free Wi-Fi</li>
                <li>Private balcony</li>
            </ul>

            <p class="availability">✔ Free cancellation <br> ✔ Book now, pay later</p>
        </div>

        <div class="room-action">
            <p class="price">IDR 1.400.000</p>

            <?php if ($checkin && $checkout): ?>
                <a href="reservasi.php?id_kamar=2&checkin=<?= $checkin; ?>&checkout=<?= $checkout; ?>" class="btn">
                    Select
                </a>
            <?php else: ?>
                <button class="btn disabled" disabled>
                    Select
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- ROOM 3 -->
    <div class="room-card">
        <img src="assets/img/room3.jpg" alt="Suite Room">

        <div class="room-info">
            <h3>SUITE ROOM</h3>
            <ul>
                <li>LCD TV</li>
                <li>Queen size bed</li>
                <li>Free Wi-Fi</li>
                <li>City view</li>
            </ul>

            <p class="unavailable">Selected dates are unavailable</p>
        </div>

        <div class="room-action">
            <button class="disabled">Find available dates</button>
        </div>
    </div>

</section>

<!-- ================= FOOTER ================= -->
<footer class="footer">
    <p>&copy; <?= date('Y'); ?> Crystalkuta Hotel. All Rights Reserved.</p>
</footer>

</body>
</html>
