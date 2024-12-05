<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lunatech</title>
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

<?php
require "DBdontpublish.php";
require "product_cookie.php";
require "cart_cookie.php";

$cartItems = [];
if (isset($_COOKIE['cart-items'])) {
    $cartItems = json_decode($_COOKIE['cart-items'], true);
} else {
    $cartEmpty = true;
}

function getCookieDetails($cartItems)
{
    $details = [];
    foreach ($cartItems as $item) {
        $details[] = [
            'name' => $item['name'],
            'quantity' => $item['quantity']
        ];
    }
    return $details;
}

$productDetails = getCookieDetails($cartItems);
$productNames = array_column($productDetails, 'name');

//one massive SQL query
if (!empty($productNames)) {
    //makes comma separated ? to insert into WHERE based on number of products
    $placeholders = implode(',', array_fill(0, count($productNames), '?'));
    $stmt = $conn->prepare("SELECT * FROM products WHERE name IN ($placeholders)");
    //makes the same number of s' as products, ... unpacks $productNames
    $stmt->bind_param(str_repeat('s', count($productNames)), ...$productNames);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<body onload="loadNavbar()">
    <div id="navbar-area"></div>
    <div class="cart">
        <div class="in-cart">
            <?php if (isset($cartEmpty) && $cartEmpty): ?>
                <p>Your cart is empty.</p>
            <?php elseif ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <!--get quantities of each product and prep for subtotal-->
                    <?php
                    $subTotal = 0;
                    $totalTax = 0;
                    $quantity = 0;
                    foreach ($productDetails as $item) {
                        if ($item['name'] === $row['name']) {
                            $quantity = $item['quantity'];
                            break;
                        }
                    }

                    //calculate tax and subtotal
                    $subTotal += $row['price'] * $quantity;
                    $totalTax += ($row['price'] * $quantity) * 0.06;
                    ?>

                    <!--Display Cart Items-->
                    <img src="<?php echo htmlspecialchars($row['image_url']) ?>">
                    <p><?php echo htmlspecialchars($row['name']) ?></p>
                    <p><?php echo htmlspecialchars($row['price']) ?></p>
                    <p><?php echo "Quantity: " . htmlspecialchars($quantity) ?></p>

                <?php endwhile;
                $total = $subTotal + $totalTax; //get total
                ?>
            <?php endif; ?>
        </div>

        <div class="total">
            <?php if (!isset($cartEmpty)): ?>
                <h3>Subtotal: <?php echo number_format($subTotal, 2); ?></h3>
                <h3>Tax: <?php echo number_format($totalTax, 2); ?></h3>
                <h2>Total: <?php echo number_format($total, 2); ?></h2>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>

<?php
$conn->close();
?>