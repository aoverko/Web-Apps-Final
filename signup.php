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

// ---- ACCOUNT CREATION PROCESSING ----
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['create_account'])) {  // Account creation form submitted
        $is_admin = 0;
        $username = $_POST['username'];
        $email = $_POST['email'];
        $lname = $_POST['lname'];
        $fname = $_POST['fname'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Insert user into the database
        $stmt = $conn->prepare("INSERT INTO users (is_admin, username, email, password, lastname, firstname) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $is_admin, $username, $email, $password, $lname, $fname);

        if ($stmt->execute()) {
            echo "Account created successfully! You can now log in.";
            header("Refresh: 2; url=login.php");
            exit();
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
    <title>Create Account</title>
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

<style>
    body {
        background-color: #0D0B1C;
    }
</style>

<body onload="loadNavbar()" class="login">
    <div id="navbar-area"></div>
    <div class="form-body create-acct-body">
        <div class="form-container create-acct-container">
            <?php if (!isset($_SESSION['username'])): ?>
                <h2 class="login-heading">Create an Account</h2>
                <form action="signup.php" method="POST">
                    <input type="text" name="fname" placeholder="First Name" required>
                    <input type="text" name="lname" placeholder="Last Name" required>
                    <input type="text" name="email" placeholder="email@lunatech.com" required>
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="create_account" class="acct-button">Create Account</button>
                    <a href="login.php" class="login-link">Already have an account? Log in!</a>
                </form>
            <?php else: ?>
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                <p><a href="signup.php?action=logout" class="logout-link">Logout</a></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>