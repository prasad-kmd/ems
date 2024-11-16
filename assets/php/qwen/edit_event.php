<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit();
}
include 'db_connect.php';

$staff_id = $_SESSION['staff_id'];
$staff_role = $_SESSION['staff_role'];

if ($staff_role == 'Manager' || $staff_role == 'Event Organizer' || $staff_role == 'System Administrator') {
    $event_id = $_GET['event_id'];
    $sql = "SELECT * FROM event WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
} else {
    echo "You do not have permission to access this page.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Edit Event</h1>
        <p>Welcome, <?php echo $_SESSION['staff_name']; ?>!</p>
        <a href="manage_events.php">Back to Events</a>
        <a href="admin_dashboard.php">Back to Dashboard</a>
        <a href="admin_logout.php">Log Out</a>
    </header>
    <main>
        <form action="edit_event_process.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo $event['event_title']; ?>" required>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo $event['event_description']; ?></textarea>
            <label for="photo">Photo:</label>
            <input type="file" id="photo" name="photo">
            <img src="uploads/<?php echo $event['event_photo']; ?>" alt="Event Photo" width="100">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" value="<?php echo $event['event_date']; ?>" required>
            <label for="start_time">Start Time:</label>
            <input type="time" id="start_time" name="start_time" value="<?php echo $event['event_start_time']; ?>" required>
            <label for="end_time">End Time:</label>
            <input type="time" id="end_time" name="end_time" value="<?php echo $event['event_end_time']; ?>" required>
            <label for="venue_id">Venue:</label>
            <select id="venue_id" name="venue_id" required>
                <?php
                $sql = "SELECT venue_id, venue_name FROM venue";
                $venues = $conn->query($sql);
                if ($venues->num_rows > 0) {
                    while ($venue = $venues->fetch_assoc()) {
                        echo "<option value='" . $venue['venue_id'] . "' " . ($venue['venue_id'] == $event['venue_id'] ? 'selected' : '') . ">" . $venue['venue_name'] . "</option>";
                    }
                } else {
                    echo "<option value='0' disabled>No venues available</option>";
                }
                ?>
            </select>
            <label for="capacity">Capacity:</label>
            <input type="number" id="capacity" name="capacity" value="<?php echo $event['event_capacity']; ?>" required>
            <label for="status">Status:</label>
            <select id="status" name="event_status" required>
                <option value="upcoming" <?php echo $event['event_status'] == 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
                <option value="ongoing" <?php echo $event['event_status'] == 'ongoing' ? 'selected' : ''; ?>>Ongoing</option>
                <option value="completed" <?php echo $event['event_status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
            </select>
            <button type="submit">Update Event</button>
        </form>
    </main>
</body>
</html>