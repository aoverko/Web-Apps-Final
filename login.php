<?php
// ---- DATABASE CONFIGURATION ----
require "DBdontpublish.php";


// ---- SESSION START ----
session_start();

// ---- LOGOUT PROCESSING ----
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// ---- LOGIN PROCESSING ----
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {  // Login form submitted
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Query the database for the user
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            // echo "Login successful! Welcome, " . htmlspecialchars($username) . "!";
            // echo '<p><a href="process_login.php?action=logout" class="logout-link">Logout</a></p>';
        } else {
            echo "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
    <div class="form-container">
        <?php if (!isset($_SESSION['username'])): ?>
            <h2>Login</h2>
            <form action="login.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
                <a href="signup.php">Don't have an account? Sign up!</a>
            </form>

        <?php else: ?>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p><a href="login.php?action=logout" class="logout-link">Logout</a></p>
        <?php endif; ?>
    </div>
</body>

</html>