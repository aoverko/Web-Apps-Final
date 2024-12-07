<?php
// Database connection
require "DBdontpublish.php";
require "cart_cookie.php";
require "product_cookie.php";
session_start();

// Initialize search variables
$searchName = "";
$minPrice = "";
$maxPrice = "";
$headphoneType = "";

// Handle Basic Search
if (isset($_GET['basic_search'])) {
    $searchName = $_GET['search_name'] ?? '';
}

// Handle Advanced Search
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchName = $_POST['search_name'] ?? '';
    $minPrice = $_POST['min_price'] ?? '';
    $maxPrice = $_POST['max_price'] ?? '';
    $headphoneType = $_POST['headphone_type'] ?? '';
}

// Construct the SQL query with optional filters
$sql = "SELECT name, description, image_url, price, headphone_type FROM products WHERE 1=1";

// Apply filters to the SQL query based on user input
if (!empty($searchName)) {
    $sql .= " AND name LIKE '%" . $conn->real_escape_string($searchName) . "%'";
}
if (!empty($minPrice)) {
    $sql .= " AND price >= " . floatval($minPrice);
}
if (!empty($maxPrice)) {
    $sql .= " AND price <= " . floatval($maxPrice);
}
if (!empty($headphoneType)) {
    $sql .= " AND headphone_type = '" . $conn->real_escape_string($headphoneType) . "'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog</title>
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

    <div class="container-fluid mt-5 catalog">
        <!-- Top Row -->
        <div class="row">
            <!-- Sidebar for Advanced Search -->
            <div id="adv-search-sidebar">
                <form action="catalog.php" method="POST" class="p-3">
                    <h5 class="text-center mb-3">Advanced Search</h5>
                    <div class="mb-3">
                        <input type="text" name="search_name" class="form-control" placeholder="Search by name" value="<?php echo htmlspecialchars($searchName); ?>">
                    </div>
                    <div class="mb-3">
                        <input type="number" name="min_price" class="form-control" placeholder="Min Price" value="<?php echo htmlspecialchars($minPrice); ?>">
                    </div>
                    <div class="mb-3">
                        <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="<?php echo htmlspecialchars($maxPrice); ?>">
                    </div>
                    <div class="mb-3">
                        <select name="headphone_type" class="form-control">
                            <option value="">Select Type</option>
                            <option value="over-ear" <?php echo ($headphoneType == "over-ear") ? "selected" : ""; ?>>Over-Ear</option>
                            <option value="in-ear" <?php echo ($headphoneType == "in-ear") ? "selected" : ""; ?>>In-Ear</option>
                        </select>
                    </div>
                    <button type="submit" class="catalog-adv-search w-100">Search</button>
                </form>
            </div>


            <!-- Main Content -->
            <div id="catalog-content" class="container-fluid mt-5">
                <div class="col-md-9">
                    <!-- Basic Search -->
                    <form action="catalog.php" method="GET" class="d-flex justify-content-between mb-4">
                        <h3 class="catalog-head">Product Catalog</h3>
                        <div class="d-flex basic-search">
                            <input type="text" name="search_name" class="form-control" placeholder="Search by product name" value="<?php echo htmlspecialchars($searchName); ?>">
                            <button type="submit" name="basic_search">Search</button>
                        </div>
                        
                    </form>

                    <!-- Product Catalog -->
                    <div class="row">
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <div class="col-md-4 mb-4">
                                    <div class="card catalog-card h-100">
                                        <a href="product_details.php" class="cookie-data" data-product-id="<?php echo htmlspecialchars($row['name']) ?>">
                                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="card-img-top catalog-img"
                                                alt="<?php echo htmlspecialchars($row['name']); ?>">
                                            <div class="catalog-card-content">
                                                <h5 class="catalog-card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                                                <p class="catalog-card-text">$<?php echo htmlspecialchars($row['price']); ?></p>
                                                
                                                <a href="#" class="cart-data catalog-add-2-cart" id="quantity-input"
                                                    data-product-name="<?php echo htmlspecialchars($row['name']); ?>">Add to Cart</a>
                                            </div>
                                    </div>
                                </div></a>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-center">No products available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<?php
$conn->close();
?>