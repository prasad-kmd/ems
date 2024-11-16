<?php
// Database configuration
$servername = "localhost"; // Default for WAMP
$username = "root";        // Default user
$password = "";            // Default for WAMP
$dbname = "db_ems";        // Name of your database

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
