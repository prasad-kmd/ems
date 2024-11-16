<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';

if (isset($_GET['id'])) {
    $paymentId = $_GET['id'];

    $sql = "SELECT 
                p.*,  -- All payment details
                c.client_name,
                e.event_title,
                b.booking_date,
                b.number_guests
            FROM payment AS p
            JOIN booking AS b ON p.booking_id = b.booking_id
            JOIN client AS c ON b.client_id = c.client_id
            JOIN event AS e ON b.event_id = e.event_id
            WHERE p.payment_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $paymentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $payment = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Payment</title>
</head>
<body>
    <h2>Payment Details</h2>

    <p><strong>Payment ID:</strong> <?php echo $payment['payment_id']; ?></p>
    <p><strong>Client Name:</strong> <?php echo $payment['client_name']; ?></p>
    <p><strong>Event:</strong> <?php echo $payment['event_title']; ?></p>
    <p><strong>Booking Date:</strong> <?php echo $payment['booking_date']; ?></p>
    <p><strong>Number of Guests:</strong> <?php echo $payment['number_guests']; ?></p>
    <p><strong>Payment Method:</strong> <?php echo $payment['payment_method']; ?></p>
    <p><strong>Payment Amount:</strong> <?php echo $payment['payment_amount']; ?></p>
    <p><strong>Payment Date:</strong> <?php echo $payment['payment_date']; ?></p>
    <p><strong>Payment Time:</strong> <?php echo $payment['payment_time']; ?></p>
    <p><strong>Payment Status:</strong> <?php echo $payment['payment_status']; ?></p>
    <p><strong>Transaction ID:</strong> <?php echo $payment['payment_transaction_id']; ?></p>
    <!-- Display other payment details as needed -->


    <a href="manage_payments.php">Back to Manage Payments</a>

</body>
</html>


<?php
    } else {
        echo "Payment not found.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid payment ID.";
}


?>