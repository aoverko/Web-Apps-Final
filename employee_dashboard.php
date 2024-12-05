<?php
require "DBdontpublish.php";
session_start();
$username;

// Verifies a user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} else {
    $username = $_SESSION['username'];
}

// Checks if the user is an admin
$stmt = $conn->prepare("SELECT is_admin FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();


//get logged in user data
$query = $conn->prepare("SELECT * FROM users where username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();


// Redirects regular employees away from the "Manage Employees" page
if (!$user['is_admin'] && $_SERVER['REQUEST_URI'] === '/manage_employee.php') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
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
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <h4><?php echo htmlspecialchars($row['firstname']) ?></h4>
                <h4><?php echo htmlspecialchars($row['lastname']) ?></h4>
                <h4><?php echo htmlspecialchars($row['email']) ?></h4>
                <h4><?php echo htmlspecialchars($row['job_title']) ?></h4>

        <?php endwhile;
        endif; ?>
        <p><a href="login.php?action=logout" class="">Logout</a></p>
    </div>

</body>

</html>

<?php
$conn->close();
?>