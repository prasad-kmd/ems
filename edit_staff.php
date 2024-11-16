<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';

if (isset($_GET['id'])) {
    $staffId = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id = ?");
    $stmt->bind_param("i", $staffId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $staff = $result->fetch_assoc();


?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Staff</title>
</head>
<body>
    <h2>Edit Staff Member</h2>
    <form action="process_edit_staff.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="staff_id" value="<?php echo $staff['staff_id']; ?>">

        <label for="staff_name">Name:</label>
        <input type="text" id="staff_name" name="staff_name" value="<?php echo $staff['staff_name']; ?>" required><br><br>

        <label for="staff_email">Email:</label>
        <input type="email" id="staff_email" name="staff_email" value="<?php echo $staff['staff_email']; ?>" required><br><br>

        <label for="staff_phone">Phone:</label>
        <input type="tel" id="staff_phone" name="staff_phone" value="<?php echo $staff['staff_phone']; ?>" required><br><br>

        <label for="staff_role">Role:</label>
        <select id="staff_role" name="staff_role" required>
            <option value="Manager" <?php if ($staff['staff_role'] == 'Manager') echo 'selected'; ?>>Manager</option>
            <option value="Event Organizer" <?php if ($staff['staff_role'] == 'Event Organizer') echo 'selected'; ?>>Event Organizer</option>
            <option value="System Administrator" <?php if ($staff['staff_role'] == 'System Administrator') echo 'selected'; ?>>System Administrator</option>
        </select><br><br>


        <input type="submit" value="Update Staff">
    </form>
</body>
</html>
<?php
        $conn->close(); // Close the connection after fetching the data
    } else {
        echo "Staff member not found.";
    }

    $stmt->close(); // Close the statement
} else {
    echo "Invalid staff ID.";
}
?>