<?php
// Database connection
require "DBdontpublish.php";
//track last product clicked
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


// ---- Fetch Products ----
$result = $conn->query("SELECT * FROM products");
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
    <div class="add-product">

        <div>
            <h4>Back to Dashboard</h4>
            <a href="employee_dashboard.php">
                <img src="SiteAssets/back_arrow.png" style="max-width:3rem">
            </a>
        </div>

        <h1>Product Dashboard</h1>

        <!-- Display Current Products -->
        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    <h3>Inventory</h3>
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
                                                    style="max-width: 5rem"></td>
                                            <td>
                                                <h5><?php echo htmlspecialchars($row['name']); ?></h5>
                                            </td>
                                            <td>
                                                <p><?php echo htmlspecialchars($row['description']); ?></p>
                                            </td>
                                            <td>
                                                <p>$<?php echo htmlspecialchars(number_format($row['price'], 2)); ?></p>
                                            </td>
                                            <td><a href="manage_product.php" class="btn cookie-data"
                                                    data-product-id="<?php echo htmlspecialchars($row['name']) ?>">Manage Product</a></td>
                                            <form action="product_dashboard.php" method="POST" style="display:inline;">
                                                <td> <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" name="delete_product">Delete</button>
                                            </form>
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
</body>

</html>

<?php
$conn->close();
?>