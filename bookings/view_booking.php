<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

if (isset($_GET['id'])) {
    $bookingId = $_GET['id'];

    // Fetch booking details, including joined data from related tables
    $sql = "SELECT
                b.booking_id,
                c.client_name,
                c.client_email,
                c.client_phone,
                e.event_title,
                e.event_description,
                v.venue_name,
                b.booking_date,
                b.number_guests,
                b.booking_status,
                p.payment_amount,
                p.payment_status
            FROM booking AS b
            JOIN client AS c ON b.client_id = c.client_id
            JOIN event AS e ON b.event_id = e.event_id
            JOIN venue AS v ON e.venue_id = v.venue_id
            LEFT JOIN payment AS p ON b.booking_id = p.booking_id  /* Left join for payment, as it might not exist yet */
            WHERE b.booking_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Booking</title>
</head>
<body>
    <h2>Booking Details</h2>

    <p><strong>Booking ID:</strong> <?php echo $booking['booking_id']; ?></p>
    <p><strong>Client Name:</strong> <?php echo $booking['client_name']; ?></p>
    <p><strong>Client Email:</strong> <?php echo $booking['client_email']; ?></p>
    <p><strong>Client Phone:</strong> <?php echo $booking['client_phone']; ?></p>
    <p><strong>Event Title:</strong> <?php echo $booking['event_title']; ?></p>
    <p><strong>Event Description:</strong> <?php echo $booking['event_description']; ?></p>
    <p><strong>Venue Name:</strong> <?php echo $booking['venue_name']; ?></p>
    <p><strong>Booking Date:</strong> <?php echo $booking['booking_date']; ?></p>
    <p><strong>Number of Guests:</strong> <?php echo $booking['number_guests']; ?></p>
    <p><strong>Booking Status:</strong> <?php echo $booking['booking_status']; ?></p>

    <?php if ($booking['payment_amount']): ?>  <!-- Check if payment information is available -->
    <p><strong>Payment Amount:</strong> <?php echo $booking['payment_amount']; ?></p>
    <p><strong>Payment Status:</strong> <?php echo $booking['payment_status']; ?></p>

    <?php endif; ?>


    <a href="manage_bookings.php">Back to Manage Bookings</a> </div>
</body>
</html>

<?php

        $stmt->close();
        $conn->close();
    } else {
        echo "Booking not found.";
    }
} else {
    echo "Invalid booking ID.";
}
?>