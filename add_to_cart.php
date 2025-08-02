<?php
session_start();
include "database.php"; // Ensure database connection is included

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];

// Validate product ID from GET request
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("Invalid product ID.");
}

$product_id = intval($_GET["id"]);

// Check if the product is already in the user's cart
$queryCheck = "SELECT * FROM cart WHERE username = ? AND product_id = ?";
$stmtCheck = $conn->prepare($queryCheck);
$stmtCheck->bind_param("si", $username, $product_id);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows > 0) {
    // Product already in cart, inform user
    echo "<script>alert('Product is already in your cart!'); window.location.href='cart.php';</script>";
} else {
    // Insert the product into the cart
    $queryInsert = "INSERT INTO cart (username, product_id) VALUES (?, ?)";
    $stmtInsert = $conn->prepare($queryInsert);
    $stmtInsert->bind_param("si", $username, $product_id);

    if ($stmtInsert->execute()) {
        echo "<script>alert('Product added to cart successfully!'); window.location.href='cart.php';</script>";
    } else {
        echo "<script>alert('Failed to add product to cart.'); window.history.back();</script>";
    }
    $stmtInsert->close();
}

$stmtCheck->close();
$conn->close();
?>
