<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required";
        header("Location: admin_login.php");
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM staff WHERE staff_email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['staff_password'])) {
        $_SESSION['admin_id'] = $admin['staff_id'];
        $_SESSION['admin_name'] = $admin['staff_name'];
        $_SESSION['admin_role'] = $admin['staff_role'];
    
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password";
        header("Location: admin_login.php");
        exit();
    }
} else {
    header("Location: admin_login.php");
    exit();
}
?>