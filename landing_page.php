<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lunatech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="lunatech.css">
</head>

<script> 
function loadNavbar() {
            fetch('navbar.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('navbar-area').innerHTML = data;
                })

        }
</script>

<body onload="loadNavbar()">
<div id="navbar-area"></div>
<!--<nav class="navbar">
  <div class="nav-box">
        <div class="nav-left">
            <a href="landing_page.php">
                <img src="Logos/lunatech_icon_white.png" class="nav-img">
            </a>
        </div>

        <div class="nav-right">
            <a class="nav-links" href="catalog.php">Catalog</a>
            <a href="cart.php">
                <img src="cart.png">
            </a>
            <a href="">
                <img src="profile.png">
            </a>
        </div>
  </div>
</nav> --> 

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>