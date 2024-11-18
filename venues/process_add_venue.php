<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $venueName = $_POST["venue_name"];
    $venueDescription = $_POST["venue_description"];
    $venueAddress = $_POST["venue_address"];
    $venueCapacity = $_POST["venue_capacity"];
    $venueEmail = $_POST["venue_email"];
    $venuePhone = $_POST["venue_phone"];


    // Handle file upload (similar to events)
    $photoPath = null;  // Or a default path
    if (isset($_FILES['venue_photo']) && $_FILES['venue_photo']['error'] == 0) {
        $allowedTypes = array("image/jpeg", "image/jpg", "image/png", "image/gif", "image/webp");
        if (in_array($_FILES['venue_photo']['type'], $allowedTypes)) {
            $ext = pathinfo($_FILES['venue_photo']['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid() . "." . $ext;
            $targetPath = "../uploads/" . $newFileName; // Make sure uploads directory exists

            if (move_uploaded_file($_FILES['venue_photo']['tmp_name'], $targetPath)) {
                $photoPath = $targetPath;
            } else {
                echo "Error uploading file."; // Handle error appropriately
            }
        } else {
            echo "Invalid file type."; // Handle error
        }
    }



    $stmt = $conn->prepare("INSERT INTO venue (venue_name, venue_description, venue_photo, venue_address, venue_capacity, venue_email, venue_phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiss", $venueName, $venueDescription, $photoPath, $venueAddress, $venueCapacity, $venueEmail, $venuePhone);

    if ($stmt->execute()) {
        header("Location: manage_venues.php");
        exit;
    } else {
        echo "Error adding venue: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>