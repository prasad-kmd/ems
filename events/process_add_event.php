<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventTitle = $_POST["event_title"];
    $eventDescription = $_POST["event_description"];
    $eventPrice = $_POST["event_price"];
    $eventType = $_POST["event_type"];
    $eventDate = $_POST["event_date"];
    $eventStartTime = $_POST["event_start_time"];
    $eventEndTime = $_POST["event_end_time"];
    $venueId = $_POST["venue_id"];
    $eventCapacity = $_POST["event_capacity"];

    // File upload handling
    $photoPath = null; // Default value
    if (isset($_FILES['event_photo']) && $_FILES['event_photo']['error'] == 0) {
        $allowedTypes = array("image/jpeg", "image/jpg", "image/png", "image/gif", "image/webp");
        if (in_array($_FILES['event_photo']['type'], $allowedTypes)) {
            $ext = pathinfo($_FILES['event_photo']['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid() . "." . $ext;
            $targetPath = "../uploads/" . $newFileName; // Make sure 'uploads' directory exists

            if (move_uploaded_file($_FILES['event_photo']['tmp_name'], $targetPath)) {
                $photoPath = $targetPath;
            } else {
                // Handle upload error (e.g., display an error message)
                echo "Error uploading file.";
            }
        } else {
            // Handle invalid file type (e.g., display an error message)
            echo "Invalid file type.";
        }
    }


    $stmt = $conn->prepare("INSERT INTO event (event_title, event_description, event_photo, event_price, event_type, event_date, event_start_time, event_end_time, venue_id, event_capacity) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdssssii", $eventTitle, $eventDescription, $photoPath, $eventPrice, $eventType, $eventDate, $eventStartTime, $eventEndTime, $venueId, $eventCapacity);



    if ($stmt->execute()) {
        header("Location: ../admin_dashboard.php");
        exit;
    } else {
        echo "Error: " . $stmt->error; // Display error message for debugging
    }

    $stmt->close();
    $conn->close();
}
?>