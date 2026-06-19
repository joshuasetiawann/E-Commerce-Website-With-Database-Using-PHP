<?php
include 'db.php';

$search   = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');
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

	<!-- search -->
	<div class="search">
		<div class="container">
			<form action="products.php">
				<input type="text" name="search" placeholder="Search Products" value="<?php echo e($search) ?>">
				<input type="hidden" name="category" value="<?php echo e($category) ?>">
				<input type="submit" name="find" value="Search">
			</form>
		</div>
	</div>

	<!-- new product -->
	<div class="section">
		<div class="container">
			<h3>Products</h3>
			<?php
			$like        = '%' . $search . '%';
			$categoryLike = '%' . $category . '%';
			$stmt = $conn->prepare(
				"SELECT * FROM tb_product
				 WHERE product_status = 1 AND product_name LIKE ? AND category_id LIKE ?
				 ORDER BY product_id DESC"
			);
			$stmt->bind_param('ss', $like, $categoryLike);
			$stmt->execute();
			$products = $stmt->get_result();

			if ($products->num_rows > 0) {
			?>
			<div class="product-grid">
				<?php while ($product = $products->fetch_array()) { ?>
				<a href="product-detail.php?id=<?php echo (int) $product['product_id'] ?>" class="product-card">
					<div class="thumb">
						<img src="products/<?php echo e($product['product_image']) ?>" alt="<?php echo e($product['product_name']) ?>">
					</div>
					<div class="pc-body">
						<span class="name"><?php echo e(substr($product['product_name'], 0, 40)) ?></span>
						<span class="price">Rp <?php echo number_format($product['product_price']) ?></span>
					</div>
				</a>
				<?php } ?>
			</div>
			<?php } else { ?>
				<div class="box"><p class="empty">No products available</p></div>
			<?php } ?>
		</div>
	</div>

	<!-- footer -->
	<div class="footer">
		<div class="container">
			<small>Copyright &copy; 2025 - Company.</small>
		</div>
	</div>
</body>
</html>
