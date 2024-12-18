<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
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
require "cart_cookie.php";
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
    $stock = query($conn, 'quantity', $cookie);
}

?>

<body onload="loadNavbar()">
    <div id="navbar-area"></div>

    <div class="product-box">
        <div class="product-img-box">
            <img id="product-img" src="<?php echo htmlspecialchars($img)?>">
        </div>
        <div class="product-details">
            <h1><?php echo htmlspecialchars($name)?></h1>
            <h4><?php echo "$" . htmlspecialchars($price)?></h4><br>
            <p class="pr-descr"><?php echo htmlspecialchars($stock) . " left in stock"?></p>
            <p><?php echo htmlspecialchars($description)?></p>
            <input type="number" id="quantity-input" min="0">
            <a href="#" id="cart-pr-dets" class="cart-data"
            data-product-name="<?php echo htmlspecialchars($name) ?>">Add to Cart</a>
           

        </div>
    </div>

</body>
</html>

<?php
$conn->close();
?>