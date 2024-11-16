<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM client WHERE client_email = ? AND client_password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['client_id'] = $row['client_id'];
        $_SESSION['client_name'] = $row['client_name'];
        header("Location: client_dashboard.php");
    } else {
        echo "Invalid email or password.";
    }
}
?>