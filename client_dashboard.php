<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: auth.html");
    exit;
}

require_once 'db_config.php';

$clientId = $_SESSION['client_id'];


$stmt = $conn->prepare("SELECT client_name, client_photo FROM client WHERE client_id = ?");
$stmt->bind_param("i", $clientId);
$stmt->execute();
$stmt->bind_result($clientName, $clientPhoto);
$stmt->fetch();
$stmt->close();
$conn->close();

$profilePicture = $clientPhoto ? $clientPhoto : 'images/default_profile.jpg';

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $clientName; ?>'s Dashboard</title>
    <link rel="stylesheet" href="assets/css/semantic.css">
    <link rel="stylesheet" href="assets/font/fonts.css" />
</head>

<body>
    <!-- Navigation Bar -->
    <div class="ui inverted segment">
        <div class="ui inverted secondary menu" style="font-family: 'Philosopher';">
            <div class="item" width="50px">
                <img src="assets/images/logo.webp" alt="Company Logo" width="50px">
            </div>
            <a class="active item">
                Client Dashboard
            </a>
            <div class="right menu">
                <div class="item">
                    <a href="edit_client_profile.php"><button class="ui right inverted yellow labeled icon button">
                            <i class="edit outline icon"></i>
                            <span style="font-family: 'Sansumi';font-weight: 500;">Profile</span>
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
    <!-- Navigation Bar Ends -->
    <!-- Titles -->
    <div class="ui segment">
        <div class="ui two column very relaxed grid">
            <div class="column">
                <h1 class="ui header" style="font-family: 'Google September 2015';">
                    <img src="<?php echo $profilePicture; ?>" alt="Client" class="ui avatar image" />
                    <div class="content">
                        Welcome Back, <span style="font-family: 'Philosopher';"><?php echo $clientName; ?></span> !
                        <div class="sub header">Let's book an Event !</div>
                    </div>
                </h1>
            </div>
            <div class="column">
                <h1 class="ui header" style="font-family: 'Google September 2015';">
                    <img src="assets/images/svg/dashboard_layout.svg" alt="Admin" class="ui image">
                    <div class="content">
                        Client Dashboard
                        <div class="sub header">Manage your preferences</div>
                    </div>
                </h1>
            </div>
        </div>
        <div class="ui vertical divider">
            &nbsp;
        </div>
    </div>
    <!-- titles Ends -->
    <!-- menu  -->
    <div class="ui fluid four item menu" style="padding: 5px;font-family: 'Nasalization Rg';">
        <a href="events/browse_events.php" class="item"><i class="server icon"></i>Browse Event</a>
        <a href="bookings/upcoming_bookings.php" class="item"><i class="chart line icon"></i>Upcoming Bookings</a>
        <a href="bookings/booking_history.php" class="item"><i class="history icon"></i>Booking History</a>
        <a href="edit_client_profile.php" class="item"><i class="edit icon"></i>Edit Profile</a>
    </div>
    <!-- menu ends -->
    <script src="assets/js/semantic.js"></script>
    <script src="assets/js/jquery-3.7.1.min.js"></script>
</body>

</html>