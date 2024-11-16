<?php
include 'db_connection.php'; // File to handle DB connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $photo = $_FILES['photo'];

    // Validate input
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($phone)) {
        echo "All fields except Address and Photo are required.";
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Handle photo upload
    $photo_path = null;
    if (!empty($photo['name'])) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowed_extensions)) {
            echo "Only JPG, JPEG, PNG, GIF, and WEBP files are allowed.";
            exit;
        }
        if ($photo['size'] > 2 * 1024 * 1024) { // Limit: 2MB
            echo "File size should not exceed 2MB.";
            exit;
        }

        $unique_name = uniqid() . '.' . $extension;
        $photo_path = 'uploads/' . $unique_name;

        if (!move_uploaded_file($photo['tmp_name'], $photo_path)) {
            echo "Error uploading the photo.";
            exit;
        }
    }

    // Insert into database
    $sql = "INSERT INTO client (client_name, client_email, client_password, client_photo, client_phone, client_address) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $email, $hashed_password, $photo_path, $phone, $address);

    if ($stmt->execute()) {
        echo "Registration successful. Redirecting to login page...";
        header("Refresh: 3; URL=auth.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
