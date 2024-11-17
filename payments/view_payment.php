<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

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
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>View Details of Payment ID: <?php echo $payment['payment_id']; ?></title>
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
                        View Selected Payments
                    </a>
                    <!-- <a class="item">
                Jobs
            </a>
            <a class="item">
                Locations
            </a> -->
                    <div class="right menu">
                        <div class="item">
                            <a href="manage_payments.php"><button class="ui right inverted orange labeled icon button">
                                    <i class="plus circle loading icon"></i>
                                    <span style="font-family: 'Sansumi';font-weight: 500;">Manage Payments</span>
                                </button></a>
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
                <span class="item" style="font-family: Neuropol;">View Client Payments [<?php echo $payment['payment_id']; ?>] by <b><?php echo $payment['client_name']; ?></b></span>
            </div>
            <!-- Nav Bar ends -->
            <h4 class="ui horizontal divider header">
                <i class="gem icon"></i>
                Payment Details
            </h4>
            <table class="ui definition table">
                <tbody style="padding: 5px;">
                    <tr>
                        <td class="two wide column">Payment ID</td>
                        <td><?php echo $payment['payment_id']; ?></td>
                    </tr>
                    <tr>
                        <td>Client Name</td>
                        <td><?php echo $payment['client_name']; ?></td>
                    </tr>
                    <tr>
                        <td>Event</td>
                        <td><?php echo $payment['event_title']; ?></td>
                    </tr>
                    <tr>
                        <td>Booking Date</td>
                        <td><?php echo $payment['booking_date']; ?></td>
                    </tr>
                    <tr>
                        <td>Number of Guests</td>
                        <td><?php echo $payment['number_guests']; ?></td>
                    </tr>
                    <tr>
                        <td>Payment Method</td>
                        <td><?php echo $payment['payment_method']; ?></td>
                    </tr>
                    <tr>
                        <td>Payment Amount</td>
                        <td>LKR <?php echo $payment['payment_amount']; ?></td>
                    </tr>
                    <tr>
                        <td>Payment Date</td>
                        <td><?php echo $payment['payment_date']; ?></td>
                    </tr>
                    <tr>
                        <td>Payment Time</td>
                        <td><?php echo $payment['payment_time']; ?></td>
                    </tr>
                    <tr>
                        <td>Payment Status</td>
                        <td><?php echo $payment['payment_status']; ?></td>
                    </tr>
                    <tr>
                        <td>Transaction ID</td>
                        <td><?php echo $payment['payment_transaction_id']; ?></td>
                    </tr>

                </tbody>
            </table>
            <script src="../assets/js/semantic.js"></script>
            <script src="../assets/js/jquery-3.7.1.min.js"></script>
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