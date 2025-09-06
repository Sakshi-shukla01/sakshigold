<?php
session_start();
require 'database.php';

// login.php - Handles User Login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM goldshop.users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) { // No hashing yet
            $_SESSION["username"] = $username;
            header("Location: index.php");
            exit();
        } else {
            echo "<p class='error'>Invalid password</p>";
        }
    } else {
        echo "<p class='error'>User not found</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login | Gold Shop</title>
    <style>
        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            background: linear-gradient(135deg, #0f0f0f, #1a1a1a);
            color: #fff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: rgba(20, 20, 20, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(255, 215, 0, 0.4);
            width: 350px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-size: 26px;
            color: #ffd700; /* Gold */
            letter-spacing: 1px;
        }

        .login-container input {
            width: 90%;
            padding: 12px;
            margin: 12px 0;
            border: 1px solid #ffd700;
            border-radius: 8px;
            background: #111;
            color: #fff;
            font-size: 15px;
            transition: 0.3s;
        }

        .login-container input:focus {
            border-color: #fff;
            outline: none;
            box-shadow: 0 0 8px #ffd700;
        }

        .login-container button {
            width: 95%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #ffd700, #b8860b);
            color: #000;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-container button:hover {
            background: linear-gradient(135deg, #b8860b, #ffd700);
            transform: scale(1.05);
        }

        .error {
            color: #ff4d4d;
            margin-top: 15px;
            font-size: 14px;
        }

        .login-container p {
            margin-top: 20px;
            font-size: 14px;
        }

        .login-container a {
            color: #ffd700;
            text-decoration: none;
            font-weight: bold;
        }

        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Gold Shop Login</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit">Login</button>
        </form>
       <p>New here? <a href="register.php">Create Account</a></p>

    </div>
</body>
</html>
