<?php
require "DBdontpublish.php";
session_start();

// ---- Fetch Products ----
$result = $conn->query("SELECT * FROM products");
if (!$result) {
    die("Query failed: " . $conn->error);
}


// ---- Handle Product Addition ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {

        // ---- Insert Product Data ----
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $headphone_type = $_POST['headphone_type'];
        $created_at = date("Y-m-d H:i:s");
        $updated_at = date("Y-m-d H:i:s");


        //handle image file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['image'];
            $dir = "SiteAssets/Products/";
            $upload_img = basename($image['name']);
            $target_file = $dir . $upload_img;
        }

        //check for duplicate products before inserting
        $duplicate = false;
        while ($row = $result->fetch_assoc()) {
            if (
                $name == $row['name'] ||
                $description == $row['description'] ||
                $target_file == $row['image_url']
            ) {
                $duplicate = true;
                break;
            }
        }

        if ($duplicate) {
            echo "Error: Product already exists";
        } else {
            $stmt = $conn->prepare("INSERT INTO products (name, headphone_type, description, price, quantity, image_url, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssdisss", $name, $headphone_type, $description, $price, $quantity, $target_file, $created_at, $updated_at);

            if ($stmt->execute()) {
                header("Location: product_dashboard.php");
            } else {
                echo "Error adding product: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
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

    <div>
        <h4>Back to Dashboard</h4>
        <a href="employee_dashboard.php">
            <img src="SiteAssets/back_arrow.png" style="max-width:3rem">
        </a>
    </div>

    <!-- Add New Product Form -->
    <h1>Add New Product</h1>
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="text" name="description" placeholder="Description" required>
        <input type="number" name="price" placeholder="Price" step="0.01" min="0" required>
        <input type="number" name="quantity" placeholder="Inventory" required step="1" min="0">
        <div class="col-md-3">
            <select name="headphone_type" class="form-control" required>
                <option value="" disabled selected>Category</option>
                <option value="over-ear">Over-Ear</option>
                <option value="in-ear">In-Ear</option>
            </select>
        </div>
        <div>
            <label for="image">Upload Image</label>
            <input type="file" name="image" accept="image/*">
        </div>
        <button type="submit" name="add_product">Add Product</button>
    </form>
    </div>
</body>

</html>