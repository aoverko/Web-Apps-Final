<?php
require "DBdontpublish.php";
session_start();

// Verifies a user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Checks if the user is an admin
$stmt = $conn->prepare("SELECT is_admin FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();


// Redirects regular employees away from the "Manage Employees" page
if (!$user['is_admin'] && $_SERVER['REQUEST_URI'] === '/manage_employee.php') {
    header("Location: login.php");
    exit();
}

//gets the users table
$result = $conn->query("SELECT * FROM users ORDER BY username");
if (!$result) {
    die("Query failed: " . $conn->error);
}

//update employee privileges
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_priv'], $_POST['user_id'], $_POST['privileges'])) {
        $user_id = intval($_POST['user_id']);
        $privileges = ($_POST['privileges'] === "1") ? 1 : 0;

        $stmt = $conn->prepare("UPDATE users set is_admin = ? WHERE id = ?");
        $stmt->bind_param("ii", $privileges, $user_id);
        if ($stmt->execute()) {
            echo "Privileges updated successfully.";
            header("Location: manage_employee.php"); //redirect to refresh the page
            exit();
        } else {
            echo "Error updating privileges: " . $stmt->error;
        }
        $stmt->close();
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees</title>
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
        <div class="manage-employees">
            <div class="back-header">
                <a href="employee_dashboard.php" class="back-link">
                    <img src="SiteAssets/back_arrow.png" class="back-icon">
                </a>
                <div class="back-text">
                    <span>
                        <h4 class="back">Back to Dashboard<h4>
                    </span>
                    <h1 class="heading">Employee Directory</h1>
                </div>
            </div>


            <div class="row">
                <div class="col">
                    <h3 class="card-title">Current Employees</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Job Title</th>
                                    <th>Actions</th>
                                    <th>Privileges</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                                        <td><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                                        <td>
                                            <a href="employee_profile.php?username=<?php echo urlencode($row['username']); ?>"
                                                class="btn btn-sm btn-outline-primary">View Profile</a>
                                        </td>
                                        <td>
                                            <form action="manage_employee.php" method="POST">
                                                <div class="col-md-3">
                                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                                    <select name="privileges" class="form-control" required>
                                                        <option value="1" <?php if ($row['is_admin']) echo "selected"; ?>>Admin</option>
                                                        <option value="0" <?php if (!$row['is_admin']) echo "selected"; ?>>User</option>
                                                    </select>
                                                    <button type="submit" name="update_priv">Update</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
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