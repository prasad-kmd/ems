<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $photo = 'uploads/default_profile.jpg'; // Default profile picture

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $file = $_FILES['photo'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_ext = ['jpeg', 'jpg', 'png', 'webp'];

        if (in_array($file_ext, $allowed_ext)) {
            $unique_name = uniqid('', true) . '.' . $file_ext;
            $photo = $uploadDir . $unique_name;
            if (move_uploaded_file($file_tmp, $photo)) {
                // File uploaded successfully
            } else {
                echo "Error uploading file.";
                exit();
            }
        } else {
            echo "Invalid file format. Only JPEG, JPG, PNG, and WEBP files are allowed.";
            exit();
        }
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash the password

    $sql = "INSERT INTO client (client_name, client_email, client_password, client_address, client_phone, client_photo) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = getDbConnection()->prepare($sql);
    $stmt->bind_param("ssssss", $name, $email, $hashed_password, $address, $phone, $photo);

    if ($stmt->execute()) {
        echo "Registration successful. Please sign in.";
        header("refresh:2; url=auth.html");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

getDbConnection()->close();
?>