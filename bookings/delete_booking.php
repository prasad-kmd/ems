<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

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
    echo "<title>Invalid Booking ID - Error 400</title>
    <link rel=\"stylesheet\" href=\"../assets/css/semantic.css\">
    <link rel=\"stylesheet\" href=\"../assets/font/fonts.css\" />
    <div class=\"ui middle aligned center aligned grid\" style=\"height: 100vh; background-color: #f9f9f9;\">
        <div class=\"column\" style=\"max-width: 450px;\">
          <div class=\"ui segment\" style=\"background-color: #fff; padding: 3em; box-shadow: 0 0 10px rgba(0,0,0,0.1);\">
            <div class=\"ui huge header\" style=\"color: #655CAA;font-family: 'Philosopher';\">Invalid Booking ID</div>
            <div class=\"ui divider\"></div>
            <div class=\"ui negative message\" style=\"border-radius: 0; box-shadow: none;\">
              <i class=\"fas fa-exclamation-triangle fa-3x\" style=\"float: left; margin: 0 1em 0 0; color: #D9534F;\"></i>
              <div class=\"header\" style=\"color: #767676;\">Error 400: Bad Request</div>
              <p>The booking ID you entered is invalid or does not exist in our system.</p>
              <p>Please double-check your booking ID and try again. Ensure it matches the one provided in your booking confirmation.</p>
              <div class=\"actions\">
                <a href=\"manage_bookings.php\" class=\"ui button\" style=\"margin-left: 0.5em;\">Return to Manage Bookings</a>
              </div>
            </div>
          </div>
        </div>
      </div>";
}

?>