<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';


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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payments</title>
    <link rel="stylesheet" href="../assets/css/semantic.css" />
    <link rel="stylesheet" href="../assets/font/fonts.css" />
</head>

<body>
    <!-- Nav Bar -->
    <div class="ui inverted segment">
        <div class="ui inverted secondary menu" style="font-family: 'Philosopher';">
            <div class="item" width="50px">
                <img src="../assets/images/logo.webp" alt="Company Logo" width="50px">
            </div>
            <a class="active item">
                Manage Payments
            </a>
            <!-- <a class="item">
                Jobs
            </a>
            <a class="item">
                Locations
            </a> -->
            <div class="right menu">
                <div class="item">
                    <a href="../admin_dashboard.php"><button class="ui right inverted teal labeled icon button">
                            <i class="asterisk loading icon"></i>
                            <span style="font-family: 'Sansumi';font-weight: 500;">Back to Dashboard</span>
                        </button></a>
                    <a href="../logout.php"><button class="ui right inverted secondary labeled icon button">
                            <i class="sign out alternate icon"></i>
                            <span style="font-family: 'Sansumi';font-weight: 500;">Log out</span>
                        </button></a>
                    <!-- &nbsp; -->
                </div>
            </div>
        </div>
    </div>
    <div class="ui fluid vertical menu" style="padding: 5px;">
        <span class="item" style="font-family: Neuropol;">Manage Current Payments</span>
    </div>
    <!-- Nav Bar ends -->
    <!-- table begins -->
    <table class="ui celled striped compact padded blue table">
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
            <?php if (isset($payments) && count($payments) > 0): ?> <!--check if $payments is set and not empty -->
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
                            <div class="ui animated button" tabindex="0"><a href="view_payment.php?id=<?php echo $payment['payment_id']; ?>">
                                    <div class="visible content">View</div>
                                    <div class="hidden content">
                                        <i class="eye icon"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="ui vertical animated button" tabindex="0"><a href="delete_payment.php?id=<?php echo $payment['payment_id']; ?>" onclick="return confirm('Are you sure?')">
                                    <div class="visible content">Delete</div>
                                    <div class="hidden content">
                                        <i class="archive icon"></i>
                                    </div>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No payments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- table ends -->

    <!-- <a href="edit_payment.php?id=<?php echo $payment['payment_id']; ?>">Edit</a> |
    <a href="process_payment.php?id=<?php echo $payment['payment_id']; ?>&action=approve" onclick="return confirm('Are you sure?')">Approve</a> |
    <a href="process_payment.php?id=<?php echo $payment['payment_id']; ?>&action=reject" onclick="return confirm('Are you sure?')">Reject</a> |
    <a href="refund_payment.php?id=<?php echo $payment['payment_id']; ?>" onclick="return confirm('Are you sure?')">Refund</a> -->
    <script src="../assets/js/semantic.js"></script>
    <script src="../assets/js/jquery-3.7.1.min.js"></script>
</body>

</html>