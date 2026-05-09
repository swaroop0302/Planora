<?php
// db_connect.php

$host = "localhost";          // Database host
$db_name = "travel_explorer"; // Database name
$username = "root";           // Database username
$password = "";               // Database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
