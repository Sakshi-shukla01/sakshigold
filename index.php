<?php
session_start();

// Fetch gold price only if it's not already stored in the session
if (!isset($_SESSION['gold_price_per_gram'])) {
    $apiKey = "goldapi-blxaacsm8jow717-io";  // Replace with your actual API key
    $url = "https://www.goldapi.io/api/XAU/INR";

    $options = [
        "http" => [
            "header" => "x-access-token: $apiKey\r\n"
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response) {
        $data = json_decode($response, true);
        if (isset($data['price'])) {
            $_SESSION['gold_price_per_gram'] = $data['price'] / 31.1035; // Convert ounce to gram
        } else {
            $_SESSION['gold_price_per_gram'] = "N/A"; // Handle API failure
        }
    } else {
        $_SESSION['gold_price_per_gram'] = "N/A"; // If API request fails
    }
}

$goldPricePerGram = $_SESSION['gold_price_per_gram'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwarnaVeda</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>SwarnaVeda</h1>
        <nav>
            <ul>
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

    <!-- Gold Price Section -->
    <section class="gold-price-section">
        <h2>Today's Gold Price</h2>
        <p><strong>₹<?php echo ($goldPricePerGram !== "N/A") ? number_format($goldPricePerGram, 2) . " per gram" : "Price unavailable"; ?></strong></p>
    </section>

    <section id="jewelry-categories" class="category-container">
        <div class="category">
            <img src="images/earrings.png" alt="Earrings">
            <a href="view_collection.php?type=earrings"><button>View Collection</button></a>
        </div>
        <div class="category">
            <img src="images/anklet.png" alt="Anklets">
            <a href="view_collection.php?type=anklets"><button>View Collection</button></a>
        </div>
        <div class="category">
            <img src="images/ring.png" alt="Rings">
            <a href="view_collection.php?type=rings"><button>View Collection</button></a>
        </div>
        <div class="category">
            <img src="images/bangles.png" alt="Bangles">
            <a href="view_collection.php?type=bangles"><button>View Collection</button></a>
        </div>
        <div class="category">
            <img src="images/necklace.png" alt="Necklaces">
            <a href="view_collection.php?type=necklaces"><button>View Collection</button></a>
        </div>
        <div class="category">
            <img src="images/artificial.png" alt="Artificial Jewelry">
            <a href="view_collection.php?type=artificial"><button>View Collection</button></a>
        </div>
    </section>
    
    <footer>
        <p>&copy; 2025 SwarnaVeda. All rights reserved.</p>
    </footer>
    
    <script src="script.js"></script>
</body>
</html>
