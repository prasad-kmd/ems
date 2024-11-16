<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';

if (isset($_GET['id'])) {
    $clientId = $_GET['id'];

    //It's important to get the image path BEFORE deleting the database record
    $stmt = $conn->prepare("SELECT client_photo FROM client WHERE client_id = ?");
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $stmt->bind_result($clientPhoto);
    $stmt->fetch();
    $stmt->close();




    $stmt = $conn->prepare("DELETE FROM client WHERE client_id = ?");
    $stmt->bind_param("i", $clientId);

    if ($stmt->execute()) {
        // Delete the client's profile picture if it exists, it is a file and not the default image:
        if ($clientPhoto && file_exists($clientPhoto) && $clientPhoto !== 'images/default_profile.jpg') {
            unlink($clientPhoto);
        }



        header("Location: manage_clients.php");
        exit;
    } else {
        echo "Error deleting client: " . $stmt->error;  // Or handle the error more gracefully
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid client ID.";
}
