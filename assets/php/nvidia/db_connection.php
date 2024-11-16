<?php
// Database connection settings
$servername = "localhost";
$database = "db_ems"; // Your database name
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}
?>