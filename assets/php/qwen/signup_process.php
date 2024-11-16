<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "INSERT INTO client (client_name, client_email, client_password, client_phone, client_address) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $hashed_password, $phone, $address);

    if ($stmt->execute()) {
        echo "Sign up successful. Please log in.";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>