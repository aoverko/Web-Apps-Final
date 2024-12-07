<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lunatech</title>
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

    //Fade In Effect
    document.addEventListener('DOMContentLoaded', () => {
        const images = document.querySelectorAll('.fade-in');

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible'); // Add the 'visible' class when the image is in the viewport
                    observer.unobserve(entry.target); // Stop observing once it's visible
                }
            });
        }, {
            threshold: 0.1 // Trigger when 10% of the image is visible
        });

        images.forEach(image => observer.observe(image));
    });
</script>


<style>
    body {
        background-color: #0D0B1C;
    }
</style>

<?php
//get content from database 
require "DBdontpublish.php";
//set cookie to track last product clicked
require "product_cookie.php";

$imgURL1 = query($conn, 'image_url', 'Lunatech SonicWave Pro');
$name1 = query($conn, 'name', 'Lunatech SonicWave Pro');

$imgURL2 = query($conn, 'image_url', 'Lunatech GamePro 360');
$name2 = query($conn, 'name', 'Lunatech GamePro 360');

$imgURL3 = query($conn, 'image_url', 'Lunatech EchoPulse Lite');
$name3 = query($conn, 'name', 'Lunatech EchoPulse Lite');

$imgURL4 = query($conn, 'image_url', 'Lunatech EchoFit Sport');
$name4 = query($conn, 'name', 'Lunatech EchoFit Sport');

?>

<body onload="loadNavbar()" class="landing">
    <div id="navbar-area"></div>

    <!-- Landing Page -->
    <div>
        <video autoplay muted loop playsinline preload="auto" class="vid-bg">
            <source src="SiteAssets/wave.mp4" type="video/mp4">

        </video>
    </div>
    <div class="landing-logo fade-in">
        <img src="Logos/lunatech_white.png">
    </div>


    <!-- Bestseller Cards-->
    <div class="ft-deals">
        <div class="feature-text">
            <h2>Explore our bestsellers</h2>
        </div>
        <div class="featured">
            <div class="product-card cookie-data" style="width: 18rem;" data-product-id="<?php echo $name1 ?>">
                <a href="product_details.php">
                    <img src="<?php echo $imgURL1 ?>" class="ft-img" alt="...">
                    <div>
                        <p class="ft-text"><?php echo $name1 ?></p>
                    </div>
                </a>
            </div>
            <div class="product-card cookie-data" style="width: 18rem;" data-product-id="<?php echo $name3 ?>">
                <a href="product_details.php">
                    <img src="<?php echo $imgURL3 ?>" class="ft-img" alt="...">
                    <div>
                        <p class="ft-text"><?php echo $name3 ?></p>
                    </div>
                </a>
            </div>
            <div class="product-card cookie-data" style="width: 18rem;" data-product-id="<?php echo $name2 ?>">
                <a href="product_details.php">
                    <img src="<?php echo $imgURL2 ?>" class="ft-img" alt="...">
                    <div>
                        <p class="ft-text"><?php echo $name2 ?></p>
                    </div>
                </a>
            </div>
            <div class="product-card cookie-data" style="width: 18rem;" data-product-id="<?php echo $name4 ?>">
                <a href="product_details.php">
                    <img src="<?php echo $imgURL4 ?>" class="ft-img" alt="...">
                    <div>
                        <p class="ft-text"><?php echo $name4 ?></p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Image Banner -->
        <div class="img-banner-1">
            <h1 id="img1-text1" class="fade-in">A New Way to Listen</h1>
            <img src="SiteAssets/galaxy_earbud_banner.png" style="width: 100%">
            <h3 id="img1-text2" class="fade-in">Explore our Catalog</h3>
            <a id="img1-btn" class="fade-in" href="catalog.php">View Catalog</a>
        </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>