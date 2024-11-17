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
$stmt = $conn->prepare("SELECT staff_name, staff_photo, staff_role FROM staff WHERE staff_id = ?");
$stmt->bind_param("i", $staffId);
$stmt->execute();
$stmt->bind_result($staffName, $staffPhoto, $role);
$stmt->fetch();
$stmt->close();
// $conn->close(); //Close after fetching data

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
            <div class="right menu">
                <div class="item">
                    <a href="edit_profile.php"><button class="ui right inverted yellow labeled icon button">
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
    <div class="ui fluid five item menu" style="padding: 5px;font-family: 'Nasalization Rg';">
        <?php if ($role == 'Manager' || $role == 'Event Organizer' || $role == 'System Administrator'): ?>
            <a href="events/add_event.php" class="item"><i class="plus square outline icon"></i>Create Event</a>
        <?php endif; ?>
        <?php if ($role == 'Manager' || $role == 'System Administrator'): ?>
            <a href="events/manage_event.php" class="item"><i class="bookmark outline icon"></i>Manage Events</a>
        <?php endif; ?>
        <?php if ($role == 'Manager' || $role == 'Event Organizer'): ?>
            <a href="bookings/manage_bookings.php" class="item"><i class="bookmark icon"></i>Manage Bookings</a>
        <?php endif; ?>
        <?php if ($role == 'Manager' || $role == 'Event Organizer' || $role == 'System Administrator'): ?>
            <a href="clients/manage_clients.php" class="item"><i class="quote left icon"></i>Manage Clients</a>
        <?php endif; ?>
        <a href="#" class="item disabled"><i class="calendar alternate outline icon"></i>Event Calendar</a>
    </div>
    <div class="ui fluid five item menu" style="padding: 5px;font-family: 'Nasalization Rg';">
        <a href="#" class="item disabled"><i class="file alternate icon"></i>Generate Reports</a>
        <?php if ($role == 'Manager' || $role == 'System Administrator'): ?>
            <a href="staff/manage_staff.php" class="item"><i class="user secret icon"></i>Manage Staff</a>
        <?php endif; ?>
        <?php if ($role == 'Manager' || $role == 'Event Organizer'): ?>
            <a href="payments/manage_payments.php" class="item"><i class="dollar sign icon"></i>Manage Payments</a>
        <?php endif; ?>
        <?php if ($role == 'Manager' || $role == 'Event Organizer'): ?>
            <a href="venues/manage_venues.php" class="item"><i class="location arrow icon"></i>Manage Venues</a>
        <?php endif; ?>
        <?php if ($role == 'System Administrator'): ?>
            <a href="backup_database.php" class="item"><i class="archive icon"></i>Backup Database</a>
        <?php endif; ?>
    </div>
    <!-- menu ends -->
    <!-- cards begins -->
    <div class="ui segments">
        <div class="ui center aligned segment">
            <h2 style="font-family: 'Google Sans';">System Statistic</h2>
        </div>
        <div class="ui center aligned horizontal segments">
            <div class="ui red segment">
                <div class="ui inverted segment" id="dash1">
                    <div class="ui active inverted placeholder">
                        <div class="image header">
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                        <div class="paragraph">
                            <div class="line"></div>
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                    </div>
                </div>
                <div class="ui fluid card" id="dash1h" style="display: none;">
                    <div class="center aligned content">
                        <div class="header" style="font-family: 'El Messiri';">Total Planned Events</div>
                    </div>
                    <div class="content">
                        <h2 class="ui center aligned icon header red">
                            <?php
                            $totalEvents = $conn->query("SELECT COUNT(*) AS total FROM event")->fetch_assoc()['total'];
                            ?>
                            <i class="calendar alternate outline icon"></i>
                            <span class="value" style="font-family: 'El Messiri';"><?php echo $totalEvents ?></span>
                        </h2>
                    </div>
                    <div class="center aligned extra content">
                        <button class="ui inverted grey button"><a href="events/manage_event.php" style="font-family: 'El Messiri';">Manage Events</a></button>
                    </div>
                </div>
            </div>
            <div class="ui blue segment">
                <div class="ui inverted segment" id="dash2">
                    <div class="ui active inverted placeholder">
                        <div class="image header">
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                        <div class="paragraph">
                            <div class="line"></div>
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                    </div>
                </div>
                <div class="ui fluid card" id="dash2h" style="display: none;">
                    <div class="center aligned content">
                        <div class="header" style="font-family: 'El Messiri';">Total Registered Clients</div>
                    </div>
                    <div class="content">
                        <h2 class="ui center aligned icon header blue">
                            <?php
                            $totalClients = $conn->query("SELECT COUNT(*) AS total FROM client")->fetch_assoc()['total'];
                            ?>
                            <i class="user circle outline icon"></i>
                            <span class="value" style="font-family: 'El Messiri';"><?php echo $totalClients ?></span>
                        </h2>
                    </div>
                    <div class="center aligned extra content">
                        <button class="ui inverted grey button"><a href="clients/manage_clients.php" style="font-family: 'El Messiri';">Manage Clients</a></button>
                    </div>
                </div>
            </div>
            <div class="ui orange segment">
                <div class="ui inverted segment" id="dash3">
                    <div class="ui active inverted placeholder">
                        <div class="image header">
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                        <div class="paragraph">
                            <div class="line"></div>
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                    </div>
                </div>
                <div class="ui fluid card" id="dash3h" style="display: none;">
                    <div class="center aligned content">
                        <div class="header" style="font-family: 'El Messiri';">Total Bookings</div>
                    </div>
                    <div class="content">
                        <h2 class="ui center aligned icon header orange">
                            <?php
                            $totalBookings = $conn->query("SELECT COUNT(*) AS total FROM booking")->fetch_assoc()['total'];
                            ?>
                            <i class="book icon"></i>
                            <span class="value" style="font-family: 'El Messiri';"><?php echo $totalBookings ?></span>
                        </h2>
                    </div>
                    <div class="center aligned extra content">
                        <button class="ui inverted grey button"><a href="bookings/manage_bookings.php" style="font-family: 'El Messiri';">Manage Bookings</a></button>
                    </div>
                </div>
            </div>
            <div class="ui green segment">
                <div class="ui inverted segment" id="dash4">
                    <div class="ui active inverted placeholder">
                        <div class="image header">
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                        <div class="paragraph">
                            <div class="line"></div>
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                    </div>
                </div>
                <div class="ui fluid card" id="dash4h" style="display: none;">
                    <div class="center aligned content">
                        <div class="header" style="font-family: 'El Messiri';">Total Revenue</div>
                    </div>
                    <div class="content">
                        <h2 class="ui center aligned icon header green">
                            <?php
                            $totalRevenue = $conn->query("SELECT SUM(payment_amount) AS total FROM payment")->fetch_assoc()['total'];
                            ?>
                            <i class="gem icon"></i>
                            <span class="value" style="font-family: 'El Messiri';">LKR <?php echo $totalRevenue ?></span>
                        </h2>
                    </div>
                    <div class="center aligned extra content">
                        <button class="ui inverted grey button"><a href="payments/manage_payments.php" style="font-family: 'El Messiri';">Manage Payments</a></button>
                    </div>
                </div>
            </div>
            <div class="ui purple segment">
                <div class="ui inverted segment" id="dash5">
                    <div class="ui active inverted placeholder">
                        <div class="image header">
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                        <div class="paragraph">
                            <div class="line"></div>
                            <div class="line"></div>
                            <div class="line"></div>
                        </div>
                    </div>
                </div>
                <div class="ui fluid card" id="dash5h" style="display: none;">
                    <div class="center aligned content">
                        <div class="header" style="font-family: 'El Messiri';">Total Venues Arranged</div>
                    </div>
                    <div class="content">
                        <h2 class="ui center aligned icon header purple">
                            <?php
                            $totalVenues = $conn->query("SELECT COUNT(*) AS total FROM venue")->fetch_assoc()['total'];
                            ?>
                            <i class="street view icon"></i>
                            <span class="value" style="font-family: 'El Messiri';"><?php echo $totalVenues ?></span>
                        </h2>
                    </div>
                    <div class="center aligned extra content">
                        <button class="ui primary disabled loading button">&nbsp;&nbsp;&nbsp;</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- cards ends -->

    <script src="assets/js/semantic.js"></script>
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script>
        function randomTimeout(id1, id2, delay) {
            setTimeout(function() {
                document.getElementById(id1).style.display = 'none';
                document.getElementById(id2).style.display = 'block';
            }, delay);
        }

        function randomTimeoutSequence() {
            const min = 1000;
            const max = 7500;

            randomTimeout('dash1', 'dash1h', Math.floor(Math.random() * (max - min + 1)) + min);
            randomTimeout('dash2', 'dash2h', Math.floor(Math.random() * (max - min + 1)) + min);
            randomTimeout('dash3', 'dash3h', Math.floor(Math.random() * (max - min + 1)) + min);
            randomTimeout('dash4', 'dash4h', Math.floor(Math.random() * (max - min + 1)) + min);
            randomTimeout('dash5', 'dash5h', Math.floor(Math.random() * (max - min + 1)) + min);
        }

        randomTimeoutSequence();
    </script>
</body>

</html>