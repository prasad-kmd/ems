<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

if (isset($_GET['id'])) {
    $eventId = $_GET['id'];

    // Fetch existing event details
    $stmt = $conn->prepare("SELECT * FROM event WHERE event_id = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();

        // Fetch venues for dropdown
        $venuesStmt = $conn->prepare("SELECT venue_id, venue_name FROM venue");
        $venuesStmt->execute();
        $venuesStmt->bind_result($venueId, $venueName);
        $venues = [];
        while ($venuesStmt->fetch()) {
            $venues[$venueId] = $venueName;
        }
        $venuesStmt->close();

        $conn->close(); // Close connection here


?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Event</title>
            <link rel="stylesheet" href="../assets/css/semantic.css">
            <link rel="stylesheet" href="../assets/font/fonts.css" />
        </head>

        <body>
            <h2>Edit Event</h2>
            <form action="process_edit_event.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">

                <label for="event_title">Title:</label>
                <input type="text" id="event_title" name="event_title" value="<?php echo $event['event_title']; ?>" required><br><br>

                <label for="event_description">Description:</label>
                <textarea id="event_description" name="event_description"><?php echo $event['event_description']; ?></textarea><br><br>

                <label for="event_photo">Photo:</label>
                <img src="<?php echo $event['event_photo']; ?>" alt="Current Photo" width="100"><br> Current Photo<br>
                <input type="file" id="event_photo" name="event_photo"><br><br>
                <input type="hidden" name="existing_photo" value="<?php echo $event['event_photo']; ?>">



                <label for="event_price">Price:</label>
                <input type="number" id="event_price" name="event_price" value="<?php echo $event['event_price']; ?>" step="0.01" required><br><br>



                <label for="event_type">Type:</label>
                <select id="event_type" name="event_type">

                </select><br><br>

                <label for="event_date">Date:</label>
                <input type="date" id="event_date" name="event_date" value="<?php echo $event['event_date']; ?>" required><br><br>


                <label for="event_start_time">Start Time:</label>
                <input type="time" id="event_start_time" name="event_start_time" value="<?php echo $event['event_start_time']; ?>" required><br><br>

                <label for="event_end_time">End Time:</label>
                <input type="time" id="event_end_time" name="event_end_time" value="<?php echo $event['event_end_time']; ?>" required><br><br>


                <label for="venue_id">Venue:</label>
                <select id="venue_id" name="venue_id" required>
                    <?php foreach ($venues as $id => $name): ?>
                        <option value="<?php echo $id; ?>" <?php if ($event['venue_id'] == $id) echo "selected"; ?>><?php echo $name; ?></option>
                    <?php endforeach; ?>
                </select><br><br>

                <label for="event_capacity">Capacity:</label>
                <input type="number" id="event_capacity" name="event_capacity" value="<?php echo $event['event_capacity']; ?>" required><br><br>


                <input type="submit" value="Update Event">
            </form>
            <script src="../assets/js/semantic.js"></script>
            <script src="../assets/js/jquery-3.7.1.min.js"></script>
        </body>

        </html>


<?php


    } else {
        echo "Event not found.";
    }
} else {
    echo "Invalid event ID.";
}


?>