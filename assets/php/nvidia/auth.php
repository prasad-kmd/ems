<?php
// Include database connection
include 'db_connection.php';

// Check if Sign in form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signin-submit"])) {
    $email = mysqli_real_escape_string($conn, $_POST["signin-email"]);
    $password = mysqli_real_escape_string($conn, $_POST["signin-password"]);

    // Query to check if email and password match in client table
    $query = "SELECT * FROM client WHERE client_email = '$email' AND client_password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Fetch relevant client ID from database
        $client_id = mysqli_fetch_assoc($result)['client_id'];

        // Redirect to client dashboard with client ID
        header("Location: client_dashboard.php?client_id=$client_id");
        exit;
    } else {
        // Sign in failed, display error message
        $error = "Invalid email or password";
    }
}

// Check if Sign up form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signup-submit"])) {
    $name = mysqli_real_escape_string($conn, $_POST["signup-name"]);
    $email = mysqli_real_escape_string($conn, $_POST["signup-email"]);
    $phone = mysqli_real_escape_string($conn, $_POST["signup-phone"]);
    $password = mysqli_real_escape_string($conn, $_POST["signup-password"]);
    $confirm_password = mysqli_real_escape_string($conn, $_POST["signup-confirm-password"]);
    $address = mysqli_real_escape_string($conn, $_POST["signup-address"]);

    // Handle profile picture upload
    $profile_picture = $_FILES["signup-profile-picture"];
    $picture_name = $profile_picture["name"];
    $picture_type = $profile_picture["type"];
    $picture_tmp_name = $profile_picture["tmp_name"];
    $picture_error = $profile_picture["error"];

    // Check if profile picture is uploaded successfully
    if ($picture_error == 0) {
        // Define upload directory and allowed file types
        $upload_dir = "uploads/profile_pictures/";
        $allowed_types = array("image/jpg", "image/jpeg", "image/png", "image/gif", "image/webp");

        // Check if file type is allowed
        if (in_array($picture_type, $allowed_types)) {
            // Generate a unique filename for the profile picture
            $unique_picture_name = uniqid(). ".". pathinfo($picture_name, PATHINFO_EXTENSION);
            $upload_file = $upload_dir. $unique_picture_name;

            // Upload profile picture to server
            if (move_uploaded_file($picture_tmp_name, $upload_file)) {
                // Check if password and confirm password match
                if ($password!= $confirm_password) {
                    $error = "Passwords do not match";
                } else {
                    // Insert new client with uploaded profile picture
                    $query = "INSERT INTO client (client_name, client_email, client_password, client_phone, client_address, client_photo) 
                              VALUES ('$name', '$email', '$password', '$phone', '$address', '$unique_picture_name')";
                    if (mysqli_query($conn, $query)) {
                        // Fetch newly created client ID
                        $client_id = mysqli_insert_id($conn);

                        // Redirect to client dashboard with client ID
                        header("Location: client_dashboard.php?client_id=$client_id");
                        exit;
                    } else {
                        // Sign up failed, display error message
                        $error = "Failed to create account";
                    }
                }
            } else {
                // Profile picture upload failed, display error message
                $error = "Failed to upload profile picture";
            }
        } else {
            // File type not allowed, display error message
            $error = "Only.jpg,.jpeg,.png,.gif, and.webp file types are allowed";
        }
    } else {
        // Check if password and confirm password match
        if ($password!= $confirm_password) {
            $error = "Passwords do not match";
        } else {
            // Insert new client without profile picture
            $query = "INSERT INTO client (client_name, client_email, client_password, client_phone, client_address) 
                      VALUES ('$name', '$email', '$password', '$phone', '$address')";
            if (mysqli_query($conn, $query)) {
                // Fetch newly created client ID
                $client_id = mysqli_insert_id($conn);

                // Redirect to client dashboard with client ID
                header("Location: client_dashboard.php?client_id=$client_id");
                exit;
            } else {
                // Sign up failed, display error message
                $error = "Failed to create account";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Auth</title>
    <!-- CSS for tabbed forms (add your preferred CSS) -->
    <style>
        /* Basic styling for demo purposes */
       .container { max-width: 300px; margin: 50px auto; }
       .tab-nav { list-style: none; padding: 0; margin: 0; }
       .tab-nav li { display: inline-block; margin-right: 20px; }
       .tab-nav a { text-decoration: none; color: #337ab7; }
       .tab-content { padding: 20px; border: 1px solid #ddd; }
       .form-group { margin-bottom: 15px; }
       .form-group label { display: block; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <ul class="tab-nav">
            <li><a href="#signin">Sign in</a></li>
            <li><a href="#signup">Sign up</a></li>
        </ul>
        <div class="tab-content">
            <div id="signin">
                <h2>Sign in</h2>
                <form id="signin-form" method="post">
                    <div class="form-group">
                        <label for="signin-email">Email</label>
                        <input type="email" id="signin-email" name="signin-email" required>
                    </div>
                    <div class="form-group">
                        <label for="signin-password">Password</label>
                        <input type="password" id="signin-password" name="signin-password" required>
                    </div>
                    <button type="submit" name="signin-submit">Sign in</button>
                    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; }?>
                </form>
            </div>
            <div id="signup">
                <h2>Sign up</h2>
                <form id="signup-form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="signup-name">Name</label>
                        <input type="text" id="signup-name" name="signup-name" required>
                    </div>
                    <div class="form-group">
                        <label for="signup-email">Email</label>
                        <input type="email" id="signup-email" name="signup-email" required>
                    </div>
                    <div class="form-group">
                        <label for="signup-phone">Phone</label>
                        <input type="tel" id="signup-phone" name="signup-phone" required>
                    </div>
                    <div class="form-group">
                        <label for="signup-password">Password</label>
                        <input type="password" id="signup-password" name="signup-password" required>
                    </div>
                    <div class="form-group">
                        <label for="signup-confirm-password">Confirm Password</label>
                        <input type="password" id="signup-confirm-password" name="signup-confirm-password" required>
                    </div>
                    <div class="form-group">
                        <label for="signup-address">Address</label>
                        <textarea id="signup-address" name="signup-address" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="signup-profile-picture">Profile Picture</label>
                        <input type="file" id="signup-profile-picture" name="signup-profile-picture" accept=".jpg,.jpeg,.png,.gif,.webp">
                    </div>
                    <button type="submit" name="signup-submit">Sign up</button>
                    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; }?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>