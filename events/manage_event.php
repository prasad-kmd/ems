<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

//Fetch venues for the dropdown
$venuesStmt = $conn->prepare("SELECT venue_id, venue_name FROM venue");
$venuesStmt->execute();
$venuesStmt->bind_result($venueId, $venueName);
$venues = [];
while ($venuesStmt->fetch()) {
    $venues[$venueId] = $venueName;
}
$venuesStmt->close();



?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="assets/css/semantic.css">
    <link rel="stylesheet" href="assets/font/fonts.css" />
</head>

<body>
    <h2>Manage Events</h2>

    <h3>Add New Event</h3>
    <form action="process_add_event.php" method="post" enctype="multipart/form-data" id="add-event-form">
    </form>
    <hr>

    <h3>Existing Events</h3>

    <?php
    $eventsStmt = $conn->prepare("SELECT event_id, event_title, event_date, event_status FROM event");
    $eventsStmt->execute();
    $eventsStmt->bind_result($eventId, $eventTitle, $eventDate, $eventStatus);
    ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($eventsStmt->fetch()): ?>
                <tr>
                    <td><?php echo $eventId; ?></td> <!-- Display Event ID -->
                    <td><?php echo $eventTitle; ?></td> <!-- Display Event Title -->
                    <td><?php echo $eventDate; ?></td> <!-- Display Event Date -->
                    <td><?php echo $eventStatus; ?></td> <!-- Display Event Status -->
                    <td>
                        <a href="edit_event.php?id=<?php echo $eventId; ?>">Edit</a> |
                        <a href="delete_event.php?id=<?php echo $eventId; ?>" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php
    $eventsStmt->close();
    $conn->close();
    ?>
</body>

</html>