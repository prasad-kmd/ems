<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: auth.php");
    exit;
}

require_once 'db_config.php';

$clientId = $_SESSION['client_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission (update client profile)
    $clientName = $_POST["client_name"];
    $clientEmail = $_POST["client_email"];
    $clientPhone = $_POST['client_phone'];
    $clientAddress = $_POST['client_address'];

    // Handle optional password change
    $newPassword = "";
    if (!empty($_POST["new_password"])) {
        if ($_POST["new_password"] === $_POST["confirm_password"]) {
            $newPassword = password_hash($_POST["new_password"], PASSWORD_DEFAULT);
        } else {
            echo "New password and confirmation do not match! <br>"; // Handle error appropriately
            exit; // Stop further processing
        }
    }

    // Handle file uploads
    $photoPath = $_POST['existing_photo'];
    if (isset($_FILES['client_photo']) && $_FILES['client_photo']['error'] == 0) {
        $allowedTypes = array("image/jpeg", "image/jpg", "image/png", "image/gif", "image/webp");
        if (in_array($_FILES['client_photo']['type'], $allowedTypes)) {
            $ext = pathinfo($_FILES['client_photo']['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid() . "." . $ext;
            $targetPath = "uploads/" . $newFileName;

            if (move_uploaded_file($_FILES['client_photo']['tmp_name'], $targetPath)) {
                if ($photoPath && file_exists($photoPath) && $photoPath !== 'images/default_profile.jpg') {
                    unlink($photoPath);
                }
                $photoPath = $targetPath;
            } else {
                echo "File upload failed.";
            }
        } else {
            echo "Invalid file type.";
        }
    }


    try {
        // Construct and execute SQL UPDATE statement (dynamically include password field if changed)

        $updateFields = "client_name=?, client_email=?, client_phone=?, client_address=?, client_photo=?";
        $bindParams = "sssisi";
        $values = [$clientName, $clientEmail, $clientPhone, $clientAddress, $photoPath, $clientId];

        if (!empty($newPassword)) {
            $updateFields .= ", client_password=?";
            $bindParams .= "s";
            $values[] = $newPassword;
        }



        $stmt = $conn->prepare("UPDATE client SET $updateFields WHERE client_id=?");
        $stmt->bind_param($bindParams, ...$values);



        if ($stmt->execute()) {
            header("Location: client_dashboard.php"); // Redirect after successful update
            exit;
        } else {
            echo "Error updating profile: " . $stmt->error; // Handle the database error appropriately
        }
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage(); // Generic error message. Improve error handling in a production app.
    }



    $stmt->close();
    $conn->close();
} else {

    // Fetch existing data to populate form (same as before)
    $stmt = $conn->prepare("SELECT * FROM client WHERE client_id = ?");
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $result = $stmt->get_result();
    $client = $result->fetch_assoc();
    $stmt->close();
    $conn->close();


?>


    <!DOCTYPE html>
    <html>

    <head>
        <title>Edit Profile</title>
    </head>

    <body>
        <h2>Edit Your Profile</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data"><input type="hidden" name="client_id" value="<?php echo $client['client_id']; ?>"><label for="client_name">Name:</label><input type="text" id="client_name" name="client_name" value="<?php echo htmlspecialchars($client['client_name']); ?>" required><br><br><label for="client_email">Email:</label><input type="email" id="client_email" name="client_email" value="<?php echo htmlspecialchars($client['client_email']); ?>" required><br><br><label for="client_phone">Phone:</label><input type="tel" id="client_phone" name="client_phone" value="<?php echo htmlspecialchars($client['client_phone']); ?>" required><br><br><label for="client_address">Address:</label><textarea id="client_address" name="client_address"><?php echo htmlspecialchars($client['client_address']); ?></textarea><br><br><label for="client_photo">Profile Picture:</label><?php if ($client['client_photo']): ?><img src="<?php echo $client['client_photo']; ?>" alt="Profile Picture" width="100"><br><?php endif; ?><input type="file" name="client_photo" id="client_photo"><br><br><input type="hidden" name="existing_photo" value="<?php echo $client['client_photo']; ?>"><label for="new_password">New Password (leave blank to keep current password):</label><input type="password" id="new_password" name="new_password"><br><br><label for="confirm_password">Confirm New Password:</label><input type="password" id="confirm_password" name="confirm_password"><br><br><input type="submit" value="Update Profile"></form>
    </body>

    </html><?php } ?>