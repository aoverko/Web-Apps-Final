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
//require "DBdontpublish.php";

?>

<body onload="loadNavbar()">
    <div id="navbar-area"></div>

    <div class="product-box">
        <div class="product-img-box">
            <img id="product-img"
                src="https://imgs.search.brave.com/2BmpCuwRgXbMtR4KvngKfbDyTOEVadYrtvRR-SCjhDc/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jZG4u/dmVjdG9yc3RvY2su/Y29tL2kvcHJldmll/dy0xeC80OC8wNi9p/bWFnZS1wcmV2aWV3/LWljb24tcGljdHVy/ZS1wbGFjZWhvbGRl/ci12ZWN0b3ItMzEy/ODQ4MDYuanBn">
        </div>
        <div class="product-details">
            <h2>Product</h2>
            <h3>$___</h3><br>
            <p>Description</p>
            <p>Quantity</p>
            <input type="number" min="0"><br></br>
            <button id="cart-pr-dets">Add to Cart</button>
            <button id="buy-pr-dets">Buy Now</button>

        </div>
    </div>

</body>

</html>