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

<?php
require "DBdontpublish.php";
require "product_cookie.php";
$img;
$name;
$price;
$description;
$stock;


if (isset($_COOKIE['view-product'])) {
    $cookie = $_COOKIE['view-product'];

    $img = query($conn, 'image_url', $cookie);
    $name = query($conn, 'name', $cookie);
    $price = query($conn, 'price', $cookie);
    $description = query($conn, 'description', $cookie);
    $stock = query($conn, 'stock', $cookie);
}

?>

<body onload="loadNavbar()">
    <div id="navbar-area"></div>

    <div class="product-box">
        <div class="product-img-box">
            <img id="product-img" src="<?php echo $img?>">
        </div>
        <div class="product-details">
            <h2><?php echo $name?></h2>
            <h3><?php echo "$" . $price?></h3><br>
            <p><?php echo $stock . " left in stock"?></p>
            <p><?php echo $description?></p>
            <input type="number" min="0"><br></br>
            <button id="cart-pr-dets">Add to Cart</button>
            <button id="buy-pr-dets">Buy Now</button>

        </div>
    </div>

</body>

</html>