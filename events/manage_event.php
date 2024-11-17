<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

//Fetch venues for the dropdown
$venuesStmt = $conn->prepare("SELECT venue_id, venue_name FROM venue");
$venuesStmt->execute();
$venuesStmt->bind_result($venueId, $venueName);
$venues = [];
while ($venuesStmt->fetch()) {
    $venues[$venueId] = $venueName;
}
$venuesStmt->close();



?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="../assets/css/semantic.css" />
    <link rel="stylesheet" href="../assets/font/fonts.css" />
</head>

<body>
    <!-- Nav Bar -->
    <div class="ui inverted segment">
        <div class="ui inverted secondary menu" style="font-family: 'Philosopher';">
            <div class="item" width="50px">
                <img src="../assets/images/logo.webp" alt="Company Logo" width="50px">
            </div>
            <a class="active item">
                Manage Events
            </a>
            <!-- <a class="item">
                Jobs
            </a>
            <a class="item">
                Locations
            </a> -->
            <div class="right menu">
                <div class="item">
                    <a href="../admin_dashboard.php"><button class="ui right inverted teal labeled icon button">
                            <i class="asterisk loading icon"></i>
                            <span style="font-family: 'Sansumi';font-weight: 500;">Back to Dashboard</span>
                        </button></a>
                    <a href="../logout.php"><button class="ui right inverted secondary labeled icon button">
                            <i class="sign out alternate icon"></i>
                            <span style="font-family: 'Sansumi';font-weight: 500;">Log out</span>
                        </button></a>
                    <!-- &nbsp; -->
                </div>
            </div>
        </div>
    </div>
    <div class="ui fluid vertical menu" style="padding: 5px;">
        <span class="item" style="font-family: Neuropol;">Manage Existing Events</span>
    </div>
    <!-- Nav Bar ends -->

    <?php
    $eventsStmt = $conn->prepare("SELECT event_id, event_title, event_date, event_status, event_type FROM event");
    $eventsStmt->execute();
    $eventsStmt->bind_result($eventId, $eventTitle, $eventDate, $eventStatus, $eventType);
    ?>
    <!-- table begins -->
    <table class="ui celled striped compact padded teal table">
        <thead>
            <tr>
                <th>Event ID</th>
                <th>Title</th>
                <th>Date</th>
                <th>Status</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($eventsStmt->fetch()): ?>
                <tr>
                    <td><?php echo $eventId; ?></td>
                    <td><?php echo $eventTitle; ?></td>
                    <td><?php echo $eventDate; ?></td>
                    <td><?php echo $eventStatus; ?></td>
                    <td><?php echo $eventType; ?></td>
                    <td>
                        <div class="ui animated button" tabindex="0"><a href="edit_event.php?id=<?php echo $eventId; ?>">
                                <div class="visible content">Edit</div>
                                <div class="hidden content">
                                    <i class="edit outline icon"></i>
                                </div>
                            </a>
                        </div>
                        <div class="ui vertical animated button" tabindex="0"><a href="delete_event.php?id=<?php echo $eventId; ?>" onclick="return confirm('Are you sure you want to delete this event?')">
                                <div class="visible content">Delete</div>
                                <div class="hidden content">
                                    <i class="archive icon"></i>
                                </div>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <!-- table ends -->
    <?php
    $eventsStmt->close();
    $conn->close();
    ?>
    <script src="../assets/js/semantic.js"></script>
    <script src="../assets/js/jquery-3.7.1.min.js"></script>
</body>

</html>