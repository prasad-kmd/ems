<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';


$sql = "SELECT 
            p.payment_id,
            c.client_name,
            e.event_title,
            p.payment_amount,
            p.payment_method,
            p.payment_status,
            p.payment_date
        FROM payment AS p
        JOIN booking AS b ON p.booking_id = b.booking_id
        JOIN client AS c ON b.client_id = c.client_id
        JOIN event AS e ON b.event_id = e.event_id";


$result = $conn->query($sql);


if ($result) {
  $payments = $result->fetch_all(MYSQLI_ASSOC);
} else {
    //Handle the error
    echo "Database error: " . $conn->error;
}

$conn->close();


?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Payments</title>
</head>
<body>
    <h2>Manage Payments</h2>

    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Client</th>
                <th>Event</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th> 
            </tr>
        </thead>
        <tbody>
          <?php if (isset($payments) && count($payments) > 0): ?>  <!--check if $payments is set and not empty -->
              <?php foreach ($payments as $payment): ?>
                  <tr>
                      <td><?php echo $payment['payment_id']; ?></td>
                      <td><?php echo $payment['client_name']; ?></td>
                      <td><?php echo $payment['event_title']; ?></td>
                      <td><?php echo $payment['payment_amount']; ?></td>
                      <td><?php echo $payment['payment_method']; ?></td>
                      <td><?php echo $payment['payment_status']; ?></td>
                      <td><?php echo $payment['payment_date']; ?></td>
                      <td>
                          <a href="view_payment.php?id=<?php echo $payment['payment_id']; ?>">View</a> |
                          <a href="edit_payment.php?id=<?php echo $payment['payment_id']; ?>">Edit</a> |
                           <a href="process_payment.php?id=<?php echo $payment['payment_id']; ?>&action=approve" onclick="return confirm('Are you sure?')">Approve</a> |
                           <a href="process_payment.php?id=<?php echo $payment['payment_id']; ?>&action=reject" onclick="return confirm('Are you sure?')">Reject</a> |
                           <a href="refund_payment.php?id=<?php echo $payment['payment_id']; ?>" onclick="return confirm('Are you sure?')">Refund</a> |
                           <a href="delete_payment.php?id=<?php echo $payment['payment_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>

                      </td>
                  </tr>
              <?php endforeach; ?>
          <?php else: ?>
                <tr><td colspan="8">No payments found.</td></tr>  
          <?php endif; ?>


        </tbody>
    </table>
</body>
</html>