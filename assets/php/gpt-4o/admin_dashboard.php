<?php
session_start();
if (!isset($_SESSION['staff_id']) || !isset($_SESSION['staff_role'])) {
    header("Location: auth.php");
    exit;
}

$staff_role = $_SESSION['staff_role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome to Admin Dashboard</h1>
    <h2>Role: <?php echo $staff_role; ?></h2>

    <?php if ($staff_role == 'Manager'): ?>
        <h3>Manager Options</h3>
        <ul>
            <li><a href="manage_clients.php">View and Manage Clients</a></li>
            <li><a href="manage_staff.php">View and Manage Staff</a></li>
            <li><a href="manage_events.php">View and Manage Events</a></li>
            <li><a href="manage_bookings.php">View and Manage Bookings</a></li>
            <li><a href="manage_payments.php">View and Manage Payments</a></li>
        </ul>
    <?php elseif ($staff_role == 'Event Organizer'): ?>
        <h3>Event Organizer Options</h3>
        <ul>
            <li><a href="manage_events.php">View and Manage Events</a></li>
            <li><a href="manage_bookings.php">View and Manage Bookings</a></li>
            <li><a href="manage_payments.php">View and Manage Payments</a></li>
        </ul>
    <?php elseif ($staff_role == 'System Administrator'): ?>
        <h3>System Administrator Options</h3>
        <ul>
            <li><a href="manage_clients.php">View and Manage Clients</a></li>
            <li><a href="manage_staff.php">View and Manage Staff</a></li>
            <li><a href="manage_events.php">View and Manage Events</a></li>
            <li><a href="system_settings.php">View and Manage System Settings</a></li>
        </ul>
    <?php endif; ?>
    
    <a href="logout.php">Logout</a>
</body>
</html>
