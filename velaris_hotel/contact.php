<?php
session_start();
require_once "config/database.php";
require_once "config/functions.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Contact | Velaris Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/contact.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<header class="header">
    <div class="logo">VELARIS HOTEL</div>
    <nav>
        <a href="index.php">Home</a>
        <a href="rooms.php">Room</a>
        <a href="experience.php">Experience</a>
        <a href="contact.php" class="active">Contact</a>
        <a href="booking.php" class="btn-booking">Booking Now</a>
    </nav>
</header>

<!-- ================= HERO ================= -->
<section class="hero">
    <img src="assets/img/contact-hero.jpg" alt="Contact Velaris Hotel">
</section>

<!-- ================= CONTACT ================= -->
<section class="contact-section">
    <h1>CONTACT</h1>

    <div class="contact-container">

        <!-- LEFT -->
        <div class="contact-info">
            <h3>GET IN TOUCH</h3>

            <p><strong>CRYSTALKUTA HOTEL – BALI</strong></p>
            <p>
                Jl. Bypass I Gusti Ngurah Rai No. 999,  
                Kuta 80361 – Bali, Indonesia
            </p>

            <p>
                +62 361 846 4618 (hunting)<br>
                +62 361 846 4718 (fax)<br>
                +62 831 3714 8108 (WhatsApp)
            </p>

            <!-- MAP -->
            <div class="map">
                <iframe 
                    src="https://www.google.com/maps?q=Kuta%20Bali&output=embed"
                    allowfullscreen=""
                    loading="lazy">
                </iframe>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="contact-form">
            <form action="#" method="post">
                <label>NAME</label>
                <input type="text" name="name" placeholder="your name">

                <label>E - MAIL</label>
                <input type="email" name="email" placeholder="your e-mail address">

                <label>REASON FOR CONTACT</label>
                <input type="text" name="reason" placeholder="e.g. reservation, complaint, partnership">

                <label>MESSAGE</label>
                <textarea name="message" placeholder="write your message here..."></textarea>

                <button type="submit">Send Message</button>
            </form>
        </div>

    </div>
</section>

<!-- ================= FOOTER ================= -->
<footer class="footer">
    <div class="footer-left">
        <h3>CRYSTALKUTA</h3>
        <p>
            Jl. Bypass I Gusti Ngurah Rai No. 999<br>
            Kuta 80361 – Bali, Indonesia
        </p>
    </div>

    <div class="footer-right">
        <p>FIND US ON</p>
        <div class="social">
            <a href="#">IG</a>
            <a href="#">WA</a>
            <a href="#">FB</a>
        </div>
    </div>
</footer>

</body>
</html>
