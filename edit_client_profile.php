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
        $bindParams = "sssssi";
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
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit <?php echo htmlspecialchars($client['client_name']); ?>'s Profile</title>
        <link rel="stylesheet" href="assets/css/semantic.css">
        <link rel="stylesheet" href="assets/font/fonts.css" />
    </head>

    <body>
        <!-- Nav Bar -->
        <div class="ui inverted segment">
            <div class="ui inverted secondary menu" style="font-family: 'Philosopher';">
                <div class="item" width="50px">
                    <img src="assets/images/logo.webp" alt="Company Logo" width="50px">
                </div>
                <a class="active item">
                    Edit Your Profile
                </a>
                <!-- <a class="item">
                Jobs
            </a>
            <a class="item">
                Locations
            </a> -->
                <div class="right menu">
                    <div class="item">
                        <a href="client_dashboard.php"><button class="ui right inverted teal labeled icon button">
                                <i class="asterisk loading icon"></i>
                                <span style="font-family: 'Sansumi';font-weight: 500;">Back to Dashboard</span>
                            </button></a>
                        <a href="logout.php"><button class="ui right inverted secondary labeled icon button">
                                <i class="sign out alternate icon"></i>
                                <span style="font-family: 'Sansumi';font-weight: 500;">Log out</span>
                            </button></a>
                        <!-- &nbsp; -->
                    </div>
                </div>
            </div>
        </div>
        <!-- menu begins -->
        <div class="ui fluid vertical menu" style="padding: 5px;">
            <span class="item" style="font-family: Neuropol;">Edit Your Profile Details - <strong><?php echo htmlspecialchars($client['client_name']); ?></strong></span>
        </div>
        <!-- menu ends -->
        <!-- Nav Bar ends -->
        <form class="ui form" style="padding: 25px;" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
            <span style="font-family: 'Orbit';">
                <input type="hidden" name="client_id" value="<?php echo $client['client_id']; ?>">
                <div class="three fluid fields">
                    <div class="required field">
                        <label for="client_name">Name:</label>
                        <input type="text" id="client_name" name="client_name" value="<?php echo htmlspecialchars($client['client_name']); ?>" required />
                    </div>
                    <div class="required field">
                        <label for="client_email">Email:</label>
                        <input type="email" id="client_email" name="client_email" value="<?php echo htmlspecialchars($client['client_email']); ?>" required />
                    </div>
                    <div class="required field">
                        <label for="client_phone">Phone:</label>
                        <input type="tel" id="client_phone" name="client_phone" value="<?php echo htmlspecialchars($client['client_phone']); ?>" required />
                    </div>
                </div>
                <div class="two fluid fields">
                    <div class="field">
                        <label for="client_photo">Profile Picture:</label>
                        <?php if ($client['client_photo']): ?>
                            <img src="<?php echo $client['client_photo']; ?>" alt="Profile Picture" width="100">
                        <?php endif; ?>
                    </div>
                    <div class="file input field">
                        <label for="event_photo">New Profile Photo:</label>
                        <input type="file" name="client_photo" id="client_photo" />
                        <input type="hidden" name="existing_photo" value="<?php echo $client['client_photo']; ?>" />
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <label for="new_password">New Password (leave blank to keep current password):</label>
                        <input type="password" id="new_password" name="new_password" />
                    </div>
                    <div class="field">
                        <label for="confirm_password">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" />
                    </div>
                </div>
                <div class="fluid field">
                    <label for="client_address">Address:</label>
                    <textarea id="client_address" name="client_address"><?php echo htmlspecialchars($client['client_address']); ?></textarea>
                </div>
                <button class="ui fluid button" type="submit" style="font-family: 'Neuropol';">Upadate the Profile</button>
            </span>
        </form>
        <!-- form ends -->
    </body>

    </html><?php } ?>