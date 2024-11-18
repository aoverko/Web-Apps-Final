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

    //set product cookie based on click for each link
    function setProductCookie(identifier) {
        document.cookie = "view-product=" + identifier + "; path=/; max-age=" + 60 * 60 * 24; 
    }

    document.addEventListener('DOMContentLoaded', function() {
        const productLinks = document.querySelectorAll('.product-card');

        productLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                const productId = this.getAttribute('data-product-id');
                setProductCookie(productId);
            });
        });
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

//TRIED to make a function I could repeat
function query($conn, $column, $name)
{
    $query = "SELECT $column from Products WHERE name = '$name'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row[$column];
        }
    }
}
$imgURL1 = query($conn, 'image_url', 'Lunatech SonicWave Pro');
$name1 = query($conn, 'name', 'Lunatech SonicWave Pro');

$imgURL2 = query($conn, 'image_url', 'Lunatech GamePro 360');
$name2 = query($conn, 'name', 'Lunatech GamePro 360');

$imgURL3 = query($conn, 'image_url', 'Lunatech CrystalX Studio');
$name3 = query($conn, 'name', 'Lunatech CrystalX Studio');

?>

<body onload="loadNavbar()" class="landing">
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
        <div class="product-card" style="width: 18rem;" data-product-id="<?php echo $name1 ?>">
            <a href="product_details.php">
                <img src="<?php echo $imgURL1 ?>" class="card-img" alt="...">
                <div>
                    <p class="card-text"><?php echo $name1 ?></p>
                </div>
            </a>
        </div>
        <div class="product-card" style="width: 18rem;" data-product-id="<?php echo $name2 ?>">
            <a href="product_details.php">
                <img src="SiteAssets/Products/blue_glow_headphones.png" class="card-img" alt="...">
                <div>
                    <p class="card-text"><?php echo $name2 ?></p>
                </div>
            </a>
        </div>
        <div class="product-card" style="width: 18rem;" data-product-id="<?php echo $name3 ?>">
            <a href="product_details.php">
                <img src="SiteAssets/Products/pink_headphones.png" class="card-img" alt="...">
                <div>
                    <p class="card-text"><?php echo $name3 ?></p>
                </div>
            </a>
        </div>

    </div>
</body>

</html>