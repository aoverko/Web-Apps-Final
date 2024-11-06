<?php
// ---- DATABASE CONFIGURATION ----
$servername = "localhost";
$user = "group1";
$pass = "tg5z4b31im";
$dbname = "group1";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// ---- SESSION MANAGEMENT ----
session_start();

// ---- ROUTING BASED ON PAGE ----
$page = $_GET['page'] ?? 'home';  // Default to 'home' page if no parameter is given

// ---- USER INTERFACE (FRONT END) ----
if ($page == 'home') {
    // Home Page: Display Product Catalog
    echo "<h1>Welcome to LunaTech Gadgets</h1><div class='product-list'>";
    $stmt = $conn->query("SELECT * FROM products");
    while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "
        <div class='product'>
            <img src='{$product['image_url']}' alt='{$product['name']}' />
            <h2>{$product['name']}</h2>
            <p>\${$product['price']}</p>
            <a href='?page=product&id={$product['id']}'>View Details</a>
        </div>";
    }
    echo "</div>";
} elseif ($page == 'product') {
    // Product Details Page
    $id = $_GET['id'] ?? 1;
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "
    <h1>{$product['name']}</h1>
    <img src='{$product['image_url']}' alt='{$product['name']}' />
    <p>{$product['description']}</p>
    <p>Price: \${$product['price']}</p>
    <p>Stock: {$product['stock']} units</p>";
}

// ---- ADMIN LOGIN PAGE ----
elseif ($page == 'login') {
    if ($_POST) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if ($username == 'admin' && $password == 'password123') {
            $_SESSION['admin_logged_in'] = true;
            header('Location: ?page=dashboard');
        } else {
            echo "Invalid credentials.";
        }
    }
    echo "
    <h1>Admin Login</h1>
    <form method='POST'>
        <input type='text' name='username' placeholder='Username' required>
        <input type='password' name='password' placeholder='Password' required>
        <button type='submit'>Login</button>
    </form>";
}

// ---- ADMIN DASHBOARD (PRODUCT MANAGEMENT) ----
elseif ($page == 'dashboard') {
    if (!isset($_SESSION['admin_logged_in'])) {
        header('Location: ?page=login');
        exit;
    }
    if ($_POST) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $image_url = $_POST['image_url'];

        $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $stock, $image_url]);

        echo "Product added successfully.";
    }
    echo "
    <h1>Admin Dashboard</h1>
    <form method='POST'>
        <input type='text' name='name' placeholder='Product Name' required>
        <textarea name='description' placeholder='Description'></textarea>
        <input type='number' step='0.01' name='price' placeholder='Price' required>
        <input type='number' name='stock' placeholder='Stock' required>
        <input type='text' name='image_url' placeholder='Image URL' required>
        <button type='submit'>Add Product</button>
    </form>
    <a href='?page=logout'>Logout</a>";
}

// ---- ADMIN LOGOUT ----
elseif ($page == 'logout') {
    session_destroy();
    header('Location: ?page=login');
}

// ---- STYLES ----
?>

<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
    text-align: center;
}
h1 {
    margin-top: 20px;
}
.product-list {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    margin: 20px;
}
.product {
    border: 1px solid #ccc;
    margin: 10px;
    padding: 10px;
    width: 200px;
    text-align: center;
}
.product img {
    max-width: 100%;
}
form {
    margin: 20px;
}
input, textarea, button {
    display: block;
    margin: 10px auto;
    padding: 10px;
    width: 80%;
    max-width: 300px;
}
</style>
