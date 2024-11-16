<?php
session_start();
include 'db_connection.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];
$admin_role = $_SESSION['admin_role'];

// Fetch all events
$stmt = $pdo->query("SELECT e.*, v.venue_name, s.staff_name 
                     FROM event e 
                     LEFT JOIN venue v ON e.venue_id = v.venue_id 
                     LEFT JOIN staff s ON e.staff_id = s.staff_id 
                     ORDER BY e.event_date DESC");
$events = $stmt->fetchAll();

// Fetch all venues for the dropdown
$venues = $pdo->query("SELECT * FROM venue")->fetchAll();

// Fetch all staff for the dropdown
$staff = $pdo->query("SELECT * FROM staff")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select {
            width: 100%;
            padding: 5px;
        }
        button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Events</h1>
        <h2>Add New Event</h2>
        <form action="add_event.php" method="POST">
            <div class="form-group">
                <label for="event_title">Event Title:</label>
                <input type="text" id="event_title" name="event_title" required>
            </div>
            <div class="form-group">
                <label for="event_description">Description:</label>
                <textarea id="event_description" name="event_description" required></textarea>
            </div>
            <div class="form-group">
                <label for="event_date">Date:</label>
                <input type="date" id="event_date" name="event_date" required>
            </div>
            <div class="form-group">
                <label for="event_start_time">Start Time:</label>
                <input type="time" id="event_start_time" name="event_start_time" required>
            </div>
            <div class="form-group">
                <label for="event_endtime">End Time:</label>
                <input type="time" id="event_endtime" name="event_endtime" required>
            </div>
            <div class="form-group">
                <label for="venue_id">Venue:</label>
                <select id="venue_id" name="venue_id" required>
                    <?php foreach ($venues as $venue): ?>
                        <option value="<?php echo $venue['venue_id']; ?>"><?php echo htmlspecialchars($venue['venue_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="event_capacity">Capacity:</label>
                <input type="number" id="event_capacity" name="event_capacity" required>
            </div>
            <div class="form-group">
                <label for="event_price">Price:</label>
                <input type="number" id="event_price" name="event_price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="staff_id">Assigned Staff:</label>
                <select id="staff_id" name="staff_id">
                    <option value="">None</option>
                    <?php foreach ($staff as $s): ?>
                        <option value="<?php echo $s['staff_id']; ?>"><?php echo htmlspecialchars($s['staff_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Add Event</button>
        </form>

        <h2>Existing Events</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Capacity</th>
                    <th>Price</th>
                    <th>Assigned Staff</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event['event_title']); ?></td>
                        <td><?php echo $event['event_date']; ?></td>
                        <td><?php echo htmlspecialchars($event['venue_name']); ?></td>
                        <td><?php echo $event['event_capacity']; ?></td>
                        <td>$<?php echo number_format($event['event_price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($event['staff_name'] ?? 'Not assigned'); ?></td>
                        <td>
                            <a href="edit_event.php?id=<?php echo $event['event_id']; ?>">Edit</a>
                            <a href="delete_event.php?id=<?php echo $event['event_id']; ?>" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>