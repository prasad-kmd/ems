<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';

$staffId = $_SESSION['staff_id'];
$role = $_SESSION['staff_role'];

// Fetch staff details (for profile section - same as before)
$stmt = $conn->prepare("SELECT staff_name, staff_photo FROM staff WHERE staff_id = ?");
$stmt->bind_param("i", $staffId);
$stmt->execute();
$stmt->bind_result($staffName, $staffPhoto);
$stmt->fetch();
$stmt->close();
$conn->close(); //Close after fetching data

$profilePicture = $staffPhoto ? $staffPhoto : 'images/default_profile.jpg';
?>

<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['admin_name']; ?>'s Dashboard</title>
    <link rel="stylesheet" href="assets/css/semantic.css">
    <link rel="stylesheet" href="assets/font/fonts.css" />
</head>

<body>
    <div class="container">
        <h2>Welcome, <?php echo $staffName; ?> (<?php echo $role; ?>)!</h2>
        <img src="<?php echo $profilePicture; ?>" alt="Profile Picture">

        <div class="dashboard-content">

            <?php if ($role == 'Manager' || $role == 'Event Organizer' || $role == 'System Administrator'): ?>
                <h3>Client Management</h3>
                <ul>
                    <li><a href="manage_clients.php">Manage Clients</a></li>
                </ul>
            <?php endif; ?>

            <?php if ($role == 'Manager' || $role == 'System Administrator'): ?>
                <h3>Staff Management</h3>
                <ul>
                    <li><a href="manage_staff.php">Manage Staff</a></li>
                </ul>
            <?php endif; ?>

            <?php if ($role == 'Manager' || $role == 'Event Organizer'): ?>
                <h3>Booking Management</h3>
                <ul>
                    <li><a href="manage_bookings.php">Manage Bookings</a></li>
                </ul>
            <?php endif; ?>

            <?php if ($role == 'Manager' || $role == 'Event Organizer'): ?>
                <h3>Payment Management</h3>
                <ul>
                    <li><a href="manage_payments.php">Manage Payments</a></li>
                </ul>
            <?php endif; ?>



            <?php if ($role == 'Manager' || $role == 'System Administrator'): ?>
                <h3>Event Management</h3>
                <ul>
                    <li><a href="manage_event.php">Manage Events</a></li>
                </ul>

                <h3>Venue Management</h3>
                <ul>
                    <li><a href="manage_venues.php">Manage Venues</a></li>
                </ul>
            <?php endif; ?>



            <?php if ($role == 'System Administrator'): ?>
                <h3>System Administration</h3>
                <ul>
                    <li><a href="backup_database.php">Backup Database</a></li>
                </ul>
            <?php endif; ?>

                <a href="edit_profile.php">profile edit</a>
        </div>
    </div>


</body>

</html>