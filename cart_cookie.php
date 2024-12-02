<script>
    //set cart cookie
    function setCartCookie(cartItems) {
        document.cookie = "cart-items=" + JSON.stringify(cartItems) + "; path=/; max-age=" + (30 * 24 * 60 * 60);
    }

    //event listener for "add to cart" click
    document.addEventListener('DOMContentLoaded', function() {
        const productLinks = document.querySelectorAll('.cart-data');

        productLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                const name = this.getAttribute('data-product-name');
                //can use if there's an input field or if just clicking add to cart
                const quantityInput = document.getElementById("quantity-input").value;
                let quantity = quantityInput ? parseInt(quantityInput, 10) : 1;
                addToCart(name, quantity);
            });
        });
    });

    //parse the cart
    function getCart() {
        const cartCookie = document.cookie.split('; ').find(row => row.startsWith('cart-items='));

        if (cartCookie) {
            //gets the cart data and makes it into a json array
            return JSON.parse(cartCookie.split('=')[1]);
        } else {
            return [];
        }
    }

    //add products to cart
    function addToCart(name, quantity) {
        let cart = getCart();

        //increase quantity
        let exists = false;
        for (let i = 0; i < cart.length; i++) {
            if (cart[i].name === name) {
                cart[i].quantity += quantity;
                exists = true;
                break;
            }
        }

        //add the product
        if (!exists) {
            cart.push({
                name: name,
                quantity: quantity
            });
        }

        setCartCookie(cart);
    }
</script>