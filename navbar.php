<?php
//useful thing called Heredoc syntax
echo <<< HTML
<nav class="navbar">
  <div class="nav-container">
        <div class="nav-left">
            <a class="" href="landing_page.php">
                <img src="Logos/lunatech_icon_white.png" width="6%" margin-left="5%">
            </a>
        </div>

        <div class="nav-right">
            <a class="nav-link" href="catalog.php">Catalog</a>
            <a class="nav-link" href="cart.php">Cart</a>
            <a href="">
                <img src="profile.png" width="6%">
            </a>
        </div>
  </div>
</nav>

HTML;
?>
