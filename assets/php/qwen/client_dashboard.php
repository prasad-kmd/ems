<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';

$client_id = $_SESSION['client_id'];
$sql = "SELECT * FROM client WHERE client_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Client Dashboard</h1>
        <p>Welcome, <?php echo $client['client_name']; ?>!</p>
        <a href="logout.php">Log Out</a>
    </header>
    <main>
        <h2>My Bookings</h2>
        <!-- Add code to display client's bookings -->
    </main>
</body>
</html>