<?php
// ---- DATABASE CONFIGURATION ----
$servername = "54.165.204.136";
$username = "group1";
$password = "tg5z4b31iM]";
$dbname = "group1";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 

// ---- SESSION START ----
session_start();

// ---- LOGOUT PROCESSING ----
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header("Location: process_login.php");
    exit();
}

// ---- LOGIN AND ACCOUNT CREATION PROCESSING ----
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {  // Login form submitted
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Query the database for the user
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username); // "s" means the parameter is a string
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            echo "Login successful! Welcome, " . htmlspecialchars($username) . "!";
            echo '<p><a href="process_login.php?action=logout" class="logout-link">Logout</a></p>';
        } else {
            echo "Invalid username or password.";
        }
    } elseif (isset($_POST['create_account'])) {  // Account creation form submitted
        $username = $_POST['username'];
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Insert user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password); // "ss" means both parameters are strings

        if ($stmt->execute()) {
            echo "Account created successfully! You can now log in.";
        } else {
            echo "Error: Could not create account. Username might be taken.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login or Create Account</title>
    <link rel="stylesheet" href="process_login.css">
</head>
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
    <div class="form-container">
        <?php if (!isset($_SESSION['username'])): ?>
            <h2>Login</h2>
            <form action="process_login.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>

            <h2>Or Create an Account</h2>
            <form action="process_login.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="create_account">Create Account</button>
            </form>
        <?php else: ?>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p><a href="process_login.php?action=logout" class="logout-link">Logout</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
