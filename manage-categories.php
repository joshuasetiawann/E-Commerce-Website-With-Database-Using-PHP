<?php
require 'auth.php';
include 'db.php';
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
			<div class="page-head">
				<h3>Categories</h3>
				<a href="add-category.php" class="btn">+ Add Category</a>
			</div>
			<div class="box">
				<table class="table">
					<thead>
						<tr>
							<th width="60px">No</th>
							<th>Category</th>
							<th width="150px">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$categories = mysqli_query($conn, "SELECT * FROM tb_category ORDER BY category_id DESC");
						if (mysqli_num_rows($categories) > 0) {
							while ($row = mysqli_fetch_array($categories)) {
						?>
						<tr>
							<td><?php echo $no++ ?></td>
							<td><?php echo e($row['category_name']) ?></td>
							<td>
								<span class="actions">
									<a href="edit-category.php?id=<?php echo (int) $row['category_id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
									<a href="delete.php?category=<?php echo (int) $row['category_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
								</span>
							</td>
						</tr>
						<?php }} else { ?>
							<tr>
								<td colspan="3">No data available</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
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
