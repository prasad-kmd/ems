<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

if (isset($_GET['id'])) {
    $venueId = $_GET['id'];


    // Get the photo path before deleting
    $stmt = $conn->prepare("SELECT venue_photo FROM venue WHERE venue_id = ?");
    $stmt->bind_param("i", $venueId);
    $stmt->execute();
    $stmt->bind_result($venuePhoto);
    $stmt->fetch();
    $stmt->close();


    $stmt = $conn->prepare("DELETE FROM venue WHERE venue_id = ?");
    $stmt->bind_param("i", $venueId);



    if ($stmt->execute()) {

        if ($venuePhoto && file_exists($venuePhoto) && $venuePhoto !== 'images/default_profile.jpg') {
            unlink($venuePhoto);
        }

        header("Location: manage_venues.php");
        exit;

    } else {
        echo "Error deleting venue: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();


} else {
    echo "Invalid venue ID.";
}


?>