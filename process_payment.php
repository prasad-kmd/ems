<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}


require_once 'db_config.php';


if (isset($_GET['id']) && isset($_GET['action'])) {
    $paymentId = $_GET['id'];
    $action = $_GET['action'];



    switch ($action) {
        case 'approve':
            $newStatus = 'success'; // Or a relevant approved status in your system
            break;
        case 'reject':
            $newStatus = 'failed'; // Or 'rejected', or a relevant status
            break;
        default:
           //Handle invalid actions
            echo "Invalid action requested.";
            exit; //Stop further execution
    }





    $stmt = $conn->prepare("UPDATE payment SET payment_status = ? WHERE payment_id = ?");
    $stmt->bind_param("si", $newStatus, $paymentId);


    if ($stmt->execute()) {

        header("Location: manage_payments.php");
        exit;


    } else {

         // Handle errors
        echo "Error updating payment: " . $stmt->error;


    }


    $stmt->close();
    $conn->close();
}


?>