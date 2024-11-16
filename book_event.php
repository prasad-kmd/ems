<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: auth.php"); // Redirect to login if not logged in
    exit;
}

require_once 'db_config.php';

if (isset($_GET['event_id'])) {
    $eventId = $_GET['event_id'];

    // Fetch event and venue details
    $stmt = $conn->prepare("SELECT e.*, v.venue_name, v.venue_capacity FROM event e JOIN venue v ON e.venue_id = v.venue_id WHERE e.event_id = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();

        // Calculate available seats
        $bookedSeatsStmt = $conn->prepare("SELECT SUM(number_guests) AS total_booked FROM booking WHERE event_id = ? AND booking_status = 'confirmed'");
        $bookedSeatsStmt->bind_param("i", $eventId);
        $bookedSeatsStmt->execute();
        $bookedSeatsResult = $bookedSeatsStmt->get_result();
        $bookedSeatsData = $bookedSeatsResult->fetch_assoc();
        $bookedSeats = $bookedSeatsData['total_booked'] ?? 0;
        $availableSeats = $event['venue_capacity'] - $bookedSeats;


        // Close statements to avoid "Commands out of sync" errors
        $stmt->close();
        $bookedSeatsStmt->close();
        $conn->close();


        if ($availableSeats > 0) : ?>
            <!DOCTYPE html>
            <html>

            <head>
                <title>Book Event</title>
            </head>

            <body>
                <h2>Book Event: <?php echo $event['event_title']; ?></h2>

                <p><strong>Date:</strong> <?php echo $event['event_date']; ?></p>
                <p><strong>Time:</strong> <?php echo $event['event_start_time'] . ' - ' . $event['event_end_time']; ?></p>
                <p><strong>Venue:</strong> <?php echo $event['venue_name']; ?></p>
                <p><strong>Price:</strong> $<?php echo $event['event_price']; ?> per person</p>
                <p><strong>Available Seats:</strong> <?php echo $availableSeats; ?></p>

                <form action="process_booking.php" method="post">
                    <input type="hidden" name="event_id" value="<?php echo $eventId; ?>">
                    <input type="hidden" name="client_id" value="<?php echo $_SESSION['client_id']; ?>">

                    <label for="number_guests">Number of Guests:</label>
                    <input type="number" id="number_guests" name="number_guests" min="1" max="<?php echo $availableSeats; ?>" value="1" required><br><br>


                    <input type="submit" value="Book Now">
                </form>

            </body>

            </html>

        <?php else : ?>
            <p>This event is sold out.</p> <a href="browse_events.php">Back to Browse Events</a>

<?php endif; //End of available seats check


    } else {
        echo "<p>Event not found.</p>";
    }
} else {
    echo "<p>Invalid event ID.</p>";
}


?>