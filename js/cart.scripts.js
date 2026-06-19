/*!
* Start Bootstrap - Shop Homepage v5.0.6 (https://startbootstrap.com/template/shop-homepage)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-shop-homepage/blob/master/LICENSE)
*/
// This file is intentionally blank
// Use this file to add JavaScript to your project
<script>
function toggleCart() {
    const cart = document.getElementById('cart');
    const overlay = document.getElementById('cartOverlay');
    cart.classList.toggle('open');
    if(cart.classList.contains('open')) {
        overlay.style.display = 'block';
        setTimeout(() => overlay.classList.add('open'), 10);
    } else {
        overlay.classList.remove('open');
        setTimeout(() => overlay.style.display = 'none', 400);
    }
}
</script>