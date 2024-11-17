<?php
require_once 'db_config.php'; // Include your database connection file
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["login_email"];
    $password = $_POST["login_password"];

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("SELECT client_id, client_password FROM client WHERE client_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($clientId, $hashedPassword);

    if ($stmt->fetch() && password_verify($password, $hashedPassword)) {
        // Successful login
        $_SESSION["client_id"] = $clientId; // Store the client ID in the session

        // Redirect to client dashboard
        header("Location: client_dashboard.php"); // Replace with your actual client dashboard file
        exit;
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid email or password.'); window.location.href = 'auth.html';</script>"; // Redirect back to login page with error message
       exit; //essential
    }

    $stmt->close();
    $conn->close();
}
?>