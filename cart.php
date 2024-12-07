<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
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


    //Event Listener for Deleting the Product from Cart
    document.addEventListener('DOMContentLoaded', () => {
        const deleteIcons = document.querySelectorAll('.cart-pr-delete');
        deleteIcons.forEach(icon => {
            icon.addEventListener('click', function() {
                const productName = this.closest('tr').querySelector('.cart-pr-name').textContent.trim();
                deleteCookie(productName);
            });
        });
    });

    //delete cookie by name
    function deleteCookie(name) {
        let cartItems = getCart();
        const updatedCart = cartItems.filter(item => item.name !== name); //filter out the clicked product
        setCartCookie(updatedCart); //update the cookie to have only what's left
        updateTotals();
        location.reload();
    }

    //Update the Carted Product Quantities and Totals
    document.addEventListener('DOMContentLoaded', function() {
        //add event listeners to quantity inputs
        //find the qty inputs
        const quantityInputs = document.querySelectorAll('input[name="quantity"]');
        const cart = getCart();

        //add the event listener
        quantityInputs.forEach((input, index) => {
            input.addEventListener('input', function() {
                const quantity = parseFloat(input.value); //reads current qty value
                const productName = document.querySelectorAll('tbody tr')[index]
                    .querySelector('.cart-pr-name').textContent; //gets product name from current index

                //update the cart for "this" specific product
                updateCart(productName, quantity);

                //update the totals when the quantities change
                updateTotals();
            });
        });
    });

    //Dependencies to make Updating the Cart and Totals possible
    //note: cart cookie functionality borrowed from the existing cart.php file
    //update the cart cookie for the product whose qty is changing
    function updateCart(name, quantity) {
        let cart = getCart();
        for (let i = 0; i < cart.length; i++) {
            if (cart[i].name === name) {
                cart[i].quantity = quantity;
                break;
            }
        }
        setCartCookie(cart);
    }

    // reset the cart cookie
    function setCartCookie(cartItems) {
        document.cookie = "cart-items=" + JSON.stringify(cartItems) + "; path=/; max-age=" + (30 * 24 * 60 * 60);
    }

    //get the cart from the cookie
    function getCart() {
        const cartCookie = document.cookie.split('; ').find(row => row.startsWith('cart-items='));
        if (cartCookie) {
            return JSON.parse(cartCookie.split('=')[1]);
        } else {
            return [];
        }
    }

    //update the totals
    function updateTotals() {
        let subtotal = 0;
        let tax = 0;

        //recalc subtotal and tax
        const productRows = document.querySelectorAll('tbody tr');
        productRows.forEach(row => {
            const quantity = parseFloat(row.querySelector('input[name="quantity"]').value);
            //gets price and removes the string text "Price: $"
            const price = parseFloat(row.querySelector('.cart-pr-price').textContent.replace('Price: $', ''));
            subtotal += (quantity * price);
        });

        tax = subtotal * 0.06;
        const total = subtotal + tax;

        //update totals on page
        document.querySelector('.cart-subtotal').textContent = `Subtotal: $ ${subtotal.toFixed(2)}`;
        document.querySelector('.cart-tax').textContent = `Tax: $ ${tax.toFixed(2)}`;
        document.querySelector('.cart-total').textContent = `Total: $ ${total.toFixed(2)}`;
    }

    //Boostrap Script for the Buy Now Button
    document.addEventListener('DOMContentLoaded', function() {
        const alertPlaceholder = document.getElementById('liveAlertPlaceholder');
        const alertBtn = document.getElementById('liveAlertBtn');

        function showLiveAlert(message, type) {
            const wrapper = document.createElement('div');
            wrapper.innerHTML = `
            <div class="alert alert-${type} alert-dismissible" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
            alertPlaceholder.appendChild(wrapper);
        }

        alertBtn.addEventListener('click', function() {
            showLiveAlert('Purchase successful! Thank you for your order.', 'success');
        });
    });
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

$result = null;
//one massive SQL query
if (!empty($productNames)) {
    //makes comma separated ? to insert into WHERE based on number of products
    $placeholders = implode(',', array_fill(0, count($productNames), '?'));
    $stmt = $conn->prepare("SELECT * FROM products WHERE name IN ($placeholders)");
    //makes the same number of s' as products, ... unpacks $productNames
    $stmt->bind_param(str_repeat('s', count($productNames)), ...$productNames);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
    } else {
        $result = null;
    }
}
?>

<body onload="loadNavbar()">
    <div id="navbar-area"></div>

    <div class="row">
        <div class="cart-container">
            <?php if (isset($cartEmpty) && $cartEmpty): ?>
                <!-- Display 'Cart is empty' message -->
                <div class="empty-cart-message">
                    <h3 style="margin-left: 1rem">Your cart is empty.</h3>
                </div>
            <?php elseif ($result && $result->num_rows > 0): ?>
                <!-- Cart Table Section -->
                <div class="cart-table-container">
                    <div class="table-responsive table-border">
                        <table class="my-table">
                            <thead>
                                <tr>
                                    <th>
                                        <h3 class="cart-head">Your Cart</h3>
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $subTotal = 0;
                                $totalTax = 0;
                                while ($row = $result->fetch_assoc()) :
                                    // Get quantities of each product
                                    $quantity = 0;
                                    foreach ($productDetails as $item) {
                                        if ($item['name'] === $row['name']) {
                                            $quantity = $item['quantity'];
                                            break;
                                        }
                                    }

                                    // Calculate subtotal and tax
                                    $subTotal += $row['price'] * $quantity;
                                    $totalTax += ($row['price'] * $quantity) * 0.06;
                                ?>
                                    <!-- Display Cart Items -->
                                    <tr>
                                        <td>
                                            <div class="cart-img-container">
                                                <img src="<?php echo htmlspecialchars($row['image_url']); ?>"
                                                    alt="Product Image" class="cart-pr-img">
                                            </div>
                                        </td>
                                        <td class="cart-text">
                                            <h5 class="cart-pr-name"><?php echo htmlspecialchars($row['name']); ?></h5>
                                        </td>
                                        <td class="cart-text">
                                            <p class="cart-pr-price">Price: $<?php echo number_format($row['price'], 2); ?></p>
                                        </td>
                                        <td>
                                            <div class="cart-quantity-container">
                                                <label for="quantity">Quantity </label>
                                                <input type="number" name="quantity" value="<?php echo htmlspecialchars($quantity) ?>"
                                                    required step="1" min="0" class="cart-pr-qty">
                                            </div>
                                        </td>
                                        <td>
                                            <img src="SiteAssets/delete.png" class="cart-pr-delete">
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                <?php
                                $total = $subTotal + $totalTax; // Calculate total
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Total Section -->
                <div class="total">
                    <div class="total-text">
                        <h4 class="cart-subtotal">Subtotal: $<?php echo number_format($subTotal, 2); ?></h4>
                        <h5 class="cart-tax">Tax: $<?php echo number_format($totalTax, 2); ?></h5>
                        <h2 class="cart-total">Total: $<?php echo number_format($total, 2); ?></h2>
                    </div>
                    <form action="cart.php" method="POST">
                        <div id="liveAlertPlaceholder"></div>
                        <button type="button" class="buy-btn" id="liveAlertBtn">Buy Now</button>
                    </form>
                </div>
            <?php else: ?>
                <!-- Fallback for unexpected conditions -->
                <div class="empty-cart-message">
                    <p>Your cart is empty.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>

<?php
$conn->close();
?>