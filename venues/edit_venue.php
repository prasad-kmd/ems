<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

if (isset($_GET['id'])) {
    $venueId = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM venue WHERE venue_id = ?");
    $stmt->bind_param("i", $venueId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $venue = $result->fetch_assoc();
        $conn->close();


?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Venue</title>
</head>
<body>
    <h2>Edit Venue</h2>
    <form action="process_edit_venue.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="venue_id" value="<?php echo $venue['venue_id']; ?>">


        <input type="submit" value="Update Venue">
    </form>
</body>
</html>
<?php
    } else {
        echo "Venue not found.";
    }
    $stmt->close();

} else {
    echo "Invalid venue ID.";
}
?>