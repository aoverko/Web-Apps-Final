<?php
// Database connection
$servername = "54.165.204.136";
$username = "group1";
$password = "tg5z4b31iM]";
$dbname = "group1";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Product Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
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

    <!-- Display Current Products -->
    <h2>Current Products</h2>
    <div class="catalog">
        <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="product">
                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <p>$<?php echo htmlspecialchars(number_format($row['price'], 2)); ?></p>

                <!-- Modify and delete buttons -->
                <form action="product_dashboard.php" method="POST" style="display:inline;">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="delete_product">Delete</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
