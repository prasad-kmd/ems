<?php
require_once 'db_config.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password
    $address = $_POST["address"];
    $phone = $_POST["phone"];


    // File upload handling
    $photoPath = null;
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
        $allowedTypes = array("image/jpeg", "image/jpg", "image/png", "image/gif", "image/webp");
        if (in_array($_FILES["photo"]["type"], $allowedTypes)) {
            $ext = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
            $newFileName = uniqid() . "." . $ext; // Generate unique filename
            $targetPath = "uploads/" . $newFileName; // Specify upload directory
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetPath)) {
                $photoPath = $targetPath;
            } else {
                 // Handle upload error
            }
        } else {
            // Handle invalid file type
        }
    }


    // Prepare and execute the SQL query
    $stmt = $conn->prepare("INSERT INTO client (client_name, client_email, client_password, client_photo, client_phone, client_address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $password, $photoPath, $phone, $address);


    if ($stmt->execute()) {
        // Redirect to login page after successful registration  (JavaScript redirect is also shown below)
         echo "<script>alert('Registration successful!'); window.location.href = 'auth.html';</script>";

        exit; //Important! Stops further execution


    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>