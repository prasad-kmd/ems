<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staffId = $_POST['staff_id'];
    $staffName = $_POST["staff_name"];
    $staffEmail = $_POST["staff_email"];
    $staffPhone = $_POST["staff_phone"];
    $staffRole = $_POST["staff_role"];


    // No file upload handling in this example (add if needed - see previous examples)


    $stmt = $conn->prepare("UPDATE staff SET staff_name=?, staff_email=?, staff_phone=?, staff_role=? WHERE staff_id=?");
    $stmt->bind_param("ssssi", $staffName, $staffEmail, $staffPhone, $staffRole, $staffId);




    if ($stmt->execute()) {

        header("Location: manage_staff.php");
        exit;

    } else {

        echo "Error updating staff: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

?>