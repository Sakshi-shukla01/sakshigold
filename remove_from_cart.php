<?php
session_start();
include "database.php"; // Ensure database connection is included

// Check if user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];
if (!isset($_GET["id"])) {
    die("Invalid product ID.");
}

$product_id = intval($_GET["id"]);

// Remove product from cart
$query = "DELETE FROM cart WHERE username = ? AND product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $username, $product_id);

if ($stmt->execute()) {
    echo "<script>alert('Product removed from cart.'); window.location.href='cart.php';</script>";
} else {
    echo "<script>alert('Failed to remove product.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
