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

// Fetch event details
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $stmt = $pdo->prepare("SELECT * FROM event WHERE event_id = :event_id");
    $stmt->execute(['event_id' => $event_id]);
    $event = $stmt->fetch();

    if (!$event) {
        header("Location: client_dashboard.php");
        exit();
    }
} else {
    header("Location: client_dashboard.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $number_guests = (int)$_POST['number_guests'];
    $booking_date = date('Y-m-d');
    $staff_id = $event['staff_id'];

    if ($number_guests <= 0) {
        $error = "Number of guests must be greater than zero.";
    } elseif ($number_guests > $event['event_remain_capacity']) {
        $error = "Not enough capacity available.";
    } else {
        // Insert booking into the database
        $stmt = $pdo->prepare("INSERT INTO booking (client_id, event_id, booking_date, number_guests, booking_status, staff_id) VALUES (:client_id, :event_id, :booking_date, :number_guests, 'pending', :staff_id)");
        $stmt->execute(['client_id' => $client_id, 'event_id' => $event_id, 'booking_date' => $booking_date, 'number_guests' => $number_guests, 'staff_id' => $staff_id]);

        $booking_id = $pdo->lastInsertId();

        // Update event remaining capacity
        $new_capacity = $event['event_remain_capacity'] - $number_guests;
        $stmt = $pdo->prepare("UPDATE event SET event_remain_capacity = :new_capacity WHERE event_id = :event_id");
        $stmt->execute(['new_capacity' => $new_capacity, 'event_id' => $event_id]);

        // Redirect to payment page
        header("Location: payment.php?booking_id=$booking_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
        .event-details {
            margin-bottom: 20px;
        }
        .event-details img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Book Event: <?php echo $event['event_title']; ?></h2>
            <div class="event-details">
                <img src="<?php echo $event['event_photo'] ? $event['event_photo'] : 'default_event.jpg'; ?>" alt="Event Photo">
                <p><strong>Date:</strong> <?php echo $event['event_date']; ?></p>
                <p><strong>Time:</strong> <?php echo $event['event_start_time']; ?> - <?php echo $event['event_endtime']; ?></p>
                <p><strong>Price:</strong> $<?php echo number_format($event['event_price'], 2); ?> per guest</p>
                <p><strong>Remaining Capacity:</strong> <?php echo $event['event_remain_capacity']; ?></p>
            </div>
            <form method="POST" action="">
                <label for="number_guests">Number of Guests:</label>

                <input type="number" id="number_guests" name="number_guests" min="1" required>

                <?php if (isset($error)) : ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                <input type="submit" value="Proceed to Payment">
            </form>
            <a href="client_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>