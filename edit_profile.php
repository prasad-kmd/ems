<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';

$staffId = $_SESSION['staff_id'];

// Fetch the current staff member's data
$stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id = ?");
$stmt->bind_param("i", $staffId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $staff = $result->fetch_assoc();

    $conn->close();//Close after use
    ?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
</head>
<body>
    <h2>Edit Your Profile</h2>

    <form action="process_edit_profile.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="staff_id" value="<?php echo $staff['staff_id']; ?>">

        <label for="staff_name">Name:</label>
        <input type="text" id="staff_name" name="staff_name" value="<?php echo $staff['staff_name']; ?>" required><br><br>

        <label for="staff_email">Email:</label>
        <input type="email" id="staff_email" name="staff_email" value="<?php echo $staff['staff_email']; ?>" required><br><br>

        <label for="staff_phone">Phone:</label>
        <input type="tel" id="staff_phone" name="staff_phone" value="<?php echo $staff['staff_phone']; ?>" required><br><br>

        <!-- <label for="staff_address">Address:</label>
        <textarea id="staff_address" name="staff_address"><?php echo $staff['staff_address']; ?></textarea><br><br> -->

        <label for="staff_photo">Profile Picture:</label>
        <?php if ($staff['staff_photo']): ?>
        <img src="<?php echo $staff['staff_photo']; ?>" alt="Current Profile Picture" width="100"><br>
        <?php endif; ?>
        <input type="file" id="staff_photo" name="staff_photo"><br><br>
        <input type="hidden" name="existing_photo" value="<?php echo $staff['staff_photo']; ?>">


        <label for="new_password">New Password (leave blank to keep current password):</label>
        <input type="password" id="new_password" name="new_password"><br><br>


        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password"><br><br>



        <input type="submit" value="Update Profile">
    </form>
</body>
</html>



<?php
} else {
    echo "Staff member not found."; // Handle error as needed
}
$stmt->close();

?>