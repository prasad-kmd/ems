<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

if (isset($_GET['id'])) {
    $bookingId = $_GET['id'];

    // Fetch booking details, including joined data from related tables
    $sql = "SELECT
                b.booking_id,
                c.client_name,
                c.client_email,
                c.client_phone,
                e.event_title,
                e.event_description,
                v.venue_name,
                b.booking_date,
                b.number_guests,
                b.booking_status,
                p.payment_amount,
                p.payment_status
            FROM booking AS b
            JOIN client AS c ON b.client_id = c.client_id
            JOIN event AS e ON b.event_id = e.event_id
            JOIN venue AS v ON e.venue_id = v.venue_id
            LEFT JOIN payment AS p ON b.booking_id = p.booking_id  /* Left join for payment, as it might not exist yet */
            WHERE b.booking_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();
?>
        <!DOCTYPE html>
        <html>

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>View Details of Booking ID: <?php echo $booking['booking_id']; ?></title>
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
                        View Selected Bookings
                    </a>
                    <!-- <a class="item">
                Jobs
            </a>
            <a class="item">
                Locations
            </a> -->
                    <div class="right menu">
                        <div class="item">
                            <a href="manage_bookings.php"><button class="ui right inverted orange labeled icon button">
                                    <i class="plus circle loading icon"></i>
                                    <span style="font-family: 'Sansumi';font-weight: 500;">Manage Bookings</span>
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
                <span class="item" style="font-family: Neuropol;">View Event Booking [<?php echo $booking['booking_id']; ?>] of <b><?php echo $booking['client_name']; ?></b></span>
            </div>
            <!-- Nav Bar ends -->

            <h4 class="ui horizontal divider header">
                <i class="bar chart icon"></i>
                Booking Details
            </h4>
            <table class="ui definition table">
                <tbody>
                    <tr>
                        <td class="two wide column">Booking ID</td>
                        <td><?php echo $booking['booking_id']; ?></td>
                    </tr>
                    <tr>
                        <td>Client Name</td>
                        <td><?php echo $booking['client_name']; ?></td>
                    </tr>
                    <tr>
                        <td>Client Email</td>
                        <td><?php echo $booking['client_email']; ?></td>
                    </tr>
                    <tr>
                        <td>Client Phone</td>
                        <td><?php echo $booking['client_phone']; ?></td>
                    </tr>
                    <tr>
                        <td>Event Title</td>
                        <td><?php echo $booking['event_title']; ?></td>
                    </tr>
                    <tr>
                        <td>Event Description</td>
                        <td><?php echo $booking['event_description']; ?></td>
                    </tr>
                    <tr>
                        <td>Venue Name</td>
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
                        <td>Booking Status</td>
                        <td><?php echo $booking['booking_status']; ?></td>
                    </tr>
                    <?php if ($booking['payment_amount']): ?>
                        <tr>
                            <td>Payment Amount</td>
                            <td>LKR <?php echo $booking['payment_amount']; ?></td>
                        </tr>
                        <tr>
                            <td>Payment Status</td>
                            <td><?php echo $booking['payment_status']; ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <script src="../assets/js/semantic.js"></script>
            <script src="../assets/js/jquery-3.7.1.min.js"></script>
        </body>

        </html>

<?php

        $stmt->close();
        $conn->close();
    } else {
        echo "<title>Booking Not Found - 204 No Content</title>
        <link rel=\"stylesheet\" href=\"../assets/css/semantic.css\">
    <link rel=\"stylesheet\" href=\"../assets/font/fonts.css\" />
        <div class=\"ui middle aligned center aligned grid\" style=\"height: 100vh; background-color: #f9f9f9;\">
        <div class=\"column\" style=\"max-width: 450px;\">
            <div class=\"ui segment\" style=\"background-color: #fff; padding: 3em;\">
                <div class=\"ui huge header\" style=\"font-family: 'Philosopher';\">Booking Not Found</div>
                <div class=\"ui divider\"></div>
                <div class=\"ui icon message\" style=\"background-color: #f9f9f9; border: none;\">
                    <i class=\"fas fa-calendar-times fa-5x\" style=\"color: #ddd;\"></i>
                    <div class=\"content\">
                        <div class=\"header\" style=\"color: #ff0000;\">Error 204: No Content</div>
                        <p>The booking you are looking for does not exist or is no longer available.</p>
                        <p>Please check your booking reference or try searching again.</p>
                        <div class=\"actions\">
                            <a href=\"manage_bookings.php\" class=\"ui primary button\">Return to Manage Bookings</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>";
    }
} else {
    echo "<title>Invalid Booking ID - Error 400</title>
    <link rel=\"stylesheet\" href=\"../assets/css/semantic.css\">
    <link rel=\"stylesheet\" href=\"../assets/font/fonts.css\" />
    <div class=\"ui middle aligned center aligned grid\" style=\"height: 100vh; background-color: #f9f9f9;\">
        <div class=\"column\" style=\"max-width: 450px;\">
          <div class=\"ui segment\" style=\"background-color: #fff; padding: 3em; box-shadow: 0 0 10px rgba(0,0,0,0.1);\">
            <div class=\"ui huge header\" style=\"color: #655CAA;font-family: 'Philosopher';\">Invalid Booking ID</div>
            <div class=\"ui divider\"></div>
            <div class=\"ui negative message\" style=\"border-radius: 0; box-shadow: none;\">
              <i class=\"fas fa-exclamation-triangle fa-3x\" style=\"float: left; margin: 0 1em 0 0; color: #D9534F;\"></i>
              <div class=\"header\" style=\"color: #767676;\">Error 400: Bad Request</div>
              <p>The booking ID you entered is invalid or does not exist in our system.</p>
              <p>Please double-check your booking ID and try again. Ensure it matches the one provided in your booking confirmation.</p>
              <div class=\"actions\">
                <a href=\"manage_bookings.php\" class=\"ui button\" style=\"margin-left: 0.5em;\">Return to Manage Bookings</a>
              </div>
            </div>
          </div>
        </div>
      </div>";
}
?>