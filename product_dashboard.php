<?php
// Database connection
require "DBdontpublish.php";
//track last product clicked
require "product_cookie.php";

// ---- Handle Product Addition ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $image_url = $_POST['image_url'];
        $price = $_POST['price'];

        $stmt = $conn->prepare("INSERT INTO products (name, description, image_url, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $name, $description, $image_url, $price);

        if ($stmt->execute()) {
            echo "Product added successfully.";
        } else {
            echo "Error adding product: " . $stmt->error;
        }
        $stmt->close();
    }

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
    <title>Employee Product Dashboard</title>
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
        <h1>Employee Product Dashboard</h1>

        <!-- Add New Product Form -->
        <h2>Add New Product</h2>
        <form action="product_dashboard.php" method="POST">
            <input type="text" name="name" placeholder="Product Name" required>
            <input type="text" name="description" placeholder="Description" required>
            <input type="text" name="image_url" placeholder="Image URL" required>
            <input type="number" name="price" placeholder="Price" required step="0.01">
            <button type="submit" name="add_product">Add Product</button>
        </form>
    </div>
    <!-- Display Current Products -->
    <h2>Current Products</h2>
    <div class="catalog">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="product cookie-data" data-product-id="<?php echo htmlspecialchars($row['name']) ?>">
                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>"
                        alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <p>$<?php echo htmlspecialchars(number_format($row['price'], 2)); ?></p>
                    <a href="manage_product.php" class="btn">Manage Product</a>

                    <!-- Modify and delete buttons -->
                    <form action="product_dashboard.php" method="POST" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_product">Delete</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products available.</p>
        <?php endif; ?>
    </div>
</body>

</html>

<?php
$conn->close();
?>