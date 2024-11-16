<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}
require_once 'db_config.php';


//Fetch venues for the dropdown
$venuesStmt = $conn->prepare("SELECT venue_id, venue_name FROM venue");
$venuesStmt->execute();
$venuesStmt->bind_result($venueId, $venueName);
$venues = [];
while ($venuesStmt->fetch()) {
    $venues[$venueId] = $venueName;
}
$venuesStmt->close();
$conn->close();

?>



<!DOCTYPE html>
<html>
<head>
  <title>Add Event</title>
</head>
<body>
  <h2>Add New Event</h2>
  <form action="process_add_event.php" method="post" enctype="multipart/form-data">
    <label for="event_title">Title:</label>
    <input type="text" id="event_title" name="event_title" required><br><br>

    <label for="event_description">Description:</label>
    <textarea id="event_description" name="event_description"></textarea><br><br>


    <label for="event_photo">Photo:</label>
    <input type="file" id="event_photo" name="event_photo"><br><br>


    <label for="event_price">Price:</label>
    <input type="number" id="event_price" name="event_price" step="0.01" required><br><br>


    <label for="event_type">Type:</label>
    <select id="event_type" name="event_type">
      <option value="Conferences">Conferences</option>
      <option value="Seminars">Seminars</option>

      </select><br><br>


    <label for="event_date">Date:</label>
    <input type="date" id="event_date" name="event_date" required><br><br>

    <label for="event_start_time">Start Time:</label>
    <input type="time" id="event_start_time" name="event_start_time" required><br><br>


    <label for="event_end_time">End Time:</label>
    <input type="time" id="event_end_time" name="event_end_time" required><br><br>


    <label for="venue_id">Venue:</label>
    <select id="venue_id" name="venue_id" required>
        <?php foreach ($venues as $id => $name): ?>
            <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="event_capacity">Capacity:</label>
    <input type="number" id="event_capacity" name="event_capacity" required><br><br>


    <input type="submit" value="Add Event">
  </form>
</body>
</html>