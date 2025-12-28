<?php
require_once '../config/database.php';
require_once '../config/functions.php';

$page_title = 'Home';
require_once 'includes/header.php';
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- hero kek di figma-->
<section style="
    height:100vh;
    position:relative;
">
    <img src="/phpdiana/VELARIS_HOTEL/uploads/velaris_hotel.png"
         style="
            width:100%;
            height:100%;
            object-fit:cover;
         ">
</section>


<!-- ABOUT -->
<style>
.about-wrap{
    position:relative;
    padding:80px 40px;
}

.about-box{
    max-width:1100px;
    margin:auto;
    display:grid;
    grid-template-columns:1fr 1px 1fr;
    gap:60px;
    align-items:flex-start;
}

/* garis tengah */
.about-divider{
    width:1px;
    background:#cfcfcf;
    height:100%;
}

/* aksen emas */
.about-wrap::before,
.about-wrap::after{
    content:'';
    position:absolute;
    width:40px;
    height:40px;
    border:2px solid #d4af37;
}
.about-wrap::before{
    top:40px; left:40px;
    border-right:none;
    border-bottom:none;
}
.about-wrap::after{
    bottom:40px; right:40px;
    border-left:none;
    border-top:none;
}

/* LEFT */
.about-left h4{
    font-family:'Cinzel',serif;
    color:#d4af37;
    letter-spacing:2px;
    font-size:1.1rem;
    margin-bottom:18px;
}
.about-left p{
    max-width:420px;
    line-height:1.7;
    font-size:.95rem;
    margin-bottom:22px;
}
.about-left .btn{
    border-radius:0;
    padding:8px 22px;
    letter-spacing:1px;
}

/* RIGHT */
.about-right h5{
    font-family:'Cinzel',serif;
    color:#d4af37;
    letter-spacing:2px;
    margin-bottom:24px;
}
.about-info{
    list-style:none;
    padding:0;
}
.about-info li{
    display:flex;
    gap:14px;
    margin-bottom:16px;
    font-size:.9rem;
}
.about-info i{
    color:#d4af37;
    font-size:1.1rem;
}
</style>

<section class="about-wrap">

    <div class="about-box">

        <!-- LEFT -->
        <div class="about-left">
            <h4>VELARIS HOTEL – INDONESIA</h4>
            <p>
                We are back with new management, more facilities,
                completed with consistent warm hospitality.
                A 4-star hotel will provide you with the ultimate
                comfort and relaxation during your stay in Bali.
            </p>

            <a href="/phpdiana/VELARIS_HOTEL/admin/blog/index.php" class="btn btn-outline-dark">
                HOTEL BLOG
            </a>
        </div>

        <!-- DIVIDER -->
        <div class="about-divider"></div>

        <!-- RIGHT -->
        <div class="about-right">
            <h5>VELARIS<br>HOTEL</h5>

            <ul class="about-info">
                <li>
                    <i class="bi bi-envelope"></i>
                    booking@velarishotel.com
                </li>
                <li>
                    <i class="bi bi-telephone"></i>
                    +62 361 846 4719
                </li>
                <li>
                    <i class="bi bi-geo-alt"></i>
                    Jl. Slamet Riyadi No.233, Surakarta
                </li>
            </ul>
        </div>

    </div>

</section>


<!-- ROOMS -->
<style>
.room-hero{
    position:relative;
    height:420px;
    border-radius:28px;
    overflow:hidden;
    margin-bottom:48px;
}
.room-hero img{
    width:100%;
    height:100%;
    object-fit:cover;
}
.room-overlay{
    position:absolute;
    inset:0;
    background:rgba(0,0,0,.35);
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;
    color:#fff;
    padding:40px;
}
.room-overlay h2{
    font-family:'Playfair Display',serif;
    font-size:2.6rem;
    margin-bottom:10px;
}
.room-overlay p{
    max-width:520px;
    font-size:.95rem;
    line-height:1.6;
    opacity:.9;
    margin-bottom:22px;
}
.room-overlay a{
    padding:10px 30px;
    border:1px solid #fff;
    border-radius:30px;
    color:#fff;
    text-decoration:none;
    letter-spacing:1px;
}
.room-overlay a:hover{
    background:#d4af37;
    border-color:#d4af37;
    color:#000;
}
</style>

<section class="container py-5">

    <div class="room-hero">
        <img src="/phpdiana/VELARIS_HOTEL/uploads/kamar/RWKA1W7pZuAgCYb9X9GD.png" alt="Rooms">
        <div class="room-overlay">
            <h2>Rooms</h2>
            <p>
                Where every detail is crafted for comfort,
                offering a serene blend of culture, luxury,
                and heartfelt hospitality.
            </p>
            <a href="reservasi/index.php">DISCOVER MORE</a>
        </div>
    </div>

</section>

<!-- ROOMS CARD -->
<style>
.room-card img{
    transition:.4s ease;
}
.room-card:hover img{
    transform:scale(1.05);
}
</style>

