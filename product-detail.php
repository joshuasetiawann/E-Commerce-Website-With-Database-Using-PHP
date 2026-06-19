<?php
include 'db.php';

$product_id = (int) ($_GET['id'] ?? 0);

$stmt = $conn->prepare('SELECT * FROM tb_product WHERE product_id = ? AND product_status = 1');
$stmt->bind_param('i', $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_object();

// Contact number for the WhatsApp button (first registered admin).
$admin = $conn->query('SELECT admin_telp FROM tb_admin ORDER BY admin_id ASC LIMIT 1')->fetch_object();
$admin_telp = $admin->admin_telp ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Company</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
</head>
<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="index.php">Company</a></h1>
            <ul>
                <li><a href="products.php">Products</a></li>
            </ul>
        </div>
    </header>

    <!-- product detail -->
    <div class="section">
        <div class="container">
            <h3>Product Details</h3>
            <div class="box">
                <?php if ($product) { ?>
                <div class="product-detail">
                    <div class="gallery">
                        <img src="products/<?php echo e($product->product_image) ?>" alt="<?php echo e($product->product_name) ?>">
                    </div>
                    <div class="info">
                        <h2><?php echo e($product->product_name) ?></h2>
                        <div class="price-tag">Rp <?php echo number_format($product->product_price) ?></div>
                        <p class="desc-label">Description</p>
                        <div class="desc"><?php echo $product->product_description ?></div>
                        <a class="btn-whatsapp" href="https://api.whatsapp.com/send?phone=<?php echo e($admin_telp) ?>&text=Hi, I'm interested in your product." target="_blank">
                            <img src="img/wa.png" alt="WhatsApp"> Contact via WhatsApp
                        </a>
                    </div>
                </div>
                <?php } else { ?>
                    <p class="empty">Product not found.</p>
                <?php } ?>
            </div>
        </div>
    </div>
    <footer>
        <div class="container">
            <small>Copyright &copy; 2025 - Company.</small>
        </div>
    </footer>
</body>
</html>
