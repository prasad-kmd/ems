<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit();
}
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_id = $_POST['client_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $photo = '';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['photo']['name']);
        $photo = basename($_FILES['photo']['name']);

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            // File uploaded successfully
        } else {
            echo "Error uploading file.";
            exit();
        }
    }

    $sql = "UPDATE client SET client_name = ?, client_email = ?, client_password = ?, client_phone = ?, client_address = ?, client_photo = ? WHERE client_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $name, $email, $hashed_password, $phone, $address, $photo, $client_id);

    if ($stmt->execute()) {
        header("Location: manage_clients.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>