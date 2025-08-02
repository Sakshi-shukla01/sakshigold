<?php
session_start();
include "database.php";

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];

// Get user ID
$getUser = $conn->prepare("SELECT id FROM users WHERE username = ?");
$getUser->bind_param("s", $username);
$getUser->execute();
$userResult = $getUser->get_result();
if ($userResult->num_rows === 0) {
    die("User not found.");
}
$userData = $userResult->fetch_assoc();
$user_id = $userData['id'];

// Get cart items: product_id, price, quantity
$getCart = $conn->prepare("
    SELECT c.product_id, p.price, COUNT(*) AS quantity 
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.username = ?
    GROUP BY c.product_id, p.price
");
$getCart->bind_param("s", $username);
$getCart->execute();
$cartResult = $getCart->get_result();

if ($cartResult->num_rows === 0) {
    echo "Your cart is empty. <a href='index.php'>Go back to shop</a>";
    exit();
}

// Calculate total amount
$total = 0;
$cart_items = [];
while ($item = $cartResult->fetch_assoc()) {
    $cart_items[] = $item;
    $total += $item['price'] * $item['quantity'];
}

// Insert into orders table
$insertOrder = $conn->prepare("INSERT INTO orders (user_id, total_amount, order_date) VALUES (?, ?, NOW())");
$insertOrder->bind_param("id", $user_id, $total);
$insertOrder->execute();
$order_id = $insertOrder->insert_id;

// Insert each product into order_items
$insertItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach ($cart_items as $item) {
    $pid = $item['product_id'];
    $qty = $item['quantity'];
    $price = $item['price'];

    // Double-check product_id
    if (!$pid) {
        die("Error: Invalid product ID in cart.");
    }

    $insertItem->bind_param("iiid", $order_id, $pid, $qty, $price);
    $insertItem->execute();
}

// Clear cart
$clearCart = $conn->prepare("DELETE FROM cart WHERE username = ?");
$clearCart->bind_param("s", $username);
$clearCart->execute();

echo "<script>alert('Order placed successfully!'); window.location.href='index.php';</script>";

// Close connections
$getUser->close();
$getCart->close();
$insertOrder->close();
$insertItem->close();
$clearCart->close();
$conn->close();
?>
