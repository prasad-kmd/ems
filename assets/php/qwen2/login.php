<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM client WHERE client_email = ?";
    $stmt = getDbConnection()->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['client_password'])) {
            $_SESSION['client_id'] = $row['client_id'];
            $_SESSION['client_name'] = $row['client_name'];
            $_SESSION['client_photo'] = $row['client_photo'];
            header("Location: client_dashboard.php");
            exit();
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Invalid email or password.";
    }

    $stmt->close();
}

getDbConnection()->close();
?>