<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    echo "Please login first.";
    exit;
}

$username = $_SESSION['username'];
$product_id = $_GET['product_id'];

$sql = "INSERT INTO orders (username, product_id) VALUES ('$username', '$product_id')";
if (mysqli_query($conn, $sql)) {
    echo "Order placed successfully.";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
