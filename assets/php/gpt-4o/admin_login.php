<?php
session_start();
include 'db_connection.php'; // Ensure connection file is included

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($email) || empty($password)) {
        echo "Email and Password are required.";
        exit;
    }

    // Check if admin exists
    $sql = "SELECT staff_id, staff_password, staff_role FROM staff WHERE staff_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['staff_password'])) {
            // Password matches, set session
            $_SESSION['staff_id'] = $admin['staff_id'];
            $_SESSION['staff_role'] = $admin['staff_role'];

            // Redirect to admin dashboard
            header("Location: admin_dashboard.php");
            exit;
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No admin account found with that email.";
    }

    $stmt->close();
}
$conn->close();
?>
