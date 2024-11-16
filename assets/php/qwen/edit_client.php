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
    $client_id = $_GET['client_id'];
    $sql = "SELECT * FROM client WHERE client_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $client = $result->fetch_assoc();
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
    <title>Edit Client</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Edit Client</h1>
        <p>Welcome, <?php echo $_SESSION['staff_name']; ?>!</p>
        <a href="manage_clients.php">Back to Clients</a>
        <a href="admin_dashboard.php">Back to Dashboard</a>
        <a href="admin_logout.php">Log Out</a>
    </header>
    <main>
        <form action="edit_client_process.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="client_id" value="<?php echo $client['client_id']; ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $client['client_name']; ?>" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $client['client_email']; ?>" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo $client['client_password']; ?>" required>
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo $client['client_phone']; ?>" required>
            <label for="address">Address:</label>
            <textarea id="address" name="address" required><?php echo $client['client_address']; ?></textarea>
            <label for="photo">Profile Photo:</label>
            <input type="file" id="photo" name="photo">
            <img src="uploads/<?php echo $client['client_photo']; ?>" alt="Profile Photo" width="100">
            <button type="submit">Update Client</button>
        </form>
    </main>
</body>
</html>