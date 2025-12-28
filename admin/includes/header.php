<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $page_title ?? 'Velaris Hotel' ?></title>

<!-- GOOGLE FONTS-->
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
<style>
:root{
    --gold:#d4af37;
    --header-h:64px;
}

body{
    margin:0;
    font-family:'Inter',sans-serif;
}

/* HEADER */
.header{
    position:fixed;
    top:0; left:0; right:0;
    height:var(--header-h);
    z-index:1000;

    background:rgba(160,170,180,.28);
    backdrop-filter:blur(3px);
    -webkit-backdrop-filter:blur(3px);

    display:flex;
    align-items:center;
    transition:.3s;
}

.header.scrolled{
    background:rgba(120,130,140,.95); /* lebih gelap */
    backdrop-filter:blur(6px);
    -webkit-backdrop-filter:blur(6px);
}


/* layout */
.header .wrap{
    width:100%;
    padding:0 72px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

/*  VELARIS HOTEL biar kek yg di figma  */
.brand{
    font-family:'Cinzel',serif;
    color:var(--gold);
    font-size:1.5rem;
    letter-spacing:4px;
    font-weight:700; /* lebih bold */

    text-shadow:
        0 4px 6px rgba(0,0,0,0.25); /* shadow halus */
}

/* MENU */
.nav-menu{
    display:flex;
    gap:56px;
}

.nav-menu a{
    font-family:'Inter',sans-serif;
    font-size:.78rem;
    letter-spacing:2px;
    font-weight:500;
    color:#fff;
    text-decoration:none;
}

.nav-menu a:hover{
    color:var(--gold);
}

/* USER BUTTON – FIX DROPDOWN */
.user-btn{
    font-family:'Inter',sans-serif;
    font-size:.75rem;
    letter-spacing:1px;
    border:1px solid #fff;
    padding:6px 18px;
    border-radius:20px;
    color:#fff;
    background:transparent;
    cursor:pointer;
}

/* JANGAN ganggu state bootstrap */
.user-btn.dropdown-toggle{
    white-space:nowrap;
}

/* hover */
.user-btn:hover{
    background:var(--gold);
    border-color:var(--gold);
    color:#000;
}

/* panah dropdown */
.user-btn.dropdown-toggle::after{
    margin-left:8px;
    vertical-align:middle;
    color:#fff;
}

/* SAAT DROPDOWN AKTIF */
.dropdown.show .user-btn{
    background:var(--gold);
    border-color:var(--gold);
    color:#000;
}

/* === DROPDOWN LOGOUT: UKURAN PAS === */

.dropdown-menu{
    min-width:auto;
    padding:8px;                 /* NAIKIN dikit */
    border-radius:22px;
    border:none;
    box-shadow:0 14px 28px rgba(0,0,0,.18);
    background:#fff;
}

/* ITEM LOGOUT */
.dropdown-menu .dropdown-item{
    font-family:'Inter',sans-serif;
    font-size:.78rem;            /* DI TENGAH (PAS) */
    padding:8px 22px;            /* NAIKIN dikit */
    border-radius:22px;
    text-align:center;
    color:#000;
    line-height:1.2;
    white-space:nowrap;
}

/* HOVER */
.dropdown-menu .dropdown-item:hover{
    background:var(--gold);
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
            <a href="/phpdiana/VELARIS_HOTEL/admin/index.php">HOME</a>
            <a href="/phpdiana/VELARIS_HOTEL/admin/kamar/index.php#rooms">ROOM</a>
            <a href="/phpdiana/VELARIS_HOTEL/admin/experiences/index.php#experience">EXPERIENCES</a>
            <a href="/phpdiana/VELARIS_HOTEL/admin/blog/index.php#gallery">BLOG</a>
        </nav>


        <!-- USER DROPDOWN -->
        <div class="dropdown">
            <button class="user-btn dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                    data-bs-display="static"
                    aria-expanded="false">
                <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Admin') ?>
            </button>


            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item"
                       href="/phpdiana/VELARIS_HOTEL/admin/logout.php">
                        Logout
                    </a>

                    <a class="dropdown-item"
                       href="/phpdiana/VELARIS_HOTEL/admin/pembatalan/index.php">
                        Cancellation Requests
                    </a>

                     <a class="dropdown-item"
                       href="/phpdiana/VELARIS_HOTEL/admin/log/index.php">
                        Log Activity
                    </a>

                    <a class="dropdown-item"
                       href="/phpdiana/VELARIS_HOTEL/admin/reservasi/index.php">
                        Reservations
                    </a>
                </li>
            </ul>
        </div>

    </div>
</header>
<script>
const header = document.getElementById('siteHeader');

window.addEventListener('scroll', () => {
    if (window.scrollY > 10) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});
</script>
</body>
