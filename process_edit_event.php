<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventId = $_POST['event_id'];
    $eventTitle = $_POST["event_title"];
    $eventDescription = $_POST["event_description"];
    $eventPrice = $_POST["event_price"];
    $eventType = $_POST["event_type"];
    $eventDate = $_POST["event_date"];
    $eventStartTime = $_POST["event_start_time"];
    $eventEndTime = $_POST["event_end_time"];
    $venueId = $_POST["venue_id"];
    $eventCapacity = $_POST["event_capacity"];

    // Handle file upload (with optional file update)
    $photoPath = $_POST['existing_photo']; // Initialize with the existing photo path

    if (isset($_FILES['event_photo']) && $_FILES['event_photo']['error'] == 0) {
        $allowedTypes = array("image/jpeg", "image/jpg", "image/png", "image/gif", "image/webp");
        if (in_array($_FILES['event_photo']['type'], $allowedTypes)) {
            $ext = pathinfo($_FILES['event_photo']['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid() . "." . $ext;
            $targetPath = "uploads/" . $newFileName;

            if (move_uploaded_file($_FILES['event_photo']['tmp_name'], $targetPath)) {

                // Delete the old image if a new one was uploaded:
                if ($photoPath && file_exists($photoPath) && $photoPath !== 'images/default_profile.jpg') {
                    unlink($photoPath);
                }


                $photoPath = $targetPath; // Update with the new photo path
            } else {
                echo "Error uploading new file.";
            }
        } else {
            echo "Invalid file type.";
        }
    }


    $stmt = $conn->prepare("UPDATE event SET event_title=?, event_description=?, event_photo=?, event_price=?, event_type=?, event_date=?, event_start_time=?, event_end_time=?, venue_id=?, event_capacity=? WHERE event_id=?");
    $stmt->bind_param("sssdssiiiii", $eventTitle, $eventDescription, $photoPath, $eventPrice, $eventType, $eventDate, $eventStartTime, $eventEndTime, $venueId, $eventCapacity, $eventId);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error updating event: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>