<?php
session_start();
include "database.php";

if (!isset($_GET['type'])) {
    die("Invalid category selected.");
}

$category = $_GET['type'];
$query = "SELECT * FROM goldshop.products WHERE category = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

$goldPricePerGram = $_SESSION['gold_price_per_gram'] ?? "N/A";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo ucfirst($category); ?> Collection</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <header>
    <h1><?php echo ucfirst($category); ?> Collection</h1>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li id="auth-link">
          <?php if (isset($_SESSION["username"])): ?>
            <a href="logout.php">Logout</a>
          <?php else: ?>
            <a href="login.php">Login</a> / <a href="register.php">Signup</a>
          <?php endif; ?>
        </li>
      </ul>
    </nav>
  </header>

  <section class="gold-price-section">
    <h2>Today's Gold Price</h2>
    <p><strong>
      ₹<?php echo ($goldPricePerGram !== "N/A") ? number_format($goldPricePerGram, 2) . " per gram" : "Price unavailable"; ?>
    </strong></p>
  </section>

  <section id="collection">
    <div class="row">
      <?php while ($row = $result->fetch_assoc()): ?>
        <?php
          $calculatedPrice = ($goldPricePerGram !== "N/A")
              ? $row['weight'] * $goldPricePerGram
              : $row['price'];

          $categoryLower = strtolower($row['category']);
          $imagePng = preg_replace('/\.(jpg|jpeg)$/i', '.png', $row['image']);

          // Cache-busting logic to ensure updated images load
          $imagePath = $imagePng;
          $version = file_exists($imagePath) ? filemtime($imagePath) : time();
        ?>
        <div class="product">
          <img src="<?php echo $imagePath . '?v=' . $version; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
          <h2><?php echo htmlspecialchars($row['name']); ?></h2>
          <p>Weight: <?php echo $row['weight']; ?>g</p>
          <p>Price: ₹<?php echo number_format($calculatedPrice, 2); ?></p>
          <a href="add_to_cart.php?id=<?php echo $row['id']; ?>"><button>Add to Cart</button></a>

          <?php if (in_array($categoryLower, ['earrings', 'necklaces', 'rings', 'bangles'])): ?>
            <?php if ($categoryLower === 'rings'): ?>
              <a href="tryon-ring.html?image=<?php echo urlencode($imagePng); ?>">
                <button>Try-On Now</button>
              </a>
            <?php elseif ($categoryLower === 'necklaces'): ?>
              <a href="tryon-necklace.html?image=<?php echo urlencode($imagePng); ?>">
                <button>Try-On Now</button>
              </a>
            <?php elseif ($categoryLower === 'bangles'): ?>
              <a href="tryon-bangle.html?image=<?php echo urlencode($imagePng); ?>">
                <button>Try-On Now</button>
              </a>
            <?php elseif ($categoryLower === 'earrings'): ?>
              <a href="tryon-mediapipe.html?image=<?php echo urlencode($imagePng); ?>&category=earrings">
                <button>Try-On Now</button>
              </a>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 SwarnaVeda. All rights reserved.</p>
  </footer>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
