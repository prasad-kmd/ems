<?php
session_start();

require_once 'db_config.php';

// Fetch events from the database (only upcoming events)
$stmt = $conn->prepare("SELECT * FROM event WHERE event_status = 'Upcoming'");
$stmt->execute();
$result = $stmt->get_result();
$events = $result->fetch_all(MYSQLI_ASSOC);

// Fetch venues for each event
$venues = [];
foreach ($events as $event) {
    $venueStmt = $conn->prepare("SELECT * FROM venue WHERE venue_id = ?");
    $venueStmt->bind_param("i", $event['venue_id']);
    $venueStmt->execute();
    $venueResult = $venueStmt->get_result();
    $venues[$event['event_id']] = $venueResult->fetch_assoc();
    $venueStmt->close();
}


$stmt->close();


?>

<!DOCTYPE html>
<html>

<head>
    <title>Browse Events</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .events-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            /* Responsive grid */
            grid-gap: 20px;
        }

        .event {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }

        .event-photo {
            max-width: 100%;
            /* Prevent image from overflowing */
            height: auto;
            display: block;
            /* Prevents extra space below the image */
            margin-bottom: 10px;
        }

        /* ... other styles ... */
    </style>
</head>

<body>
    <h2>Upcoming Events</h2>

    <div class="events-container">
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
                <div class="event">
                    <?php if ($event['event_photo']): ?>
                        <img src="<?php echo $event['event_photo']; ?>" alt="<?php echo $event['event_title']; ?> Photo" class="event-photo">
                    <?php endif; ?>

                    <h3><?php echo $event['event_title']; ?></h3>
                    <p><strong>Date:</strong> <?php echo $event['event_date']; ?></p>
                    <p><strong>Time:</strong> <?php echo $event['event_start_time'] . ' - ' . $event['event_end_time']; ?></p>
                    <p><strong>Venue:</strong> <?php echo $venues[$event['event_id']]['venue_name']; ?></p>
                    <p><strong>Price:</strong> $<?php echo $event['event_price']; ?> per person</p>

                    <p><strong>Available Seats:</strong>
                        <?php
                        $eventId = $event['event_id'];
                        $eventCapacity = $event['event_capacity'];

                        $bookedSeatsStmt = $conn->prepare("SELECT SUM(number_guests) AS total_booked FROM booking WHERE event_id = ? AND booking_status = 'confirmed'");
                        $bookedSeatsStmt->bind_param("i", $eventId);
                        $bookedSeatsStmt->execute();
                        $bookedSeatsResult = $bookedSeatsStmt->get_result();
                        $bookedSeatsData = $bookedSeatsResult->fetch_assoc();
                        $bookedSeats = $bookedSeatsData['total_booked'] ?? 0;

                        $availableSeats = $eventCapacity - $bookedSeats;

                        echo $availableSeats > 0 ? $availableSeats : "Sold Out";
                        $bookedSeatsStmt->close();
                        ?>
                    </p>

                    <?php if (isset($_SESSION['client_id'])): ?>
                        <?php if ($availableSeats > 0): ?>
                            <a href="book_event.php?event_id=<?php echo $event['event_id']; ?>">Book Now</a>
                        <?php else: ?>
                            <p class="sold-out">Sold Out</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p>Please <a href="auth.php">Sign In</a> or <a href="auth.php">Sign Up</a> to book this event.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No upcoming events found.</p>
        <?php endif; ?>
    </div>


</body>

</html>


<?php
// Close the connection *after* the loop
$conn->close();
?>