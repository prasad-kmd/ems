<?php
session_start();
include 'db_connection.php'; // File to handle DB connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($email) || empty($password)) {
        echo "Email and Password are required.";
        exit;
    }

    // Check if user exists
    $sql = "SELECT client_id, client_password FROM client WHERE client_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['client_password'])) {
            // Password matches, set session
            $_SESSION['client_id'] = $user['client_id'];
            header("Location: client_dashboard.php");
            exit;
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No account found with that email.";
    }

    $stmt->close();
}
$conn->close();
?>
