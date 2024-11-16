<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';

$staffId = $_SESSION['staff_id'];
$role = $_SESSION['staff_role'];

// Fetch staff details (for profile section - same as before)
$stmt = $conn->prepare("SELECT staff_name, staff_photo FROM staff WHERE staff_id = ?");
$stmt->bind_param("i", $staffId);
$stmt->execute();
$stmt->bind_result($staffName, $staffPhoto);
$stmt->fetch();
$stmt->close();
$conn->close(); //Close after fetching data

$profilePicture = $staffPhoto ? $staffPhoto : 'images/default_profile.jpg';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $staffName; ?>'s Dashboard (<?php echo $role; ?>)</title>
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
                Admin Dashboard
            </a>
            <!-- <a class="item">
                Jobs
            </a>
            <a class="item">
                Locations
            </a> -->
            <div class="right menu">
                <div class="item">
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
                    <img src="<?php echo $profilePicture; ?>" alt="Admin" class="ui avatar image" />
                    <div class="content">
                        Welcome Back, <span style="font-family: 'Philosopher';"><?php echo $staffName; ?></span> !
                        <div class="sub header">You're the <?php echo $role; ?> !</div>
                    </div>
                </h1>
            </div>
            <div class="column">
                <h1 class="ui header" style="font-family: 'Google September 2015';">
                    <img src="assets/images/svg/dashboard_layout.svg" alt="Admin" class="ui image">
                    <div class="content">
                        Administration Dashboard
                        <div class="sub header">Manage your <?php echo $role; ?> preferences</div>
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
    <div class="ui six item menu" style="padding: 5px;font-family: 'Nasalization Rg';">
        <a href="create_event.php" class="item"><i class="plus square outline icon"></i>Create Event</a>
        <a href="manage_events.php" class="item"><i class="bookmark outline icon"></i>Manage Events</a>
        <a href="manage_bookings.php" class="item"><i class="bookmark icon"></i>Manage Bookings</a>
        <a href="view_clients.php" class="item"><i class="quote left icon"></i>Client Informations</a>
        <a href="event_calendar.php" class="item"><i class="calendar alternate outline icon"></i>Event Calendar</a>
        <a href="reports.php" class="item"><i class="file alternate icon"></i>Generate Reports</a>
    </div>
    <!-- menu ends -->
    <div class="container">
        <h2>Welcome, <?php echo $staffName; ?> (<?php echo $role; ?>)!</h2>
        <!-- <img src="<?php echo $profilePicture; ?>" alt="Profile Picture"> -->

        <div class="dashboard-content">

            <?php if ($role == 'Manager' || $role == 'Event Organizer' || $role == 'System Administrator'): ?>
                <h3>Client Management</h3>
                <ul>
                    <li><a href="manage_clients.php">Manage Clients</a></li>
                </ul>
            <?php endif; ?>

            <?php if ($role == 'Manager' || $role == 'System Administrator'): ?>
                <h3>Staff Management</h3>
                <ul>
                    <li><a href="manage_staff.php">Manage Staff</a></li>
                </ul>
            <?php endif; ?>

            <?php if ($role == 'Manager' || $role == 'Event Organizer'): ?>
                <h3>Booking Management</h3>
                <ul>
                    <li><a href="manage_bookings.php">Manage Bookings</a></li>
                </ul>
            <?php endif; ?>

            <?php if ($role == 'Manager' || $role == 'Event Organizer'): ?>
                <h3>Payment Management</h3>
                <ul>
                    <li><a href="manage_payments.php">Manage Payments</a></li>
                </ul>
            <?php endif; ?>



            <?php if ($role == 'Manager' || $role == 'System Administrator'): ?>
                <h3>Event Management</h3>
                <ul>
                    <li><a href="manage_event.php">Manage Events</a></li>
                </ul>

                <h3>Venue Management</h3>
                <ul>
                    <li><a href="manage_venues.php">Manage Venues</a></li>
                </ul>
            <?php endif; ?>



            <?php if ($role == 'System Administrator'): ?>
                <h3>System Administration</h3>
                <ul>
                    <li><a href="backup_database.php">Backup Database</a></li>
                </ul>
            <?php endif; ?>

            <a href="edit_profile.php">profile edit</a>
        </div>
    </div>

    <script src="assets/js/semantic.js"></script>
    <script src="assets/js/jquery-3.7.1.min.js"></script>
</body>

</html>