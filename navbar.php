<?php
//useful thing called Heredoc syntax
echo <<< HTML
<nav class="navbar">
  <div class="nav-box">
        <div class="nav-left">
            <a href="landing_page.php">
                <img src="Logos/lunatech_icon_white.png" class="nav-img">
            </a>
        </div>

        <div class="nav-right">
            <a class="nav-links" href="catalog.php">Catalog</a>
            <a href="cart.php">
                <img src="cart.png">
            </a>
            <a href="">
                <img src="profile.png">
            </a>
        </div>
  </div>
</nav>

HTML;
?>
