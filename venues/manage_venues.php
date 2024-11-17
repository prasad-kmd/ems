<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Venues</title>
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
                Manage Venues
            </a>
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
        <span class="item" style="font-family: Neuropol;">Manage Existing Venues</span>
    </div>
    <!-- Nav Bar ends -->
    <a href="add_venue.php"><button class="fluid ui button" style="padding: 10px;">Add New Venue</button></a>
    <hr>
    <?php
    $venuesStmt = $conn->prepare("SELECT venue_id, venue_name, venue_address, venue_capacity FROM venue");
    $venuesStmt->execute();
    $venuesStmt->bind_result($venueId, $venueName, $venueAddress, $venueCapacity);
    ?>
    <!-- table begins -->
    <table class="ui celled striped compact padded teal table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>Capacity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($venuesStmt->fetch()): ?>
                <tr>
                    <td><?php echo $venueId; ?></td>
                    <td><?php echo $venueName; ?></td>
                    <td><?php echo $venueAddress; ?></td>
                    <td><?php echo $venueCapacity; ?></td>
                    <td>
                        <div class="ui animated button" tabindex="0"><a href="edit_venue.php?id=<?php echo $venueId; ?>">
                                <div class="visible content">Edit</div>
                                <div class="hidden content">
                                    <i class="edit outline icon"></i>
                                </div>
                            </a>
                        </div>
                        <div class="ui vertical animated button" tabindex="0"><a href="delete_venue.php?id=<?php echo $venueId; ?>" onclick="return confirm('Are you sure you want to delete this venue?')">
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
    $venuesStmt->close();
    $conn->close();
    ?>
</body>

</html>