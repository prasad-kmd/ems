<?php
// Include database connection file
include 'db_connection.php';

// Initialize variables for form data and errors
$username = $password = $email = '';
$errors = array();

// Handle form submissions here (we'll add this later)
// Sign Up
if (isset($_POST['signup'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($username)) $errors[] = "Username is required";
    if (empty($email)) $errors[] = "Email is required";
    if (empty($password)) $errors[] = "Password is required";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match";

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT * FROM client WHERE client_email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $errors[] = "Email already exists";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO client (client_name, client_email, client_password) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $hashed_password])) {
            $_SESSION['success'] = "Registration successful. Please sign in.";
            header("Location: auth.php");
            exit();
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
    }
}

// Sign In
if (isset($_POST['signin'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username)) $errors[] = "Username is required";
    if (empty($password)) $errors[] = "Password is required";

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM client WHERE client_name = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['client_password'])) {
            $_SESSION['user_id'] = $user['client_id'];
            $_SESSION['username'] = $user['client_name'];
            header("Location: client_dashboard.php");
            exit();
        } else {
            $errors[] = "Invalid username or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In / Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="success">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>
    <div class="container">
        <div class="tabs">
            <button class="tab-btn active" onclick="openTab(event, 'signin')">Sign In</button>
            <button class="tab-btn" onclick="openTab(event, 'signup')">Sign Up</button>
        </div>

        <div id="signin" class="tab-content">
            <h2>Sign In</h2>
            <form action="auth.php" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="signin">Sign In</button>
            </form>
        </div>

        <div id="signup" class="tab-content" style="display:none;">
            <h2>Sign Up</h2>
            <form action="auth.php" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit" name="signup">Sign Up</button>
            </form>
        </div>
    </div>

    <script>
        function openTab(evt, tabName) {
            var i, tabContent, tabButtons;
            tabContent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabContent.length; i++) {
                tabContent[i].style.display = "none";
            }
            tabButtons = document.getElementsByClassName("tab-btn");
            for (i = 0; i < tabButtons.length; i++) {
                tabButtons[i].className = tabButtons[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
</body>

</html>