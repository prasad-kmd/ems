<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION["client_id"])) {
    header("Location: auth.html"); // Redirect to login page if not logged in
    exit;
}


require_once 'db_config.php';


// Fetch client details from the database
$stmt = $conn->prepare("SELECT client_name, client_photo FROM client WHERE client_id = ?");
$stmt->bind_param("i", $_SESSION["client_id"]);
$stmt->execute();
$stmt->bind_result($clientName, $clientPhoto);
$stmt->fetch();
$stmt->close();
$conn->close();


// Determine the profile picture to display
$profilePicture = $clientPhoto ? $clientPhoto : 'images/default_profile.png'; // Use default image if no photo uploaded


?>


<!DOCTYPE html>
<html>

<head>
    <title>Client Dashboard</title>
    <style>
        .profile-section {
            display: flex;
            align-items: center;
            /* Align items vertically */
            margin-bottom: 20px;
            /* Add some space below */
        }

        .profile-picture {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            /* Make it circular */
            margin-right: 10px;
            /* Add some space to the right */
        }
    </style>
</head>

<body>


    <div class="container">
        <div class="profile-section">
            <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="profile-picture">
            <h2>Welcome, <?php echo $clientName; ?>!</h2>
        </div>



    </div>


</body>

</html>