<?php
session_start();
require "DBdontpublish.php";
require "product_cookie.php";


// ---- Handle Product Deletion ----
if (isset($_POST['delete_product'])) {
    $id = $_POST['product_id'];

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Product deleted successfully.";
    } else {
        echo "Error deleting product: " . $stmt->error;
    }
    $stmt->close();
}

// Handle Basic Search
$searchName = "";
if (isset($_GET['basic_search'])) {
    $searchName = $_GET['search_name'] ?? '';
}

// ---- Fetch Products ----
$result = null;
if (!empty($searchName)) {
    $result = $conn->query("SELECT * FROM products WHERE name LIKE '%" . $conn->real_escape_string($searchName) . "%'");
} else {
    $result = $conn->query("SELECT * FROM products");
}

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Product Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="lunatech.css">
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    function loadSidebar() {
        fetch('sidebar.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('sidebar-area').innerHTML = data;
            })
    }
</script>

<body onload="loadSidebar()">
    <div id="sidebar-area"></div>

    <div class="content">
        <div class="add-product">
            <div class="back-header">
                <a href="employee_dashboard.php" class="back-link">
                    <img src="SiteAssets/back_arrow.png" class="back-icon">
                </a>
                <div class="back-text">
                    <span>
                        <h4 class="back">Back to Dashboard<h4>
                    </span>
                    <h1 class="heading">Product Dashboard</h1>
                </div>
            </div>



            <!-- Display Current Products -->
            <div class="row">
                <div class="col">
                    <div class="table-responsive table-border">
                        <table class="my-table">
                            <thead>
                                <tr>
                                    <th>
                                        <h3>Inventory</h3>
                                    </th>

                                    <th></th>
                                    <th></th>

                                    <th>
                                        <form action="product_dashboard.php" method="GET" class="inv-search-cont">
                                            <span class="inv-search">
                                                <input type="text" name="search_name" class="inv-search-input"
                                                    placeholder="Search Product" value="<?php echo htmlspecialchars($searchName); ?>">
                                                <button type="submit" name="basic_search" class="inv-search-btn">Search</button></span>
                                        </form>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()) : ?>
                                        <tr>
                                            <div class="product cookie-data" data-product-id="<?php echo htmlspecialchars($row['name']) ?>">
                                                <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>"
                                                        alt="<?php echo htmlspecialchars($row['name']); ?>"
                                                    id="pr-img"></td>
                                                <td>
                                                    <h5 id="pr-name"><?php echo htmlspecialchars($row['name']); ?></h5>
                                                    <p id="pr-price">$<?php echo htmlspecialchars(number_format($row['price'], 2)); ?></p>
                                                </td>
                                                <td></td>
                                                <td><span class="inv-btns">
                                                        <button class="inv-btn" id="manage-btn"><a href="manage_product.php"
                                                                class="inv-btn-link cookie-data" data-product-id="<?php echo htmlspecialchars($row['name']) ?>">Modify Product</a></button>

                                                        <form action="product_dashboard.php" method="POST" style="display:inline;">
                                                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                                            <button class="inv-btn" id="delete-btn" type="submit" name="delete_product">
                                                                <img src="SiteAssets/trash.png" class="img-delete"></button>
                                                        </form>
                                                    </span>
                                                </td>

                                            </div>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p>No products available.</p>
                                <?php endif; ?>
                            </tbody>
                        </table>
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