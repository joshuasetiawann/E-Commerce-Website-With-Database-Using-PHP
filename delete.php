<?php
require 'auth.php';
include 'db.php';

// Delete a category.
if (isset($_GET['category'])) {
	$id = (int) $_GET['category'];
	$stmt = $conn->prepare('DELETE FROM tb_category WHERE category_id = ?');
	$stmt->bind_param('i', $id);
	$stmt->execute();
	echo '<script>window.location="manage-categories.php"</script>';
	exit;
}

// Delete a product (and its image file).
if (isset($_GET['product'])) {
	$id = (int) $_GET['product'];

	$stmt = $conn->prepare('SELECT product_image FROM tb_product WHERE product_id = ?');
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$product = $stmt->get_result()->fetch_object();

	if ($product && $product->product_image && file_exists('./products/' . $product->product_image)) {
		unlink('./products/' . $product->product_image);
	}

	$stmt = $conn->prepare('DELETE FROM tb_product WHERE product_id = ?');
	$stmt->bind_param('i', $id);
	$stmt->execute();
	echo '<script>window.location="manage-products.php"</script>';
	exit;
}
