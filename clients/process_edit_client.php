<?php
session_start();

require_once '../db_config.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clientId = $_POST['client_id'];
    $clientName = $_POST["client_name"];
    $clientEmail = $_POST["client_email"];
    $clientPhone = $_POST["client_phone"];
    $clientAddress = $_POST["client_address"];



    $stmt = $conn->prepare("UPDATE client SET client_name=?, client_email=?, client_phone=?, client_address=? WHERE client_id=?");
    $stmt->bind_param("ssssi", $clientName, $clientEmail, $clientPhone, $clientAddress, $clientId);

    if ($stmt->execute()) {

        header("Location: manage_clients.php");
        exit;

    } else {
        echo "Error updating client: " . $stmt->error;
    }



    $stmt->close();
    $conn->close();
}


?>