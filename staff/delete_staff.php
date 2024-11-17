<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';

if (isset($_GET['id'])) {
    $staffIdToDelete = $_GET['id'];

    // Prevent self-deletion
    if ($staffIdToDelete == $_SESSION['staff_id']) {
        echo "You cannot delete yourself.";
        exit;
    }


     // Get the photo path before deleting
    $stmt = $conn->prepare("SELECT staff_photo FROM staff WHERE staff_id = ?");
    $stmt->bind_param("i", $staffIdToDelete);
    $stmt->execute();
    $stmt->bind_result($staffPhoto);
    $stmt->fetch();
    $stmt->close();



    $stmt = $conn->prepare("DELETE FROM staff WHERE staff_id = ?");
    $stmt->bind_param("i", $staffIdToDelete);

    if ($stmt->execute()) {

        // Delete the staff member's profile picture if it exists
         if ($staffPhoto && file_exists($staffPhoto) && $staffPhoto !== 'images/default_profile.jpg') {
            unlink($staffPhoto);
         }

        header("Location: manage_staff.php");
        exit;
    } else {
        echo "Error deleting staff member: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();


} else {
    echo "Invalid staff ID.";
}

?>