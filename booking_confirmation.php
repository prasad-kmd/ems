<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: auth.php"); // Or your login page
    exit;
}

require_once 'db_config.php';


if (isset($_GET['booking_id'])) {
    $bookingId = $_GET['booking_id'];

    // Fetch booking details (including related data from other tables)
    $stmt = $conn->prepare("
        SELECT 
            b.booking_id,
            b.booking_date,
            b.number_guests,
            e.event_title,
            e.event_date AS event_date, /* Alias to avoid ambiguity */
            e.event_start_time,
            e.event_end_time,
            v.venue_name,
            p.payment_amount,
            p.payment_transaction_id
        FROM booking b
        JOIN event e ON b.event_id = e.event_id
        JOIN venue v ON e.venue_id = v.venue_id
        JOIN payment p ON b.booking_id = p.booking_id /* Assuming payment is always created */
        WHERE b.booking_id = ?
    ");
    $stmt->bind_param("i", $bookingId);


    if ($stmt->execute()) {


        $result = $stmt->get_result();

        if ($result->num_rows > 0) {


            $booking = $result->fetch_assoc();

?>




            <!DOCTYPE html>
            <html>

            <head>
                <title>Booking Confirmation</title>
            </head>

            <body>
                <h2>Booking Confirmation</h2>
                <p>Thank you for your booking!</p>

                <p><strong>Booking ID:</strong> <?php echo $booking['booking_id']; ?></p>
                <p><strong>Event:</strong> <?php echo $booking['event_title']; ?></p>
                <p><strong>Event Date:</strong> <?php echo $booking['event_date']; ?></p>
                <p><strong>Event Time:</strong> <?php echo $booking['event_start_time'] . ' - ' . $booking['event_end_time']; ?></p>
                <p><strong>Venue:</strong> <?php echo $booking['venue_name']; ?></p>
                <p><strong>Booking Date:</strong> <?php echo $booking['booking_date']; ?></p>
                <p><strong>Number of Guests:</strong> <?php echo $booking['number_guests']; ?></p>
                <p><strong>Total Amount:</strong> $<?php echo $booking['payment_amount']; ?></p>
                <p><strong>Transaction ID:</strong> <?php echo $booking['payment_transaction_id']; ?></p>




                <p><a href="browse_events.php">Back to Browse Events</a></p>


            </body>

            </html>


<?php




        } else {
            echo "<p>Booking details not found.</p>"; // Handle error appropriately

        }
    } else {


        echo "Error retrieving booking: " . $stmt->error; // Handle error


    }




    $stmt->close();
    $conn->close();
} else {
    //Handle invalid booking id
    echo "Invalid request";
}


?>