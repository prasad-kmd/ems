<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';


if (isset($_GET['id'])) {
    $eventId = $_GET['id'];


    // Get the photo path before deleting the event
    $stmt = $conn->prepare("SELECT event_photo FROM event WHERE event_id = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $stmt->bind_result($eventPhoto);
    $stmt->fetch();
    $stmt->close();




    $stmt = $conn->prepare("DELETE FROM event WHERE event_id = ?");
    $stmt->bind_param("i", $eventId);

    if ($stmt->execute()) {


      if ($eventPhoto && file_exists($eventPhoto) && $eventPhoto !== './images/default_profile.jpg') {
          unlink($eventPhoto);
      }


        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error deleting event: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid event ID.";
}
?>