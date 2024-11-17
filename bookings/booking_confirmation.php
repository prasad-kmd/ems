<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: ../auth.html"); // Or your login page
    exit;
}

require_once '../db_config.php';


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
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Confirmation of Booking [<?php echo $booking['booking_id']; ?>]</title>
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
                            Confirmation
                        </a>
                        <div class="right menu">
                            <div class="item">
                                <a href="../client_dashboard.php"><button class="ui right inverted teal labeled icon button">
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
                    <span class="item" style="font-family: Neuropol;">Your Payment for <b><?php echo $booking['event_title']; ?></b> has been Confirmed !</span>
                </div>
                <!-- Nav Bar ends -->
                <h4 class="ui horizontal divider header">
                    <i class="gem icon"></i>
                    Thank you for your booking!
                </h4>
                <table class="ui definition table">
                    <tbody style="padding: 5px;">
                        <tr>
                            <td class="two wide column">Booking ID</td>
                            <td><?php echo $booking['booking_id']; ?></td>
                        </tr>
                        <tr>
                            <td>Event</td>
                            <td><?php echo $booking['event_title']; ?></td>
                        </tr>
                        <tr>
                            <td>Event Date</td>
                            <td><?php echo $booking['event_date']; ?></td>
                        </tr>
                        <tr>
                            <td>Event Time</td>
                            <td><?php echo $booking['event_start_time'] . ' - ' . $booking['event_end_time']; ?></td>
                        </tr>
                        <tr>
                            <td>Venue</td>
                            <td><?php echo $booking['venue_name']; ?></td>
                        </tr>
                        <tr>
                            <td>Booking Date</td>
                            <td><?php echo $booking['booking_date']; ?></td>
                        </tr>
                        <tr>
                            <td>Number of Guests</td>
                            <td><?php echo $booking['number_guests']; ?></td>
                        </tr>
                        <tr>
                            <td>Total Amount</td>
                            <td>LKR <?php echo $booking['payment_amount']; ?></td>
                        </tr>
                        <tr>
                            <td>Transaction ID</td>
                            <td><?php echo $booking['payment_transaction_id']; ?></td>
                        </tr>
                    </tbody>
                </table>
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