<?php
session_start();
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "INSERT INTO goldshop.users (username, password) VALUES ('$username', '$password')";
    if (mysqli_query($conn, $sql)) {
        echo "Registration successful. <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<form method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>
