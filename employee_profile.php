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
        header("Location: employee_profile.php?username=" . urlencode($username)); //redirect to refresh the page
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
    <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()) : ?>
        <div>
            <h3><?php echo htmlspecialchars($row['firstname']) ?></h3>
            <h3><?php echo htmlspecialchars($row['lastname']) ?></h3>
            <h3><?php echo htmlspecialchars($row['job_title']) ?></h3>
        </div>
        <h2>Status</h2>
        <form action="employee_profile.php?username=<?php echo urlencode($row['username']); ?>" method="POST">
            <div class="col-md-3">
                <input type="hidden" name="emp_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                <input type="hidden" name="emp_user" value="<?php echo htmlspecialchars($row['username']); ?>">
                <select name="titles" class="form-control" required>
                    <option value="employee" <?php if ($row['job_title'] === "employee") echo "selected"; ?>>Employee</option>
                    <option value="manager" <?php if ($row['job_title'] === "manager") echo "selected"; ?>>Manager</option>
                    <option value="department head" <?php if ($row['job_title'] === "department head") echo "selected"; ?>>Department Head</option>
                </select>
                <button type="submit" name="update_title">Update Job Title</button>
            </div>
        </form>
        <form action="employee_profile.php?username=<?php echo urlencode($row['username']); ?>" method="POST">
            <div class="col-md-3">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                <input type="hidden" name="user" value="<?php echo htmlspecialchars($row['username']); ?>">
                <select name="privileges" class="form-control" required>
                    <option value="1" <?php if ($row['is_admin']) echo "selected"; ?>>Admin</option>
                    <option value="0" <?php if (!$row['is_admin']) echo "selected"; ?>>User</option>
                </select>
                <button type="submit" name="update_priv">Update User Privileges</button>
            </div>
        </form>
        <form action="employee_profile.php?username=<?php echo urlencode($row['username']); ?>" method="POST">
            <td> <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="username" value="<?php echo htmlspecialchars($row['username']); ?>">
                <button type="submit" name="delete_employee">Terminate Employee</button>
        </form>
    <?php endwhile; endif;?>

</body>

</html>