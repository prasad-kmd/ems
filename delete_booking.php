<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';

if (isset($_GET['id'])) {
    $bookingId = $_GET['id'];


    //If you want to delete the related payment record as well, do it BEFORE deleting the booking.
    $stmt = $conn->prepare("DELETE FROM payment WHERE booking_id = ?");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute(); // It's okay if this fails if there's no payment


    $stmt = $conn->prepare("DELETE FROM booking WHERE booking_id = ?");
    $stmt->bind_param("i", $bookingId);


    if ($stmt->execute()) {
        header("Location: manage_bookings.php");
        exit;
    } else {
        echo "Error deleting booking: " . $stmt->error;
    }


    $stmt->close();
    $conn->close();

} else {
    echo "Invalid booking ID.";
}

?>