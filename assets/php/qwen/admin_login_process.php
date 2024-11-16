<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch the staff member with the given email
    $sql = "SELECT * FROM staff WHERE staff_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_password = $row['staff_password'];

        // Verify the password
        if (password_verify($password, $stored_password)) {
            $_SESSION['staff_id'] = $row['staff_id'];
            $_SESSION['staff_name'] = $row['staff_name'];
            $_SESSION['staff_role'] = $row['staff_role'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Invalid email.";
    }
}
?>