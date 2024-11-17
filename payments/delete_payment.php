<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

if (isset($_GET['id'])) {
    $paymentId = $_GET['id'];


    $stmt = $conn->prepare("DELETE FROM payment WHERE payment_id = ?");
    $stmt->bind_param("i", $paymentId);


    if ($stmt->execute()) {
        header("Location: manage_payments.php");
        exit;
    } else {
        echo "Error deleting payment: " . $stmt->error;
    }



    $stmt->close();
    $conn->close();
} else {
    echo "Invalid payment ID.";
}

?>