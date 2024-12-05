<?php
require "DBdontpublish.php";
require "product_cookie.php";
session_start();

//get products from DB
$result = $conn->query("SELECT * FROM products");
if (!$result) {
    die("Query failed: " . $conn->error);
}

//get product details based on the cookie
if (isset($_COOKIE['view-product'])) {
    $cookie = $_COOKIE['view-product'];

    $img = query($conn, 'image_url', $cookie);
    $db_name = query($conn, 'name', $cookie);
    $db_price = query($conn, 'price', $cookie);
    $db_description = query($conn, 'description', $cookie);
    $category = query($conn, 'headphone_type', $cookie);
    $stock = query($conn, 'quantity', $cookie);
    $product_id = query($conn, 'id', $cookie);
}


//modify the product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_product'])) {
        //collect form inputs
        $image_url = $_POST['current_image'];
        $product_id = $_POST['product_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $headphone_type = $_POST['headphone_type'];
        $target_file = '';
        $updated_at = date("Y-m-d H:i:s");

        //handle file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['image'];
            $dir = "SiteAssets/Products/";
            $upload_img = basename($image['name']);
            $target_file = $dir . $upload_img;

            if (move_uploaded_file($image['tmp_name'], $target_file)) {
                $image_url = $target_file; // Update the image URL to the new file path
            }
        }

        //prep and execute the update query
        $stmt = $conn->prepare("UPDATE products SET name = ?, headphone_type = ?, description = ?, price = ?, quantity = ?, image_url = ?, updated_at = ? WHERE id = ?");
        $stmt->bind_param("sssdissi", $name, $headphone_type, $description, $price, $quantity, $image_url, $updated_at, $product_id);


        if ($stmt->execute()) {
            echo "Product updated successfully.";
        } else {
            echo "Error updating product: " . $stmt->error;
        }

        $stmt->close();
    }
}

//delete the product
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
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Product</title>
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

<body onload="">
    <div id="navbar-area"></div>

    <div class="sidebar">
        <div class="col-auto">
            <div class=" d-flex flex-column min-vh-100">
                <ul class="nav flex-column mb-sm-auto align-items-center align-items-sm-start">

                    <a href="landing_page.php" class="sidebar-main"><span class="d-sm-in">
                            <img src="Logos/lunatech_white.png" class="sidebar-img-main"></span></a>

                    <a href="employee_dashboard.php" class="nav-link">
                        <span class="d-sm-in"><img src="SiteAssets/home.png" class="sidebar-img"> Home</span></a>

                    <a href="product_dashboard.php" class="nav-link">
                        <span class="d-sm-in"><img src="SiteAssets/products.png" class="sidebar-img"> Product Dashboard</span></a>

                    <a href="add_product.php" class="nav-link">
                        <span class="d-sm-inline"><img src="SiteAssets/add_product.png" class="sidebar-img"> Add Product</span></a>

                    <a href="manage_employee.php" class="nav-link">
                        <span class="d-sm-inline"><img src="SiteAssets/employee.png" class="sidebar-img"> Manage Employees</span></a>
                </ul>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="manage-product-container">
            <div class="back-header">
                <a href="product_dashboard.php" class="back-link">
                    <img src="SiteAssets/back_arrow.png" class="back-icon">
                </a>
                <div class="back-text">
                    <span>
                        <h4 class="back">Back to Product List<h4>
                    </span>
                    <h1 class="heading">Modify Product</h1>
                </div>
            </div>


            <form action="manage_product.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">

                <img src="<?php echo htmlspecialchars($img) ?>">
                <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($img); ?>">
                <div>
                    <label for="image">Upload New Image</label>
                    <input type="file" name="image" accept="image/*">
                </div>
                <input type="text" name="name" value="<?php echo htmlspecialchars($db_name) ?>"
                    placeholder="Product Name" required>
                <input type="text" name="description" value="<?php echo htmlspecialchars($db_description) ?>"
                    placeholder="Product Description" required>
                <input type="number" name="price" value="<?php echo htmlspecialchars($db_price) ?>" required step="0.01"
                    min="0" placeholder="Price">
                <input type="number" name="quantity" value="<?php echo htmlspecialchars($stock) ?>" required step="1" min="0"
                    placeholder="Inventory">
                <div class="col-md-3">
                    <select name="headphone_type" class="form-control" required>
                        <option value="" disabled selected>Category</option>
                        <option value="over-ear" <?php if ($category === "over-ear") echo "selected"; ?>>Over-Ear</option>
                        <option value="in-ear" <?php if ($category === "in-ear") echo "selected"; ?>>In-Ear</option>
                    </select>
                </div>
                <button type="submit" name="update_product">Update Product</button>
            </form>

            <form action="manage_product.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
                <button type="submit" name="delete_product">Delete</button>
            </form>
        </div>
    </div>
</body>

</html>