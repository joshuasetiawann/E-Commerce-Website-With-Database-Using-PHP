<?php
require 'auth.php';
include 'db.php';

if (isset($_POST['submit'])) {
	$category    = (int) $_POST['category'];
	$name        = trim($_POST['name']);
	$price       = (int) $_POST['price'];
	$description = $_POST['description'];
	$status      = (int) $_POST['status'];

	// Uploaded image.
	$filename = $_FILES['image']['name'];
	$tmp_name = $_FILES['image']['tmp_name'];
	$ext      = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

	$allowed = ['jpg', 'jpeg', 'png', 'gif'];

	if (!in_array($ext, $allowed, true)) {
		$error = 'Invalid file format. Allowed: ' . implode(', ', $allowed);
	} else {
		$newName = 'product' . time() . '.' . $ext;
		move_uploaded_file($tmp_name, './products/' . $newName);

		$stmt = $conn->prepare(
			'INSERT INTO tb_product (category_id, product_name, product_price, product_description, product_image, product_status)
			 VALUES (?, ?, ?, ?, ?, ?)'
		);
		$stmt->bind_param('isissi', $category, $name, $price, $description, $newName, $status);

		if ($stmt->execute()) {
			echo '<script>alert("Data added successfully"); window.location="manage-products.php";</script>';
			exit;
		}
		$error = 'Failed to add data: ' . $conn->error;
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Company</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
	<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
</head>
<body>
	<!-- header -->
	<header>
		<div class="container">
			<h1><a href="dashboard.php">Company</a></h1>
			<ul>
				<li><a href="dashboard.php">Dashboard</a></li>
				<li><a href="manage-categories.php">Categories</a></li>
				<li><a href="manage-products.php">Products</a></li>
				<li><a href="logout.php">Logout</a></li>
			</ul>
		</div>
	</header>

	<!-- content -->
	<div class="section">
		<div class="container">
			<h3>Add Product</h3>
			<div class="box">
				<?php if (!empty($error)) echo '<div class="form-error">' . e($error) . '</div>'; ?>
				<form action="" method="POST" enctype="multipart/form-data">
					<select class="input-control" name="category" required>
						<option value="">-- Select --</option>
						<?php
						$categories = mysqli_query($conn, "SELECT * FROM tb_category ORDER BY category_id DESC");
						while ($row = mysqli_fetch_array($categories)) {
						?>
						<option value="<?php echo (int) $row['category_id'] ?>"><?php echo e($row['category_name']) ?></option>
						<?php } ?>
					</select>

					<input type="text" name="name" class="input-control" placeholder="Product Name" required>
					<input type="number" name="price" class="input-control" placeholder="Price" required>
					<input type="file" name="image" class="input-control" required>
					<textarea class="input-control" name="description" placeholder="Description"></textarea><br>
					<select class="input-control" name="status" required>
						<option value="">-- Select --</option>
						<option value="1">Active</option>
						<option value="0">Inactive</option>
					</select>
					<input type="submit" name="submit" value="Submit" class="btn">
				</form>
			</div>
		</div>
	</div>

	<!-- footer -->
	<footer>
		<div class="container">
			<small>Copyright &copy; 2025 - Company.</small>
		</div>
	</footer>
	<script>
		CKEDITOR.replace('description');
	</script>
</body>
</html>
