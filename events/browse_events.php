<?php
session_start();

require_once '../db_config.php';

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
                Browse Events
            </a>
            <!-- <a class="item">
                Jobs
            </a>
            <a class="item">
                Locations
            </a> -->
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
        <span class="item" style="font-family: Neuropol;">Upcoming Events</span>
    </div>
    <!-- Nav Bar ends -->
    <!-- Events Cards begins -->
    <div class="ui three column grid special cards" style="padding: 10px;">
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
                <div class="column">
                    <div class="ui fluid card">
                        <div class="blurring dimmable image">
                            <div class="ui dimmer">
                                <div class="content">
                                    <div class="center">
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
                                                <a href="book_event.php?event_id=<?php echo $event['event_id']; ?>">
                                                    <div class="ui inverted button">Book Event</div>
                                                </a>
                                            <?php else: ?>
                                                <div class="ui inverted red button" style="cursor: not-allowed;">Sold Out</div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <p>Please <a href="../auth.html">Sign In</a> or <a href="../auth.html">Sign Up</a> to book this event.</p>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                            <?php if ($event['event_photo']): ?>
                                <img src="<?php echo $event['event_photo']; ?>" alt="<?php echo $event['event_title']; ?> Photo" class="event-photo">
                            <?php endif; ?>
                        </div>
                        <div class="content">
                            <div class="header"><?php echo $event['event_title']; ?></div>
                        </div>
                        <div class="content">
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
                                        <td><?php echo $venues[$event['event_id']]['venue_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Price</td>
                                        <td>LKR <?php echo $event['event_price']; ?> per person</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="meta">
                                                <i class="sync loading icon"></i>
                                                <span class="date">Updated at <?php echo $event['event_updated_at']; ?></span>
                                            </div>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="extra content">
                            <a>
                                <i class="circle notch loading icon"></i>
                                <?php echo $event['event_description']; ?>
                            </a>
                        </div>
                        <div class="extra content">
                            <button class="ui fluid disabled button">Share This Event</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="column">
                <div class="ui fluid green card">
                    <div class="blurring dimmable image">
                        <div class="ui dimmer">
                            <div class="content">
                                <div class="center">
                                    <p><strong>Content Unavailable</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="ui placeholder">
                            <div class="image"></div>
                        </div>
                    </div>
                    <div class="content">
                        <div class="ui placeholder">
                            <div class="header">
                                <div class="line"></div>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                    <div class="content">
                        <div class="ui fluid placeholder">
                            <div class="header">
                                <div class="line"></div>
                                <div class="line"></div>
                            </div>
                            <div class="paragraph">
                                <div class="line"></div>
                                <div class="line"></div>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                    <div class="extra content">
                        <div class="ui placeholder">
                            <div class="header">
                                <div class="line"></div>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                    <div class="extra content">
                        <button class="ui fluid disabled button">Currently Not Available</button>
                    </div>
                </div>
            </div>
            <!-- 1 -->
            <div class="column">
                <div class="ui fluid red card">
                    <div class="blurring dimmable image">
                        <div class="ui dimmer">
                            <div class="content">
                                <div class="center">
                                    <p><strong>Content Unavailable</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="ui placeholder">
                            <div class="image"></div>
                        </div>
                    </div>
                    <div class="content">
                        <div class="ui placeholder">
                            <div class="header">
                                <div class="line"></div>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                    <div class="content">
                        <div class="ui fluid placeholder">
                            <div class="header">
                                <div class="line"></div>
                                <div class="line"></div>
                            </div>
                            <div class="paragraph">
                                <div class="line"></div>
                                <div class="line"></div>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                    <div class="extra content">
                        <div class="ui placeholder">
                            <div class="header">
                                <div class="line"></div>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                    <div class="extra content">
                        <button class="ui fluid disabled button">Currently Not Available</button>
                    </div>
                </div>
            </div>
            <!-- 2 -->
            <div class="column">
                <div class="ui fluid yellow card">
                    <div class="blurring dimmable image">
                        <div class="ui dimmer">
                            <div class="content">
                                <div class="center">
                                    <p><strong>Content Unavailable</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="ui placeholder">
                            <div class="image"></div>
                        </div>
                    </div>
                    <div class="content">
                        <div class="ui placeholder">
                            <div class="header">
                                <div class="line"></div>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                    <div class="content">
                        <div class="ui fluid placeholder">
                            <div class="header">
                                <div class="line"></div>
                                <div class="line"></div>
                            </div>
                            <div class="paragraph">
                                <div class="line"></div>
                                <div class="line"></div>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                    <div class="extra content">
                        <div class="ui placeholder">
                            <div class="header">
                                <div class="line"></div>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                    <div class="extra content">
                        <button class="ui fluid disabled button">Currently Not Available</button>
                    </div>
                </div>
            </div>
            <!-- 3 -->
        <?php endif; ?>
    </div>

    <!-- events cards ends -->
    <script>
        $('.special.cards .image').dimmer({
            on: 'hover'
        });
    </script>



</body>

</html>


<?php
// Close the connection *after* the loop
$conn->close();
?>