<section id="rooms-card" class="container pb-5">
    <div class="row g-4 justify-content-center">

    <?php
    $rooms = fetch_all("SELECT * FROM kamar LIMIT 3");
    foreach ($rooms as $r):
    ?>
        <div class="col-md-4">
            <div class="card room-card border-0 shadow-sm rounded-4 overflow-hidden">

                <img
                    src="/phpdiana/VELARIS_HOTEL/uploads/kamar/<?= rawurlencode($r['foto_kamar']) ?>"
                    alt="<?= htmlspecialchars($r['nama_kamar']) ?>"
                    style="height:260px;object-fit:cover"
                >


                <div class="card-body text-center">
                    <h5 class="mb-1">
                        <?= htmlspecialchars($r['nama_kamar']) ?>
                    </h5>
                    <p class="text-muted mb-0">
                        <?= format_rupiah($r['harga']) ?>/night
                    </p>
                </div>

            </div>
        </div>
    <?php endforeach; ?>

    </div>
</section>

<!-- RESTAURANTS (FIGMA STYLE) -->
<style>
.rest-section{
    padding:80px 0;
}

.rest-wrap{
    max-width:1100px;
    margin:auto;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:80px;
    align-items:center;
}

/* LEFT IMAGES */
.rest-images{
    position:relative;
    display:flex;
    flex-direction:column;
    gap:24px;
}

.rest-images img{
    width:260px;
    height:180px;
    object-fit:cover;
    border-radius:18px;
}

/* offset images */
.rest-images img:nth-child(1){
    align-self:flex-start;
}
.rest-images img:nth-child(2){
    align-self:center;
}
.rest-images img:nth-child(3){
    align-self:flex-end;
}

/* RIGHT CONTENT */
.rest-content h2{
    font-family:'Playfair Display',serif;
    font-size:2.2rem;
    color:#1f3c88;
    margin-bottom:22px;
}

.rest-content p{
    font-size:.95rem;
    line-height:1.7;
    max-width:420px;
    margin-bottom:18px;
}

.rest-content a{
    text-decoration:none;
    font-weight:500;
    color:#1f3c88;
    letter-spacing:.5px;
}

.rest-content a:hover{
    text-decoration:underline;
}

/* responsive */
@media(max-width:992px){
    .rest-wrap{
        grid-template-columns:1fr;
        gap:40px;
    }
    .rest-images{
        align-items:center;
    }
    .rest-images img{
        width:100%;
        max-width:340px;
    }
}
</style>

<section class="rest-section">

    <div class="rest-wrap">

        <!-- LEFT IMAGES -->
        <div class="rest-images">
            <img src="/phpdiana/VELARIS_HOTEL/uploads/restaurant1.jpg" alt="Restaurant">
            <img src="/phpdiana/VELARIS_HOTEL/uploads/restaurant2.jpg" alt="Restaurant">
            <img src="/phpdiana/VELARIS_HOTEL/uploads/restaurant3.jpg" alt="Restaurant">
        </div>

        <!-- RIGHT CONTENT -->
        <div class="rest-content">
            <h2>Restaurants</h2>
            <p>
                Our hotel offers a diverse and delectable dining
                experience at our on-site restaurant. Our restaurant
                features a menu of delicious dishes made with fresh
                and locally sourced ingredients, as well as international
                cuisine to cater to a variety of tastes.
            </p>
            <p>
                We take pride in our exceptional service and strive
                to provide a welcoming atmosphere that enhances
                your dining experience.
            </p>

           
        </div>

    </div>

</section>



<!-- EXPERIENCE -->
<section id="experience" class="container py-5">

    <h2 class="text-center mb-5" style="
        font-family:'Cinzel',serif;
        font-weight:600;
        letter-spacing:1px;
    ">
        Experience
    </h2>

    <?php
    $exp = fetch_all("SELECT * FROM experiences LIMIT 1");
    foreach ($exp as $e):
    ?>

    <div class="row align-items-center position-relative">

        <!-- LEFT CONTENT -->
        <div class="col-md-5">

            <!-- Image -->
            <div class="mb-3">
                <img src="/phpdiana/VELARIS_HOTEL/uploads/experiences/TUB53zGe9PkiisZx72C1.png"
                     class="img-fluid"
                     style="
                        border-radius:4px;
                        clip-path: polygon(0 0, 95% 0, 100% 15%, 100% 100%, 0 100%);
                     ">
            </div>

            <!-- Text -->
            <p style="font-size:0.9rem; line-height:1.7;">
                <?= nl2br($e['deskripsi']); ?>
            </p>

            <a href="/phpdiana/VELARIS_HOTEL/admin/experiences/index.php#experience"
               style="
                    font-size:0.85rem;
                    text-decoration:none;
                    color:#0d3b8e;
                    font-weight:500;
               ">
                View Details →
            </a>
        </div>

        <!-- RIGHT IMAGE -->
        <div class="col-md-7 text-end">
            <img src="/phpdiana/VELARIS_HOTEL/uploads/experiences/lcJGOwUpfyHxvTfTQ5Rz.jpg"
                 class="img-fluid"
                 style="
                    max-width:90%;
                    border-radius:20px;
                 ">
        </div>

        <!-- DECORATION LINE -->
        <div style="
            position:absolute;
            right:0;
            bottom:-20px;
            width:40px;
            height:40px;
            border-right:2px solid #000;
            border-bottom:2px solid #000;
        "></div>

    </div>

    <?php endforeach; ?>
</section>



<?php require_once 'includes/footer.php'; ?>
