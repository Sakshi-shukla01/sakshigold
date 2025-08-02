<?php
$host = "localhost:3307";
$user = "root";
$pass = "sakshi@123";
$dbname = "goldshop";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
