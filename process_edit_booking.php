<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}


require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookingId = $_POST['booking_id'];
    $numberGuests = $_POST['number_guests'];
    $bookingStatus = $_POST['booking_status'];

    $stmt = $conn->prepare("UPDATE booking SET number_guests=?, booking_status=? WHERE booking_id=?");
    $stmt->bind_param("isi", $numberGuests, $bookingStatus, $bookingId);

    if ($stmt->execute()) {
        header("Location: manage_bookings.php");
        exit;
    } else {
        echo "Error updating booking: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>