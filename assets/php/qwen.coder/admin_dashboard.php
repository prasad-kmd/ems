<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_auth.php");
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

$staff_id = $_SESSION['staff_id'];
$staff_role = $_SESSION['staff_role'];

// Fetch staff details
$stmt = $pdo->prepare("SELECT * FROM staff WHERE staff_id = :staff_id");
$stmt->execute(['staff_id' => $staff_id]);
$staff = $stmt->fetch();

// Default profile picture
$profile_photo = $staff['staff_photo'] ? $staff['staff_photo'] : 'default_profile.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            <h2>Admin Dashboard</h2>
            <img src="<?php echo $profile_photo; ?>" alt="Profile Photo" class="profile-photo">
            <p>Welcome, <?php echo $staff['staff_name']; ?></p>
            <a href="manage_clients.php" <?php if ($staff_role != 'Manager' && $staff_role != 'System Administrator') echo 'style="display:none;"'; ?>>Manage Clients</a>
            <a href="manage_staff.php" <?php if ($staff_role != 'Manager' && $staff_role != 'System Administrator') echo 'style="display:none;"'; ?>>Manage Staff</a>
            <a href="manage_events.php">Manage Events</a>
            <a href="manage_bookings.php">Manage Bookings</a>
            <a href="manage_payments.php">Manage Payments</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="content">
            <h2>Welcome to the Admin Dashboard</h2>
            <p>Select an option from the sidebar to manage clients, staff, events, bookings, and payments.</p>
        </div>
    </div>
</body>
</html>