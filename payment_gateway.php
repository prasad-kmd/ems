<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: auth.php"); // Or your login page
    exit;
}

require_once 'db_config.php';

if (isset($_GET['booking_id'])) {
    $bookingId = $_GET['booking_id'];

    // Fetch booking details (including event, client, and venue details)
    $stmt = $conn->prepare("
        SELECT 
            b.*,
            e.event_title, e.event_date, e.event_start_time, e.event_end_time, e.event_price,
            v.venue_name
        FROM booking b
        JOIN event e ON b.event_id = e.event_id
        JOIN venue v ON e.venue_id = v.venue_id
        WHERE b.booking_id = ?
    ");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();

        // Calculate total price
        $totalPrice = $booking['number_guests'] * $booking['event_price'];

        $stmt->close();
        $conn->close(); // Close the connection after fetching data

?>

        <!DOCTYPE html>
        <html>

        <head>
            <title>Payment Gateway</title>
        </head>

        <body>
            <h2>Payment Gateway (Simulation)</h2>

            <form action="process_payment_gateway.php" method="post">
                <input type="hidden" name="booking_id" value="<?php echo $bookingId; ?>">

                <h3>Booking Summary</h3>
                <p><strong>Event:</strong> <?php echo $booking['event_title']; ?></p>
                <p><strong>Date:</strong> <?php echo $booking['event_date']; ?></p>
                <p><strong>Time:</strong> <?php echo $booking['event_start_time'] . " - " . $booking['event_end_time']; ?></p>
                <p><strong>Venue:</strong> <?php echo $booking['venue_name']; ?></p>
                <p><strong>Number of Guests:</strong> <?php echo $booking['number_guests']; ?></p>
                <p><strong>Total Price:</strong> $<?php echo $totalPrice; ?></p>

                <h3>Payment Method</h3>
                </p>

                <label for="payment_method">Select Payment Method:</label>
                <select id="payment_method" name="payment_method">
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                </select><br><br>

                </select><br><br>


                <p>(Simulation: Enter any card details)</p>


                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="card_number"><br><br>
                <input type="submit" value="Simulate Payment">
            </form>

        </body>

        </html>

<?php
    } else {
        echo "Booking not found.";
    }
} else {
    echo "Invalid booking ID.";
}
?>