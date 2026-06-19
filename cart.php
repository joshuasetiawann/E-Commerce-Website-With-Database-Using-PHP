<?php
session_start();
include 'db.php';

// Initialize cart session if not set.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add to cart.
if (isset($_POST['add_to_cart'])) {
    $product_id = (int) $_POST['add_to_cart'];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    echo "<script>alert('Product added to cart!'); window.location='cart.php';</script>";
    exit;
}

// Handle remove from cart.
if (isset($_POST['remove_from_cart'])) {
    $product_id = (int) $_POST['remove_from_cart'];
    unset($_SESSION['cart'][$product_id]);
    echo "<script>alert('Product removed from cart!'); window.location='cart.php';</script>";
    exit;
}

// Handle update quantity.
if (isset($_POST['update_qty'])) {
    $product_id = (int) $_POST['product_id'];
    $qty = max(1, (int) $_POST['qty']);
    $_SESSION['cart'][$product_id] = $qty;
    echo "<script>window.location='cart.php';</script>";
    exit;
}

// Handle checkout.
if (isset($_POST['checkout'])) {
    // Order persistence could be added here.
    $_SESSION['cart'] = [];
    echo "<script>alert('Order successful!'); window.location='cart.php';</script>";
    exit;
}

// Cart summary.
$cart_count = array_sum($_SESSION['cart']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Company</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/cart.style.css" rel="stylesheet" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand">Company</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
                </ul>
                <!-- Cart Button -->
                <button class="btn btn-outline-dark" type="button" onclick="toggleCart()">
                    <i class="bi-cart-fill me-1"></i>
                    Cart
                    <span class="badge bg-dark text-white ms-1 rounded-pill"><?php echo $cart_count; ?></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Cart Overlay -->
    <div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>

    <!-- Cart Side Menu -->
    <div class="cart-container" id="cart">
        <div class="cart-header">
            <h2>Shopping Cart</h2>
            <button type="button" onclick="toggleCart()" style="font-size:2em;background:none;border:none; color: white;">&times;</button>
        </div>
        <div class="cart-items">
            <?php
            $total = 0;
            if ($cart_count > 0) {
                $ids          = array_map('intval', array_keys($_SESSION['cart']));
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $types        = str_repeat('i', count($ids));

                $stmt = $conn->prepare("SELECT * FROM tb_product WHERE product_id IN ($placeholders)");
                $stmt->bind_param($types, ...$ids);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($item = $result->fetch_assoc()) {
                    $qty = $_SESSION['cart'][$item['product_id']];
                    $subtotal = $item['product_price'] * $qty;
                    $total += $subtotal;
            ?>
                <div class="cart-item">
                    <img src="products/<?php echo e($item['product_image']); ?>" alt="<?php echo e($item['product_name']); ?>">
                    <div class="item-details">
                        <h6><?php echo e($item['product_name']); ?></h6>
                        <p class="price">Rp <?php echo number_format($item['product_price']); ?> x <?php echo $qty; ?></p>
                        <form method="POST" style="display:flex;align-items:center;gap:4px;">
                            <input type="hidden" name="product_id" value="<?php echo (int) $item['product_id']; ?>">
                            <input type="number" name="qty" value="<?php echo $qty; ?>" min="1" style="width:50px;">
                            <button type="submit" name="update_qty" class="btn btn-sm btn-primary">Update</button>
                        </form>
                    </div>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="remove_from_cart" value="<?php echo (int) $item['product_id']; ?>">
                        <button class="remove-btn btn btn-sm btn-danger" type="submit">Remove</button>
                    </form>
                </div>
            <?php
                }
                echo '<div class="cart-total">Total: Rp ' . number_format($total) . '</div>';
            } else {
                echo "<p style='padding:16px;'>Your cart is empty.</p>";
            }
            ?>
        </div>
        <?php if ($cart_count > 0): ?>
            <form method="POST" style="padding:16px;">
                <button type="submit" name="checkout" class="btn btn-success w-100">Checkout</button>
            </form>
        <?php endif; ?>
    </div>

    <header class="bg-dark py-5">
        <div class="container px-4 px-lg-5 my-5" style="height: 60;">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">Shop in style</h1>
                <p class="lead fw-normal text-white-50 mb-0">With this shop homepage template</p>
            </div>
        </div>
    </header>
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <?php
                $products = mysqli_query($conn, "SELECT * FROM tb_product WHERE product_status = 1 ORDER BY product_id DESC LIMIT 8");
                if (mysqli_num_rows($products) > 0) {
                    while ($p = mysqli_fetch_array($products)) {
                ?>
                <div class="col mb-5">
                    <div class="card h-100">
                        <!-- Product image-->
                        <img class="card-img-top" src="products/<?php echo e($p['product_image']) ?>" alt="<?php echo e($p['product_name']) ?>" />
                        <!-- Product details-->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <!-- Product name-->
                                <h5 class="fw-bolder"><?php echo e(substr($p['product_name'], 0, 30)) ?></h5>
                                <!-- Product price-->
                                Rp. <?php echo number_format($p['product_price']) ?>
                            </div>
                        </div>
                        <!-- Product actions-->
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="btn btn-outline-dark" href="product-detail.php?id=<?php echo (int) $p['product_id'] ?>">View Details</a>
                                    <form method="POST" action="cart.php">
                                        <input type="hidden" name="add_to_cart" value="<?php echo (int) $p['product_id']; ?>">
                                        <button type="submit" class="btn btn-outline-dark">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }} else { ?>
                <p>No products available</p>
                <?php } ?>
            </div>
        </div>
    </section>
    <!-- Footer-->
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Company</p>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/cart.scripts.js"></script>
</body>

</html>
