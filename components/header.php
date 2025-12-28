<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $page_title ?? 'Velaris Hotel' ?></title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root{
    --gold:#d4af37;
    --header-h:70px;
}

body{
    margin:0;
    font-family:'Inter',sans-serif;
}

/* ================= HEADER ================= */
.header{
    position:fixed;
    inset:0 0 auto 0;
    height:var(--header-h);
    z-index:1000;

    background:rgba(140,150,160,.35);
    backdrop-filter:blur(6px);
    -webkit-backdrop-filter:blur(6px);

    display:flex;
    align-items:center;
    transition:.3s;
}

.header.scrolled{
    background:#9fa4a8;
    backdrop-filter:none;
}

.header .wrap{
    width:100%;
    padding:0 80px;
    display:flex;
    align-items:center;
    justify-content:space-between;
}

/* BRAND */
.brand{
    font-family:'Cinzel',serif;
    color:var(--gold);
    font-size:1.45rem;
    letter-spacing:4px;
    font-weight:700;
    text-shadow:0 3px 6px rgba(0,0,0,.35);
}

/* NAV */
.nav-menu{
    display:flex;
    gap:54px;
    align-items:center;
}

.nav-menu a{
    font-size:.72rem;
    letter-spacing:2px;
    font-weight:500;
    color:#fff;
    text-decoration:none;
}

.nav-menu a:hover{
    color:var(--gold);
}

/* BOOKING */
.btn-book{
    border:1px solid #fff;
    padding:6px 18px;
    border-radius:20px;
    font-size:.7rem;
    letter-spacing:1px;
    color:#fff;
    text-decoration:none;
}

.btn-book:hover{
    background:var(--gold);
    border-color:var(--gold);
    color:#000;
}

@media(max-width:992px){
    .nav-menu{display:none}
    .header .wrap{padding:0 24px}
}
</style>
</head>

<body>

<header class="header" id="siteHeader">
    <div class="wrap">

        <div class="brand">VELARIS HOTEL</div>

        <nav class="nav-menu">
            <a href="index.php">HOME</a>
            <a href="rooms.php">ROOM</a>
            <a href="experience.php">EXPERIENCE</a>
            <a href="contact.php">CONTACT</a>
            <a href="booking.php" class="btn-book">BOOKING NOW</a>
        </nav>

    </div>
</header>

<script>
window.addEventListener('scroll',()=>{
    document.getElementById('siteHeader')
        .classList.toggle('scrolled',window.scrollY>40);
});
</script>
