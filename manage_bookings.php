<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';

// Fetch booking data (including joined data from related tables)
$sql = "SELECT 
            b.booking_id,
            c.client_name,
            e.event_title,
            b.booking_date,
            b.number_guests,
            b.booking_status
        FROM booking AS b
        JOIN client AS c ON b.client_id = c.client_id
        JOIN event AS e ON b.event_id = e.event_id"; // Join with client and event tables

$result = $conn->query($sql);
$bookings = $result->fetch_all(MYSQLI_ASSOC);


$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Bookings</title>
</head>
<body>
    <h2>Manage Bookings</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Event</th>
                <th>Booking Date</th>
                <th>Guests</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($bookings) > 0): ?>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo $booking['booking_id']; ?></td>
                        <td><?php echo $booking['client_name']; ?></td>
                        <td><?php echo $booking['event_title']; ?></td>
                        <td><?php echo $booking['booking_date']; ?></td>
                        <td><?php echo $booking['number_guests']; ?></td>
                        <td><?php echo $booking['booking_status']; ?></td>
                        <td>
                            <a href="view_booking.php?id=<?php echo $booking['booking_id']; ?>">View</a> |
                            <a href="edit_booking.php?id=<?php echo $booking['booking_id']; ?>">Edit</a> |
                            <a href="process_booking.php?id=<?php echo $booking['booking_id']; ?>&action=approve" onclick="return confirm('Are you sure you want to approve this booking?')">Approve</a> |
                            <a href="process_booking.php?id=<?php echo $booking['booking_id']; ?>&action=reject" onclick="return confirm('Are you sure you want to reject this booking?')">Reject</a> |
                            <a href="delete_booking.php?id=<?php echo $booking['booking_id']; ?>" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</a>
                             |
                            <a href="cancel_booking.php?id=<?php echo $booking['booking_id']; ?>" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</a>




                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No bookings found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>