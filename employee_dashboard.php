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


//get logged in user data
$query = $conn->prepare("SELECT * FROM users where username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
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

<style>
    body {
        background-color: #f8f9fa;
    }
</style>

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
        <div class="user-prfl-cont">
            <div class="user-prfl">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <img src="SiteAssets/user_profile.png" id="user-img">
                        <h2><?php echo htmlspecialchars($row['job_title']) ?></h2>
                        <h3><?php echo htmlspecialchars($row['firstname'] . " " . $row['lastname']) ?></h3>
                        <h4><?php echo htmlspecialchars($row['email']) ?></h4>
                <?php endwhile;
                endif; ?>
                <a href="login.php?action=logout" class="">Logout</a>
            </div>
        </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>