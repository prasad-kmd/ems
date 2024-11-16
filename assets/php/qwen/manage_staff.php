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
    $sql = "SELECT * FROM staff";
    $result = $conn->query($sql);
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
    <title>Manage Staff</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Manage Staff</h1>
        <p>Welcome, <?php echo $_SESSION['staff_name']; ?>!</p>
        <a href="admin_dashboard.php">Back to Dashboard</a>
        <a href="admin_logout.php">Log Out</a>
    </header>
    <main>
        <h2>Staff Members</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Photo</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['staff_id'] . "</td>";
                    echo "<td>" . $row['staff_name'] . "</td>";
                    echo "<td>" . $row['staff_email'] . "</td>";
                    echo "<td>" . $row['staff_role'] . "</td>";
                    echo "<td><img src='uploads/" . $row['staff_photo'] . "' alt='Profile Photo' width='50'></td>";
                    echo "<td>";
                    echo "<a href='edit_staff.php?staff_id=" . $row['staff_id'] . "'>Edit</a> | ";
                    echo "<a href='delete_staff.php?staff_id=" . $row['staff_id'] . "'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No staff members found.</td></tr>";
            }
            ?>
        </table>
        <h2>Add New Staff Member</h2>
        <form action="add_staff_process.php" method="post" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="Manager">Manager</option>
                <option value="Event Organizer">Event Organizer</option>
                <option value="System Administrator">System Administrator</option>
            </select>
            <label for="photo">Profile Photo:</label>
            <input type="file" id="photo" name="photo">
            <button type="submit">Add Staff</button>
        </form>
    </main>
</body>
</html>