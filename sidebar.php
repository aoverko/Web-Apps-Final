<?php
session_start();
require "DBdontpublish.php";
// Checks if the user is an admin
$stmt = $conn->prepare("SELECT is_admin FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

?>



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

                    <a href="manage_employee.php" class="nav-link <?php if ($user['is_admin'] == 0 ) echo "hide" ?>">
                        <span class="d-sm-inline"><img src="SiteAssets/employee.png" class="sidebar-img
                        <?php if ($user['is_admin'] == 0) echo "hide" ?>"> 
                        <?php if ($user['is_admin'] == 1) echo " Manage Employees"?>
                        <?php if (!$user) echo ""?></span></a>
                </ul>
            </div>
        </div>
    </div>