<?php
// Database connection
$servername = "54.165.204.136";
$username = "group1";
$password = "tg5z4b31iM]";
$dbname = "group1";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

        <!-- Basic Search Form -->
        <form action="catalog.php" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="search_name" class="form-control" placeholder="Search by product name" value="<?php echo htmlspecialchars($searchName); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" name="basic_search" class="btn btn-primary w-100">Basic Search</button>
                </div>
            </div>
        </form>

        <!-- Advanced Search Form -->
        <form action="catalog.php" method="POST" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search_name" class="form-control" placeholder="Search by name" value="<?php echo htmlspecialchars($searchName); ?>">
                </div>
                <div class="col-md-2">
                    <input type="number" name="min_price" class="form-control" placeholder="Min Price" value="<?php echo htmlspecialchars($minPrice); ?>">
                </div>
                <div class="col-md-2">
                    <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="<?php echo htmlspecialchars($maxPrice); ?>">
                </div>
                <div class="col-md-3">
                    <select name="headphone_type" class="form-control">
                        <option value="">Select Type</option>
                        <option value="over-ear" <?php echo ($headphoneType == "over-ear") ? "selected" : ""; ?>>Over-Ear</option>
                        <option value="on-ear" <?php echo ($headphoneType == "on-ear") ? "selected" : ""; ?>>On-Ear</option>
                        <option value="in-ear" <?php echo ($headphoneType == "in-ear") ? "selected" : ""; ?>>In-Ear</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Advanced Search</button>
                </div>
            </div>
        </form>

        <!-- Display Products -->
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
                    echo "<a href='#' class='btn btn-primary'>Reserve/Purchase</a>";
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
