<?php
session_start();

// Check if the client is logged in
if (!isset($_SESSION['client_id'])) {
    header("Location: auth.php");
    exit();
}

// Database connection details
$host = 'localhost';
$dbname = 'db_ems';
$username = 'root'; // Default username for WAMP
$password = ''; // Default password for WAMP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

$client_id = $_SESSION['client_id'];

// Fetch client details
$stmt = $pdo->prepare("SELECT * FROM client WHERE client_id = :client_id");
$stmt->execute(['client_id' => $client_id]);
$client = $stmt->fetch();

// Fetch upcoming events
$stmt = $pdo->prepare("SELECT * FROM event WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 2");
$stmt->execute();
$upcoming_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch booking history
$stmt = $pdo->prepare("SELECT * FROM booking WHERE client_id = :client_id");
$stmt->execute(['client_id' => $client_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch total events booked and total payments
$stmt = $pdo->prepare("SELECT COUNT(*) AS total_events FROM booking WHERE client_id = :client_id");
$stmt->execute(['client_id' => $client_id]);
$total_events = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT SUM(payment_amount) AS total_payments FROM payment WHERE booking_id IN (SELECT booking_id FROM booking WHERE client_id = :client_id)");
$stmt->execute(['client_id' => $client_id]);
$total_payments = $stmt->fetchColumn();

// Default profile picture
$profile_photo = $client['client_photo'] ? $client['client_photo'] : 'default_profile.jpg';

// Ensure total_payments is not NULL
$total_payments = $total_payments !== null ? $total_payments : 0.00;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #333;
            color: #fff;
            padding: 20px;
            height: 100vh;
        }
        .sidebar a {
            display: block;
            color: #fff;
            padding: 10px 0;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 15px;
        }
        .card h2 {
            margin-top: 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .stats div {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
        }
        .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Client Dashboard</h2>
            <img src="<?php echo $profile_photo; ?>" alt="Profile Photo" class="profile-photo">
            <p>Welcome, <?php echo $client['client_name']; ?></p>
            <a href="#upcoming-events">Upcoming Events</a>
            <a href="#booking-history">Booking History</a>
            <a href="update_account.php">Manage Account</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="content">
            <div class="stats">
                <div>Total Events Booked: <?php echo $total_events; ?></div>
                <div>Total Payments: $<?php echo number_format($total_payments, 2); ?></div>
            </div>
            <div id="upcoming-events" class="card">
                <h2>Upcoming Events</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Price</th>
                            <th>Capacity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($upcoming_events as $event) : ?>
                            <tr>
                                <td><?php echo $event['event_title']; ?></td>
                                <td><?php echo $event['event_date']; ?></td>
                                <td><?php echo $event['event_start_time']; ?> - <?php echo $event['event_endtime']; ?></td>
                                <td>$<?php echo number_format($event['event_price'], 2); ?></td>
                                <td><?php echo $event['event_remain_capacity']; ?></td>
                                <td><a href="book_event.php?event_id=<?php echo $event['event_id']; ?>">Book</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div id="booking-history" class="card">
                <h2>Booking History</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Date</th>
                            <th>Number of Guests</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking) : ?>
                            <tr>
                                <td>
                                    <?php
                                    $stmt = $pdo->prepare("SELECT event_title FROM event WHERE event_id = :event_id");
                                    $stmt->execute(['event_id' => $booking['event_id']]);
                                    $event = $stmt->fetch();
                                    echo $event['event_title'];
                                    ?>
                                </td>
                                <td><?php echo $booking['booking_date']; ?></td>
                                <td><?php echo $booking['number_guests']; ?></td>
                                <td><?php echo $booking['booking_status']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>