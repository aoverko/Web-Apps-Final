<?php
require "DBdontpublish.php";
session_start();

// Verifies a user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


//get username query param
$username;
if (isset($_GET['username'])) {
    $username = urldecode($_GET['username']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
}


//update job title
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_title'], $_POST['emp_id'], $_POST['titles'])) {
        $emp_id = intval($_POST['emp_id']);
        $emp_user = $_POST['emp_user'];
        $title = $_POST['titles'];

        $stmt = $conn->prepare("UPDATE users set job_title = ? WHERE id = ?");
        $stmt->bind_param("si", $title, $emp_id);
        if ($stmt->execute()) {
            header("Location: employee_profile.php?username=" . urlencode($emp_user)); //redirect to refresh the page
            exit();
        } else {
            echo "Error updating job title: " . $stmt->error;
        }
        $stmt->close();
    }
}


//update employee privileges
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_priv'], $_POST['user_id'], $_POST['privileges'])) {
        $user_id = intval($_POST['user_id']);
        $user = $_POST['user'];
        $privileges = ($_POST['privileges'] === "1") ? 1 : 0;

        $stmt = $conn->prepare("UPDATE users set is_admin = ? WHERE id = ?");
        $stmt->bind_param("ii", $privileges, $user_id);
        if ($stmt->execute()) {
            header("Location: employee_profile.php?username=" . urlencode($user)); //redirect to refresh the page
            exit();
        } else {
            echo "Error updating privileges: " . $stmt->error;
        }
        $stmt->close();
    }
}


//terminate employee
if (isset($_POST['delete_employee'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: manage_employee.php"); //redirect to refresh the page
        exit();
    } else {
        echo "Error terminating employee: " . $stmt->error;
    }
    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Profile</title>
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
        <div class="back-header">
            <a href="manage_employee.php" class="back-link">
                <img src="SiteAssets/back_arrow.png" class="back-icon">
            </a>
            <div class="back-text">
                <span>
                    <h4 class="back">Back to Employee Directory<h4>
                </span>
                <h1 class="heading">Employee Profile</h1>
            </div>
        </div>


        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="emp-prfl-cont">
                    <div class="emp-prfl-left">
                        <img src="SiteAssets/user_profile.png" id="profile-img">
                        <h3><?php echo htmlspecialchars($row['firstname'] . " " . $row['lastname']) ?></h3>
                            <h2><?php echo htmlspecialchars($row['job_title']) ?></h2>
                    </div>

                    <div class="emp-prfl-right">
                        <h4>Status</h4>
                        <div class="emp-select">
                            <form action="employee_profile.php?username=<?php echo urlencode($row['username']); ?>" method="POST">
                                <div class="">
                                    <input type="hidden" name="emp_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <input type="hidden" name="emp_user" value="<?php echo htmlspecialchars($row['username']); ?>">
                                    <select name="titles" class="" required>
                                        <option value="Employee" <?php if ($row['job_title'] === "Employee") echo "selected"; ?>>Employee</option>
                                        <option value="Manager" <?php if ($row['job_title'] === "Manager") echo "selected"; ?>>Manager</option>
                                        <option value="Department Head" <?php if ($row['job_title'] === "Department Head") echo "selected"; ?>>Department Head</option>
                                    </select>
                                    <button type="submit" name="update_title">Update Job Title</button>
                                </div>
                            </form>
                        </div>
                        <div class="emp-select">
                            <form action="employee_profile.php?username=<?php echo urlencode($row['username']); ?>" method="POST">
                                <div class="">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <input type="hidden" name="user" value="<?php echo htmlspecialchars($row['username']); ?>">
                                    <select name="privileges" class="" required>
                                        <option value="1" <?php if ($row['is_admin']) echo "selected"; ?>>Admin</option>
                                        <option value="0" <?php if (!$row['is_admin']) echo "selected"; ?>>User</option>
                                    </select>
                                    <button type="submit" name="update_priv">Update Privileges</button>
                                </div>
                            </form>
                        </div>

                        <form action="employee_profile.php?username=<?php echo urlencode($row['username']); ?>" method="POST">
                            <td> <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="username" value="<?php echo htmlspecialchars($row['username']); ?>">
                                <button class="term-btn" type="submit" name="delete_employee">Terminate Employee</button>
                        </form>
                    </div>
                </div>
        <?php endwhile;
        endif; ?>
    </div>
</body>

</html>