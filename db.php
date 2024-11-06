<?php
$host = 'localhost'; // Database host
$db = 'movie'; // Your database name
$user = 'root'; // Default XAMPP MySQL username
$pass = ''; // Default XAMPP MySQL password (leave empty if using XAMPP default)

// Create a connection to the MySQL database
$conn = new mysqli($host, $user, $pass, $db);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
