<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit();
}
include 'db_connect.php';

$staff_id = $_SESSION['staff_id'];
$sql = "SELECT * FROM staff WHERE staff_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo $staff['staff_name']; ?>!</p>
        <img src="<?php echo $staff['staff_photo'] ? 'uploads/' . $staff['staff_photo'] : 'default_profile_picture.webp'; ?>" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">
        <a href="admin_logout.php">Log Out</a>
    </header>
    <main>
        <h2>Dashboard</h2>
        <nav>
            <ul>
                <li><a href="manage_clients.php">Manage Clients</a></li>
                <li><a href="manage_staff.php">Manage Staff</a></li>
                <li><a href="manage_events.php">Manage Events</a></li>
                <li><a href="manage_bookings.php">Manage Bookings</a></li>
                <li><a href="manage_payments.php">Manage Payments</a></li>
                <?php if ($staff['staff_role'] == 'System Administrator'): ?>
                    <li><a href="system_management.php">System Management</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </main>
</body>
</html>