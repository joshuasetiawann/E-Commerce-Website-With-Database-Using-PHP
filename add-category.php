<?php
require 'auth.php';
include 'db.php';

if (isset($_POST['submit'])) {
	$name = ucwords(trim($_POST['name']));

	$stmt = $conn->prepare('INSERT INTO tb_category (category_name) VALUES (?)');
	$stmt->bind_param('s', $name);

	if ($stmt->execute()) {
		echo '<script>alert("Data added successfully"); window.location="manage-categories.php";</script>';
		exit;
	}
	$error = 'Failed to add data: ' . $conn->error;
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
			<h3>Add Category</h3>
			<div class="box">
				<?php if (!empty($error)) echo '<div class="form-error">' . e($error) . '</div>'; ?>
				<form action="" method="POST">
					<input type="text" name="name" placeholder="Category Name" class="input-control" required>
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
</body>
</html>
