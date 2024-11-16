<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit();
}
include 'db_connect.php';

$staff_id = $_SESSION['staff_id'];
$staff_role = $_SESSION['staff_role'];

if ($staff_role == 'Manager' || $staff_role == 'System Administrator') {
    $edit_staff_id = $_GET['staff_id'];
    $sql = "SELECT * FROM staff WHERE staff_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $edit_staff_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $staff = $result->fetch_assoc();
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
    <title>Edit Staff</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Edit Staff</h1>
        <p>Welcome, <?php echo $_SESSION['staff_name']; ?>!</p>
        <a href="manage_staff.php">Back to Staff</a>
        <a href="admin_dashboard.php">Back to Dashboard</a>
        <a href="admin_logout.php">Log Out</a>
    </header>
    <main>
        <form action="edit_staff_process.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="staff_id" value="<?php echo $staff['staff_id']; ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $staff['staff_name']; ?>" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $staff['staff_email']; ?>" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo $staff['staff_password']; ?>" required>
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo $staff['staff_phone']; ?>" required>
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="Manager" <?php echo $staff['staff_role'] == 'Manager' ? 'selected' : ''; ?>>Manager</option>
                <option value="Event Organizer" <?php echo $staff['staff_role'] == 'Event Organizer' ? 'selected' : ''; ?>>Event Organizer</option>
                <option value="System Administrator" <?php echo $staff['staff_role'] == 'System Administrator' ? 'selected' : ''; ?>>System Administrator</option>
            </select>
            <label for="photo">Profile Photo:</label>
            <input type="file" id="photo" name="photo">
            <img src="uploads/<?php echo $staff['staff_photo']; ?>" alt="Profile Photo" width="100">
            <button type="submit">Update Staff</button>
        </form>
    </main>
</body>
</html>