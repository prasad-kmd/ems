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
    $sql = "DELETE FROM client WHERE client_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $client_id);

    if ($stmt->execute()) {
        header("Location: manage_clients.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "You do not have permission to access this page.";
    exit();
}
?>