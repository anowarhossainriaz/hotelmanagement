<?php
$servername = "localhost"; // Server is localhost (default)
$username = "root";        // Default username in XAMPP
$password = "";            // Default password is empty (""), if you set a password, replace this
$dbname = "hotelmanagement"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Welcome to : $dbname";
}
?>
