<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: auth.html");
    exit();
}

$client_id = $_SESSION['client_id'];
$sql = "SELECT * FROM client WHERE client_id = ?";
$stmt = getDbConnection()->prepare($sql);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();

$stmt->close();
getDbConnection()->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="profile">
            <img src="<?php echo $client['client_photo']; ?>" alt="Profile Picture" class="profile-picture">
            <h1>Welcome, <?php echo $client['client_name']; ?>!</h1>
        </div>
        <div class="dashboard">
            <div class="card">
                <h2>Book Events</h2>
                <a href="book_events.php">Book an Event</a>
            </div>
            <div class="card">
                <h2>Manage Booked Events</h2>
                <a href="manage_booked_events.php">Manage Bookings</a>
            </div>
            <div class="card">
                <h2>Booking History</h2>
                <a href="booking_history.php">View History</a>
            </div>
            <div class="card">
                <h2>Upcoming Booked Events</h2>
                <a href="upcoming_booked_events.php">View Upcoming Events</a>
            </div>
            <div class="card">
                <h2>Write a Review</h2>
                <a href="write_review.php">Write a Review</a>
            </div>
            <div class="card">
                <h2>Manage Account</h2>
                <a href="manage_account.php">Manage Account</a>
            </div>
        </div>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>