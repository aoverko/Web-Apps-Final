<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lunatech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="lunatech.css">
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    function loadNavbar() {
        fetch('navbar.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('navbar-area').innerHTML = data;
            })

    }
</script>

<style>
    body {
        background-color: #0D0B1C;
    }
</style>

<?php
require "DBdontpublish.php";

?>

<body onload="loadNavbar()">
    <div id="navbar-area"></div>
    <div>
        <video autoplay muted loop playsinline preload="auto" class="vid-bg">
            <source src="SiteAssets/wave.mp4" type="video/mp4">

        </video>
    </div>
    <div class="landing-logo">
        <img src="Logos/lunatech_white.png">
    </div>
    <div class="feature-text">
        <h2>Explore our latest deals</h2>
    </div>
    <div class="featured">
        <div class="card" style="width: 18rem;">
            <a href="product_details.php">
                <img src="SiteAssets/Products/blue_led_headphones.png" class="card-img-top" alt="...">
                <div class="card-body">
                    <p class="card-text">Featuring adaptive noise canceling, memory foam ear cushions, and up to thirty hours of battery life.</p>
                </div>
            </a>
        </div>
        <div class="card" style="width: 18rem;">
            <a href="product_details.php">
                <img src="SiteAssets/Products/blue_glow_headphones.png" class="card-img-top" alt="...">
                <div class="card-body">
                    <p class="card-text">Featuring 7.1 surround sound, customizable RGB lights, noise-canceling mic and comfortable ear cups, this is the perfect headset for professional and hobbyist gamers.</p>
                </div>
            </a>
        </div>
        <div class="card" style="width: 18rem;">
            <a href="product_details.php">
                <img src="SiteAssets/Products/pink_headphones.png" class="card-img-top" alt="...">
                <div class="card-body">
                    <p class="card-text">Premium over-ear headphones with studio-quality sound, ideal for professionals and audiophiles.</p>
                </div>
            </a>
        </div>

    </div>
</body>

</html>