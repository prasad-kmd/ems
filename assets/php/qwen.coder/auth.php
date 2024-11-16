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

    $stmt = $pdo->prepare("SELECT * FROM client WHERE client_email = :email");
    $stmt->execute(['email' => $email]);
    $client = $stmt->fetch();

    if ($client && password_verify($password, $client['client_password'])) {
        $_SESSION['client_id'] = $client['client_id'];
        $_SESSION['client_name'] = $client['client_name'];
        header("Location: client_dashboard.php");
        exit();
    } else {
        $login_error = "Invalid email or password.";
    }
}

// Handle signup form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $photo = '';

    if ($_FILES['photo']['name']) {
        $target_dir = "uploads/";
        $target_file = $target_dir . uniqid() . basename($_FILES["photo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($imageFileType, $allowedTypes)) {
            $signup_error = "Only JPG, JPEG, PNG, GIF, and WEBP files are allowed.";
        } else {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo = $target_file;
            } else {
                $signup_error = "Sorry, there was an error uploading your file.";
            }
        }
    }

    if (empty($signup_error)) {
        $stmt = $pdo->prepare("INSERT INTO client (client_name, client_email, client_password, client_phone, client_address, client_photo) VALUES (:name, :email, :password, :phone, :address, :photo)");
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password, 'phone' => $phone, 'address' => $address, 'photo' => $photo]);

        $signup_success = "Registration successful! You will be redirected to the login page shortly.";
        header("refresh:5;url=auth.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login or Signup</title>
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
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }
        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }
        .tab button:hover {
            background-color: #ddd;
        }
        .tab button.active {
            background-color: #ccc;
        }
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }
        .tabcontent.active {
            display: block;
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
        <div class="tab">
            <button class="tablinks active" onclick="openForm(event, 'Login')">Login</button>
            <button class="tablinks" onclick="openForm(event, 'Signup')">Signup</button>
        </div>

        <div id="Login" class="tabcontent active">
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

        <div id="Signup" class="tabcontent">
            <form method="POST" action="" enctype="multipart/form-data">
                <label for="name">Name:</label>

                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>

                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>

                <input type="password" id="password" name="password" required>

                <label for="confirm_password">Confirm Password:</label>

                <input type="password" id="confirm_password" name="confirm_password" required>

                <label for="phone">Phone:</label>

                <input type="tel" id="phone" name="phone" required>

                <label for="address">Address:</label>

                <textarea id="address" name="address" required></textarea>

                <label for="photo">Profile Photo:</label>

                <input type="file" id="photo" name="photo" accept="image/*">

                <input type="submit" name="signup" value="Signup">

                <?php if (isset($signup_error)) : ?>
                    <p class="error"><?php echo $signup_error; ?></p>
                <?php endif; ?>
                <?php if (isset($signup_success)) : ?>
                    <p class="success"><?php echo $signup_success; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script>
        function openForm(evt, formName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove("active");
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }
            document.getElementById(formName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
        document.getElementById("defaultOpen").click();
    </script>
</body>
</html>