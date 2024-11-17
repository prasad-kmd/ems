<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: ../auth.html");
    exit;
}

require_once '../db_config.php';

$clientId = $_SESSION['client_id'];

// Fetch upcoming booked events
$stmt = $conn->prepare("
    SELECT 
        b.booking_id,
        e.event_title,
        e.event_date,
        e.event_start_time,
        e.event_end_time,
        v.venue_name,
        b.number_guests,
        b.booking_date,
        b.booking_status,
        p.payment_amount
    FROM booking b
    JOIN event e ON b.event_id = e.event_id
    JOIN venue v ON e.venue_id = v.venue_id
    LEFT JOIN payment p ON b.booking_id = p.booking_id
    WHERE b.client_id = ? AND e.event_date >= CURDATE() /* Filter for upcoming events */
    ORDER BY e.event_date ASC /* Order by event date */
");
$stmt->bind_param("i", $clientId);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);


$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Upcoming Bookings</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h2>Upcoming Booked Events</h2>

    <?php if (!empty($bookings)): ?>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Event</th>
                    <th>Event Date</th>
                    <th>Event Time</th>
                    <th>Venue</th>
                    <th>Guests</th>
                    <th>Booking Date</th>
                    <th>Status</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo $booking['booking_id']; ?></td>
                        <td><?php echo $booking['event_title']; ?></td>
                        <td><?php echo $booking['event_date']; ?></td>
                        <td><?php echo $booking['event_start_time'] . ' - ' . $booking['event_end_time']; ?></td>
                        <td><?php echo $booking['venue_name']; ?></td>
                        <td><?php echo $booking['number_guests']; ?></td>
                        <td><?php echo $booking['booking_date']; ?></td>
                        <td><?php echo $booking['booking_status']; ?></td>
                        <td><?php echo $booking['payment_amount'] ? '$' . $booking['payment_amount'] : 'N/A'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No upcoming bookings found.</p>
    <?php endif; ?>

</body>

</html>