<?php
session_start();
include "database.php"; // Ensure database connection is included

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];
$query = "SELECT products.id, products.name, products.price, products.image 
          FROM cart 
          JOIN products ON cart.product_id = products.id 
          WHERE cart.username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Calculate total price
$totalPrice = 0;
$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
    $totalPrice += $row['price'];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Your Shopping Cart</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
    <header>
        <h1>Your Shopping Cart</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if (count($cartItems) > 0): ?>
                <li><a href="checkout.php">Checkout</a></li>
                <?php endif; ?>
                <li id="auth-link"><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section id="cart">
        <?php if (count($cartItems) === 0): ?>
            <p>Your cart is empty. <a href="index.php">Go back to shop</a></p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($cartItems as $item): ?>
                    <div class="product">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" />
                        <h2><?php echo htmlspecialchars($item['name']); ?></h2>
                        <p>Price: ₹<?php echo number_format($item['price'], 2); ?></p>
                        <a href="remove_from_cart.php?id=<?php echo intval($item['id']); ?>">
                            <button>Remove</button>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="cart-summary">
                <h3>Total: ₹<?php echo number_format($totalPrice, 2); ?></h3>
                <a href="checkout.php"><button>Proceed to Checkout</button></a>
            </div>
        <?php endif; ?>
    </section>

    <footer>
        <p>&copy; 2025 Luxury Jewelry Shop. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
