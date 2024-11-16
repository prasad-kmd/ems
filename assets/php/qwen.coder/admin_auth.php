<?php
session_start();

// Database connection details
$host = 'localhost';
$dbname = 'db_ems';
$username = 'root'; // Default username for WAMP
$password = ''; // Default password for WAMP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM staff WHERE staff_email = :email");
    $stmt->execute(['email' => $email]);
    $staff = $stmt->fetch();

    if ($staff && password_verify($password, $staff['staff_password'])) {
        $_SESSION['staff_id'] = $staff['staff_id'];
        $_SESSION['staff_name'] = $staff['staff_name'];
        $_SESSION['staff_role'] = $staff['staff_role'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $login_error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <form method="POST" action="">
            <label for="email">Email:</label>

            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>

            <input type="password" id="password" name="password" required>

            <input type="submit" name="login" value="Login">

            <?php if (isset($login_error)) : ?>
                <p class="error"><?php echo $login_error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>