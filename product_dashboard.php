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
                    <div class="table-responsive inventory-border">
                        <table class="inventory">
                            <thead>
                                <tr>
                                    <th>
                                        <h3>Inventory</h3>
                                    </th>

                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
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
                                                    <h5 id="pr-name"><?php echo htmlspecialchars($row['name']); ?></h5>

                                                    <p id="pr-price">$<?php echo htmlspecialchars(number_format($row['price'], 2)); ?></p>
                                                </td>
                                                <td></td>
                                                <td><button class="inv-btn" id="manage-btn"><a href="manage_product.php"
                                                            class="inv-btn-link cookie-data" data-product-id="<?php echo htmlspecialchars($row['name']) ?>">Manage Product</a></button></td>

                                                <td><form action="product_dashboard.php" method="POST" style="display:inline;">
                                                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                                        <button class="inv-btn" id="delete-btn" type="submit" name="delete_product">
                                                            <img src="SiteAssets/delete.png" class="img-delete"></button>
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
        </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>