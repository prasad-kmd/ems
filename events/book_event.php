<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: ../auth.html"); // Redirect to login if not logged in
    exit;
}

require_once '../db_config.php';

if (isset($_GET['event_id'])) {
    $eventId = $_GET['event_id'];

    // Fetch event and venue details
    $stmt = $conn->prepare("SELECT e.*, v.venue_name, v.venue_capacity FROM event e JOIN venue v ON e.venue_id = v.venue_id WHERE e.event_id = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();

        // Calculate available seats
        $bookedSeatsStmt = $conn->prepare("SELECT SUM(number_guests) AS total_booked FROM booking WHERE event_id = ? AND booking_status = 'confirmed'");
        $bookedSeatsStmt->bind_param("i", $eventId);
        $bookedSeatsStmt->execute();
        $bookedSeatsResult = $bookedSeatsStmt->get_result();
        $bookedSeatsData = $bookedSeatsResult->fetch_assoc();
        $bookedSeats = $bookedSeatsData['total_booked'] ?? 0;
        $availableSeats = $event['venue_capacity'] - $bookedSeats;


        // Close statements to avoid "Commands out of sync" errors
        $stmt->close();
        $bookedSeatsStmt->close();
        $conn->close();


        if ($availableSeats > 0) : ?>
            <!DOCTYPE html>
            <html>

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Browse Upcoming Events</title>
                <link rel="stylesheet" href="../assets/css/semantic.css" />
                <link rel="stylesheet" href="../assets/font/fonts.css" />
                <script src="../assets/js/jquery.min.js"></script>
                <script src="../assets/js/semantic.js"></script>
            </head>

            <body>
                <!-- Nav Bar -->
                <div class="ui inverted segment">
                    <div class="ui inverted secondary menu" style="font-family: 'Philosopher';">
                        <div class="item" width="50px">
                            <img src="../assets/images/logo.webp" alt="Company Logo" width="50px">
                        </div>
                        <a class="active item">
                            Book a Event
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
                    <span class="item" style="font-family: Neuropol;">Book Selected Event : <?php echo $event['event_title']; ?></span>
                </div>
                <!-- Nav Bar ends -->
                <!-- <?php if ($event['event_photo']): ?>
                    <img src="<?php echo $event['event_photo']; ?>" alt="<?php echo $event['event_title']; ?> Photo" class="event-photo">
                <?php endif; ?> -->
                <h2>Book Event: <?php echo $event['event_title']; ?></h2>

                <div class="ui placeholder segment">
                    <div class="ui two column very relaxed stackable grid">
                        <div class="column">
                            <?php if ($event['event_photo']): ?>
                                <img src="<?php echo $event['event_photo']; ?>" alt="<?php echo $event['event_title']; ?> Photo" class="ui fluid image">
                            <?php endif; ?>
                        </div>
                        <div class="middle aligned column">
                            <table class="ui definition table">
                                <tbody style="padding: 5px;">
                                    <tr>
                                        <td class="two wide column">Date</td>
                                        <td><?php echo $event['event_date']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Time</td>
                                        <td><?php echo $event['event_start_time'] . ' - ' . $event['event_end_time']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Venue</td>
                                        <td><?php echo $event['venue_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Price</td>
                                        <td>LKR <?php echo $event['event_price']; ?> per person</td>
                                    </tr>
                                    <tr>
                                        <td>Available Seats</td>
                                        <td><?php echo $availableSeats; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Number of Guests:</td>
                                        <td>
                                                <form class="ui form" style="padding: 25px;" method="POST" action="../bookings/process_booking.php">
                                                    <span style="font-family: 'Orbit';">
                                                    <input type="hidden" name="event_id" value="<?php echo $eventId; ?>">
                                                    <input type="hidden" name="client_id" value="<?php echo $_SESSION['client_id']; ?>">
                                                        <div class="two fluid fields">
                                                            <div class="required field">
                                                            <!-- <label for="number_guests">Number of Guests:</label> -->
                                                            <input type="number" id="number_guests" name="number_guests" min="1" max="<?php echo $availableSeats; ?>" value="1" required/>
                                                            </div>
                                                            <button class="ui fluid button" type="submit" style="font-family: 'Neuropol';">Book Now !</button>
                                                        </div>
                                                    </span>
                                                </form>
                                        </td>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="ui vertical divider">
                        &nbsp;
                    </div>
                </div>


            </body>

            </html>

        <?php else : ?>
            <p>This event is sold out.</p> <a href="browse_events.php">Back to Browse Events</a>

<?php endif; //End of available seats check


    } else {
        echo "<p>Event not found.</p>";
    }
} else {
    echo "<p>Invalid event ID.</p>";
}


?>