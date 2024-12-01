
<!--set product cookie based on click for each link -->
<script>
function setProductCookie(identifier) {
    document.cookie = "view-product=" + identifier + "; path=/; max-age=" + (60 * 60 * 24); 
};


document.addEventListener('DOMContentLoaded', function() {
    const productLinks = document.querySelectorAll('.cookie-data');

    productLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            const productId = this.getAttribute('data-product-id');
            setProductCookie(productId);
        });
    });
});

</script>


<?php
function query($conn, $column, $name)
{
    $query = "SELECT $column from products WHERE name = '$name'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row[$column];
        }
    }
}
?>