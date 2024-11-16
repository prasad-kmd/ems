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

// Function to check if the current admin has a specific role
function hasRole($role) {
    return $_SESSION['admin_role'] === $role;
}
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
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .dashboard {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #333;
        }
        .menu {
            margin-bottom: 20px;
        }
        .menu a {
            margin-right: 10px;
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h1>Welcome, <?php echo htmlspecialchars($admin_name); ?> (<?php echo htmlspecialchars($admin_role); ?>)</h1>
        
        <div class="menu">
            <?php if (hasRole('Manager') || hasRole('System Administrator')): ?>
                <a href="manage_clients.php">Manage Clients</a>
                <a href="manage_staff.php">Manage Staff</a>
            <?php endif; ?>
            
            <a href="manage_events.php">Manage Events</a>
            <a href="manage_bookings.php">Manage Bookings</a>
            <a href="manage_payments.php">Manage Payments</a>
            
            <?php if (hasRole('System Administrator')): ?>
                <a href="system_settings.php">System Settings</a>
                <a href="database_backup.php">Database Backup</a>
                <a href="user_roles.php">Manage User Roles</a>
                <a href="system_logs.php">System Logs</a>
            <?php endif; ?>
        </div>

        <h2>Dashboard Overview</h2>
        <!-- Add dashboard widgets or summary information here -->
        
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>