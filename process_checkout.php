<?php
session_start();
include "database.php"; // your DB connection file

// Check user login
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];

// Get user ID from username
$userQuery = $conn->prepare("SELECT id FROM users WHERE username = ?");
$userQuery->bind_param("s", $username);
$userQuery->execute();
$userResult = $userQuery->get_result();

if ($userResult->num_rows === 0) {
    die("User not found.");
}
$user = $userResult->fetch_assoc();
$user_id = $user['id'];

// Fetch cart items for this user with product details
$cartQuery = $conn->prepare("
    SELECT c.product_id, p.price, COUNT(*) as quantity
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.username = ?
    GROUP BY c.product_id, p.price
");
$cartQuery->bind_param("s", $username);
$cartQuery->execute();
$cartResult = $cartQuery->get_result();

if ($cartResult->num_rows === 0) {
    die("Your cart is empty.");
}

// Calculate total amount
$total_amount = 0;
$cart_items = [];
while ($row = $cartResult->fetch_assoc()) {
    $total_amount += $row['price'] * $row['quantity'];
    $cart_items[] = $row;
}

// Insert order
$orderInsert = $conn->prepare("INSERT INTO orders (user_id, total_amount, order_date) VALUES (?, ?, NOW())");
$orderInsert->bind_param("id", $user_id, $total_amount);

if (!$orderInsert->execute()) {
    die("Failed to place order.");
}

$order_id = $orderInsert->insert_id;

// Insert order items
$orderItemInsert = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");

foreach ($cart_items as $item) {
    $orderItemInsert->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    if (!$orderItemInsert->execute()) {
        die("Failed to insert order items.");
    }
}

// Clear the cart after successful order
$clearCart = $conn->prepare("DELETE FROM cart WHERE username = ?");
$clearCart->bind_param("s", $username);
$clearCart->execute();

// Close statements and connection
$orderInsert->close();
$orderItemInsert->close();
$clearCart->close();
$userQuery->close();
$cartQuery->close();
$conn->close();

echo "<script>alert('Order placed successfully!'); window.location.href='index.php';</script>";
exit();
?>
