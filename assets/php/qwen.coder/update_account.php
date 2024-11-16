<?php
session_start();

// Check if the client is logged in
if (!isset($_SESSION['client_id'])) {
    header("Location: auth.php");
    exit();
}

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

$client_id = $_SESSION['client_id'];

// Fetch client details
$stmt = $pdo->prepare("SELECT * FROM client WHERE client_id = :client_id");
$stmt->execute(['client_id' => $client_id]);
$client = $stmt->fetch();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $photo = $client['client_photo'];

    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . uniqid() . basename($_FILES["photo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($imageFileType, $allowedTypes)) {
            $error = "Only JPG, JPEG, PNG, GIF, and WEBP files are allowed.";
        } else {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo = $target_file;
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        }
    }

    if (empty($error)) {
        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE client SET client_name = :name, client_email = :email, client_password = :password, client_phone = :phone, client_address = :address, client_photo = :photo WHERE client_id = :client_id");
            $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password, 'phone' => $phone, 'address' => $address, 'photo' => $photo, 'client_id' => $client_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE client SET client_name = :name, client_email = :email, client_phone = :phone, client_address = :address, client_photo = :photo WHERE client_id = :client_id");
            $stmt->execute(['name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address, 'photo' => $photo, 'client_id' => $client_id]);
        }

        $success = "Account updated successfully!";
    }
}

// Default profile picture
$profile_photo = $client['client_photo'] ? $client['client_photo'] : 'default_profile.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
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
        .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Update Account</h2>
            <img src="<?php echo $profile_photo; ?>" alt="Profile Photo" class="profile-photo">
            <form method="POST" action="" enctype="multipart/form-data">
                <label for="name">Name:</label>

                <input type="text" id="name" name="name" value="<?php echo $client['client_name']; ?>" required>

                <label for="email">Email:</label>

                <input type="email" id="email" name="email" value="<?php echo $client['client_email']; ?>" required>

                <label for="password">Password:</label>

                <input type="password" id="password" name="password">

                <label for="phone">Phone:</label>

                <input type="tel" id="phone" name="phone" value="<?php echo $client['client_phone']; ?>" required>

                <label for="address">Address:</label>

                <textarea id="address" name="address" required><?php echo $client['client_address']; ?></textarea>

                <label for="photo">Profile Photo:</label>

                <input type="file" id="photo" name="photo" accept="image/*">

                <input type="submit" value="Update Account">

                <?php if (isset($error)) : ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                <?php if (isset($success)) : ?>
                    <p class="success"><?php echo $success; ?></p>
                <?php endif; ?>
            </form>
            <a href="client_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>