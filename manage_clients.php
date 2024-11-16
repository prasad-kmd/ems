<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';

// Fetch client data
$stmt = $conn->prepare("SELECT client_id, client_name, client_email, client_phone, client_address FROM client");
$stmt->execute();
$result = $stmt->get_result();
$clients = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Clients</title>
    <link rel="stylesheet" href="style.css">  </head>
<body>
    <h2>Manage Clients</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($clients) > 0): ?>  
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?php echo $client['client_id']; ?></td>
                        <td><?php echo $client['client_name']; ?></td>
                        <td><?php echo $client['client_email']; ?></td>
                        <td><?php echo $client['client_phone']; ?></td>
                        <td><?php echo $client['client_address']; ?></td>
                        <td>
                            <a href="edit_client.php?id=<?php echo $client['client_id']; ?>">Edit</a> |
                            <a href="delete_client.php?id=<?php echo $client['client_id']; ?>" onclick="return confirm('Are you sure you want to delete this client?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">No clients found.</td></tr> 
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>