<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';

if (isset($_GET['id'])) {
    $bookingId = $_GET['id'];

    // Fetch the existing booking data (you'll likely want to join with client and event tables here)
    $stmt = $conn->prepare("SELECT * FROM booking WHERE booking_id = ?");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Booking</title>
</head>
<body>
    <h2>Edit Booking</h2>
    <form action="process_edit_booking.php" method="post">
        <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">


        <label for="number_guests">Number of Guests:</label>
        <input type="number" id="number_guests" name="number_guests" value="<?php echo $booking['number_guests']; ?>" required><br><br>

        <label for="booking_status">Status:</label>
        <select id="booking_status" name="booking_status">
            <option value="pending" <?php if ($booking['booking_status'] == 'pending') echo 'selected'; ?>>Pending</option>
            <option value="confirmed" <?php if ($booking['booking_status'] == 'confirmed') echo 'selected'; ?>>Confirmed</option>
            <option value="cancelled" <?php if ($booking['booking_status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option>
        </select><br><br>



        <input type="submit" value="Update Booking">
    </form>
</body>
</html>

<?php
    } else {
        echo "Booking not found.";
    }
    $stmt->close();
    $conn->close();

} else {
    echo "Invalid booking ID.";
}
?>