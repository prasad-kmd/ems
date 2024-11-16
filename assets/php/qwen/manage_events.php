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
    $sql = "SELECT * FROM event";
    $result = $conn->query($sql);
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
    <title>Manage Events</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>Manage Events</h1>
        <p>Welcome, <?php echo $_SESSION['staff_name']; ?>!</p>
        <a href="admin_dashboard.php">Back to Dashboard</a>
        <a href="admin_logout.php">Log Out</a>
    </header>
    <main>
        <h2>Events</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Photo</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Venue</th>
                <th>Capacity</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['event_id'] . "</td>";
                    echo "<td>" . $row['event_title'] . "</td>";
                    echo "<td>" . $row['event_description'] . "</td>";
                    echo "<td><img src='uploads/" . $row['event_photo'] . "' alt='Event Photo' width='50'></td>";
                    echo "<td>" . $row['event_date'] . "</td>";
                    echo "<td>" . $row['event_start_time'] . "</td>";
                    echo "<td>" . $row['event_end_time'] . "</td>";
                    echo "<td>" . $row['venue_name'] . "</td>";
                    echo "<td>" . $row['event_capacity'] . "</td>";
                    echo "<td>" . $row['event_status'] . "</td>";
                    echo "<td>";
                    echo "<a href='edit_event.php?event_id=" . $row['event_id'] . "'>Edit</a> | ";
                    echo "<a href='delete_event.php?event_id=" . $row['event_id'] . "'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No events found.</td></tr>";
            }
            ?>
        </table>
        <h2>Add New Event</h2>
        <form action="add_event_process.php" method="post" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
            <label for="photo">Photo:</label>
            <input type="file" id="photo" name="photo">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>
            <label for="start_time">Start Time:</label>
            <input type="time" id="start_time" name="start_time" required>
            <label for="end_time">End Time:</label>
            <input type="time" id="end_time" name="end_time" required>
            <label for="venue">Venue:</label>
            <select id="venue" name="venue_id" name="venue_id" required>
                <?php
                $sql = "SELECT venue_id, venue_name, venue_id FROM venue";
                $venues = $conn->query($sql);
                if ($venues->num_rows > 0) {
                    while ($venue = fetch_assoc()) {
                        echo "<option value=" . $venue['venue_id'] . '" ' . $venue['venue_name'] . '">' . $venue['venue_name'] . '</option>';
                    }
                } else {
                    echo "<option value='0' disabled>No venues available</option>";
                }
                ?>
            </select>
            <label for="capacity">Capacity:</label>
            <input type="number" id="capacity" name="capacity" required>
            <label for="type">Status:</label>
            <select id="status" name="event_status" name="event_status" required>
                <option value="upcoming" selected>Upcoming</option>
                <option value="ongoing">Ongoing</option>
                <option value="completed">Completed</option>
            </select>
            <button type="submit">Add Event</button>
        </form>
    </main>
</body>

</html>