<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

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

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Details of Booking ID: <?php echo $booking['booking_id']; ?></title>
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
                        Edit Selected Bookings
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
                <span class="item" style="font-family: Neuropol;">Edit Event Booking [<?php echo $booking['booking_id']; ?>]</span>
            </div>
            <!-- Nav Bar ends -->
            <!-- form begins -->
            <form action="process_edit_booking.php" method="post">
                <div class="ui equal width form" style="padding: 25px;">
                    <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                    <div class="fields">
                        <div class="required field">
                            <label for="number_guests">Number of Guests</label>
                            <input type="number" id="number_guests" name="number_guests" value="<?php echo $booking['number_guests']; ?>" required>
                        </div>
                        <div class="required field">
                            <label for="booking_status">Status</label>
                            <select id="booking_status" name="booking_status">
                                <option value="pending" <?php if ($booking['booking_status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                <option value="confirmed" <?php if ($booking['booking_status'] == 'confirmed') echo 'selected'; ?>>Confirmed</option>
                                <option value="cancelled" <?php if ($booking['booking_status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <button class="ui fluid  button" type="submit" style="font-family: 'Neuropol';">Update Details
                    </button>
                </div>
            </form>
            <!-- form ends -->
            <script src="../assets/js/semantic.js"></script>
            <script src="../assets/js/jquery-3.7.1.min.js"></script>
        </body>

        </html>

<?php
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
    $stmt->close();
    $conn->close();
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