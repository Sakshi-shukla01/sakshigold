<?php
session_start();
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "INSERT INTO goldshop.users (username, password) VALUES ('$username', '$password')";
    if (mysqli_query($conn, $sql)) {
        echo "<p class='success'>Registration successful. <a href='login.php'>Login here</a></p>";
    } else {
        echo "<p class='error'>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register | Gold Shop</title>
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

        .register-container {
            background: rgba(20, 20, 20, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(255, 215, 0, 0.4);
            width: 350px;
            text-align: center;
        }

        .register-container h2 {
            margin-bottom: 20px;
            font-size: 26px;
            color: #ffd700; /* Gold */
            letter-spacing: 1px;
        }

        .register-container input {
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

        .register-container input:focus {
            border-color: #fff;
            outline: none;
            box-shadow: 0 0 8px #ffd700;
        }

        .register-container button {
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

        .register-container button:hover {
            background: linear-gradient(135deg, #b8860b, #ffd700);
            transform: scale(1.05);
        }

        .success {
            color: #32cd32;
            margin-top: 15px;
            font-size: 14px;
        }

        .error {
            color: #ff4d4d;
            margin-top: 15px;
            font-size: 14px;
        }

        .register-container p {
            margin-top: 20px;
            font-size: 14px;
        }

        .register-container a {
            color: #ffd700;
            text-decoration: none;
            font-weight: bold;
        }

        .register-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Create Account</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
