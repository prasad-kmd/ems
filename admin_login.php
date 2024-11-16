<?php
require_once 'db_config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['admin_email'];
    $password = $_POST['admin_password'];

    $stmt = $conn->prepare("SELECT staff_id, staff_password, staff_role FROM staff WHERE staff_email = ?"); // Select the hashed password from the database
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($staffId, $hashedPassword, $staffRole); // Bind the hashed password


    if ($stmt->fetch() && password_verify($password, $hashedPassword)) { // Verify the password!
        $_SESSION['staff_id'] = $staffId;
        $_SESSION['staff_role'] = $staffRole;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "<script>alert('Invalid email or password.'); window.location.href = 'admin_login.php';</script>";
        exit;
    }


    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="assets/css/auth_admin.css">
    <link rel="stylesheet" href="assets/font/fonts.css" />
</head>

<body>

    <div class="login-box">
        <h2 class="btn-shine">Administrator Login</h2>
        <form method="POST">
            <div class="user-box">
                <input type="email" id="admin_email" name="admin_email" required>
                <label>Email</label>
            </div>
            <div class="user-box">
                <input type="password" id="admin_password" name="admin_password" required>
                <label>Password</label>
            </div>
            <button type="submit">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                Login
            </button>
        </form>
    </div>
</body>

</html>
