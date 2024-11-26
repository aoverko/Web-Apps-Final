<?php
//Database connection
require "DBdontpublish.php";
require "product_cookie.php";

// Fetch products from the database
$sql = "SELECT name, description, image_url, price FROM products";
$result = $conn->query($sql);

?>

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
        });
}
</script>

<body onload="loadNavbar()">
    <div id="navbar-area"></div> 

    <div class="container mt-5">
        <h1 class="text-center">Product Catalog</h1>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                // Loop through each product
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='col-md-4 mb-4'>";
                    echo "<div class='card h-100'>";
                    echo "<img src='" . htmlspecialchars($row['image_url']) . "' class='card-img-top' alt='" . htmlspecialchars($row['name']) . "'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($row['name']) . "</h5>";
                    echo "<p class='card-text'>" . htmlspecialchars($row['description']) . "</p>";
                    echo "<p class='card-text'><strong>$" . htmlspecialchars($row['price']) . "</strong></p>";
                    echo "<a href='#' class='btn btn-primary'>Add to Cart</a>";
                    echo "</div></div></div>";
                }
            } else {
                echo "<p class='text-center'>No products available.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
