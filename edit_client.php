<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';

if (isset($_GET['id'])) {
    $clientId = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM client WHERE client_id = ?");
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
        $conn->close(); //Close connection after data fetch
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Client</title>
</head>
<body>
    <h2>Edit Client</h2>
    <form action="process_edit_client.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="client_id" value="<?php echo $client['client_id']; ?>">

        <label for="client_name">Name:</label>
        <input type="text" id="client_name" name="client_name" value="<?php echo $client['client_name']; ?>" required><br><br>

        <label for="client_email">Email:</label>
        <input type="email" id="client_email" name="client_email" value="<?php echo $client['client_email']; ?>" required><br><br>



        <input type="submit" value="Update Client">
    </form>
</body>
</html>

<?php
    } else {
        echo "Client not found.";
    }
    $stmt->close();
} else {
    echo "Invalid client ID.";
}

?>