<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

// Fetch booking data (including joined data from related tables)
$sql = "SELECT 
            b.booking_id,
            c.client_name,
            e.event_title,
            b.booking_date,
            b.number_guests,
            b.booking_status
        FROM booking AS b
        JOIN client AS c ON b.client_id = c.client_id
        JOIN event AS e ON b.event_id = e.event_id"; // Join with client and event tables

$result = $conn->query($sql);
$bookings = $result->fetch_all(MYSQLI_ASSOC);


$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
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
                Manage Bookings
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
                    <a href="logout.php"><button class="ui right inverted secondary labeled icon button">
                            <i class="sign out alternate icon"></i>
                            <span style="font-family: 'Sansumi';font-weight: 500;">Log out</span>
                        </button></a>
                    <!-- &nbsp; -->
                </div>
            </div>
        </div>
    </div>
    <div class="ui fluid vertical menu" style="padding: 5px;">
        <span class="item" style="font-family: Neuropol;">Manage Existing Bookings</span>
    </div>
    <!-- Nav Bar ends -->
    <!-- table begins -->
    <table class="ui celled striped compact padded blue table">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Client Name</th>
                <th>Event Title</th>
                <th>Booking Date</th>
                <th>Num. of Guests</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($bookings) > 0): ?>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo $booking['booking_id']; ?></td>
                        <td><?php echo $booking['client_name']; ?></td>
                        <td><?php echo $booking['event_title']; ?></td>
                        <td><?php echo $booking['booking_date']; ?></td>
                        <td><?php echo $booking['number_guests']; ?></td>
                        <td><?php echo $booking['booking_status']; ?></td>
                        <td>
                            <div class="ui animated button" tabindex="0"><a href="view_booking.php?id=<?php echo $booking['booking_id']; ?>">
                                    <div class="visible content">View</div>
                                    <div class="hidden content">
                                        <i class="eye icon"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="ui animated button" tabindex="0"><a href="edit_booking.php?id=<?php echo $booking['booking_id']; ?>">
                                    <div class="visible content">Edit</div>
                                    <div class="hidden content">
                                        <i class="pencil alternate icon"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="ui animated button" tabindex="0"><a href="process_booking.php?id=<?php echo $booking['booking_id']; ?>&action=approve" onclick="return confirm('Are you sure you want to approve this booking?')">
                                    <div class="visible content">Approve</div>
                                    <div class="hidden content">
                                        <i class="calendar check icon"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="ui vertical animated button" tabindex="0"><a href="process_booking.php?id=<?php echo $booking['booking_id']; ?>&action=reject" onclick="return confirm('Are you sure you want to reject this booking?')">
                                    <div class="visible content">Reject</div>
                                    <div class="hidden content">
                                        <i class="close icon"></i>
                                    </div>
                                </a>
                            </div>
                            <!-- <div class="ui animated button" tabindex="0"><a href="cancel_booking.php?id=<?php echo $booking['booking_id']; ?>" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                    <div class="visible content">Cancel</div>
                                    <div class="hidden content">
                                        <i class="calendar times icon"></i>
                                    </div>
                                </a>
                            </div> -->
                            <div class="ui vertical animated button" tabindex="0"><a href="delete_booking.php?id=<?php echo $booking['booking_id']; ?>" onclick="return confirm('Are you sure you want to delete this booking?')">
                                    <div class="visible content">Delete</div>
                                    <div class="hidden content">
                                        <i class="close icon"></i>
                                    </div>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No bookings found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- table ends -->
    <script src="../assets/js/semantic.js"></script>
    <script src="../assets/js/jquery-3.7.1.min.js"></script>
</body>

</html>