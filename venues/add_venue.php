<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Venue</title>
</head>
<body>
    <h2>Add New Venue</h2>
    <form action="process_add_venue.php" method="post" enctype="multipart/form-data">
        <label for="venue_name">Name:</label>
        <input type="text" id="venue_name" name="venue_name" required><br><br>

        <label for="venue_description">Description:</label>
        <textarea id="venue_description" name="venue_description"></textarea><br><br>

        <label for="venue_photo">Photo:</label>
        <input type="file" id="venue_photo" name="venue_photo"><br><br>

        <label for="venue_address">Address:</label>
        <textarea id="venue_address" name="venue_address" required></textarea><br><br>

        <label for="venue_capacity">Capacity:</label>
        <input type="number" id="venue_capacity" name="venue_capacity" required><br><br>

        <label for="venue_email">Email:</label>
        <input type="email" id="venue_email" name="venue_email" required><br><br>

        <label for="venue_phone">Phone:</label>
        <input type="tel" id="venue_phone" name="venue_phone" required><br><br>

        <input type="submit" value="Add Venue">
    </form>
</body>
</html>