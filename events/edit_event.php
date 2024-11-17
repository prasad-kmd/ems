<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

if (isset($_GET['id'])) {
    $eventId = $_GET['id'];

    // Fetch existing event details
    $stmt = $conn->prepare("SELECT * FROM event WHERE event_id = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();

        // Fetch venues for dropdown
        $venuesStmt = $conn->prepare("SELECT venue_id, venue_name FROM venue");
        $venuesStmt->execute();
        $venuesStmt->bind_result($venueId, $venueName);
        $venues = [];
        while ($venuesStmt->fetch()) {
            $venues[$venueId] = $venueName;
        }
        $venuesStmt->close();

        $conn->close(); // Close connection here


?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Event</title>
            <link rel="stylesheet" href="../assets/css/semantic.css">
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
                        Edit a Event
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
            <!-- menu begins -->
            <div class="ui fluid vertical menu" style="padding: 5px;">
                <span class="item" style="font-family: Neuropol;">Edit Existing Event</span>
            </div>
            <!-- menu ends -->
            <!-- Nav Bar ends -->
            <!-- form begins -->
            <form class="ui form" style="padding: 25px;" method="POST" action="process_edit_event.php" enctype="multipart/form-data">
                <span style="font-family: 'Orbit';">
                    <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                    <div class="required field">
                        <label for="event_title">Event Title:</label>
                        <input type="text" id="event_title" name="event_title" value="<?php echo $event['event_title']; ?>" required placeholder="Event Title" />
                    </div>
                    <div class="required field">
                        <label for="event_description">Event Description:</label>
                        <textarea rows="2" id="event_description" name="event_description" required placeholder="Event Description"><?php echo $event['event_description']; ?></textarea>
                    </div>
                    <div class="two fluid fields">
                        <div class="field">
                            <label>Current Event Photo:</label>
                            <img src="<?php echo $event['event_photo']; ?>" alt="Current Photo" class="ui small rounded image"/>
                        </div>
                        <div class="file input field">
                            <label for="event_photo">New Event Photo:</label>
                            <input type="file" id="event_photo" name="event_photo" />
                            <input type="hidden" name="existing_photo" value="<?php echo $event['event_photo']; ?>" />
                        </div>
                    </div>
                    <div class="four fields">
                        <div class="required field">
                            <label for="event_price">Price ($):</label>
                            <input type="number" id="event_price" name="event_price" value="<?php echo $event['event_price']; ?>" step="0.01" required>
                        </div>
                        <div class="required field">
                            <label for="event_date">Event Date:</label>
                            <input type="date" id="event_date" name="event_date" value="<?php echo $event['event_date']; ?>" required>
                        </div>
                        <div class="required field">
                            <label for="event_start_time">Start Time:</label>
                            <input type="time" id="event_start_time" name="event_start_time" value="<?php echo $event['event_start_time']; ?>" required>
                        </div>
                        <div class="required field">
                            <label for="event_end_time">End Time:</label>
                            <input type="time" id="event_end_time" name="event_end_time" value="<?php echo $event['event_end_time']; ?>" required>
                        </div>
                    </div>
                    <div class="three fields">
                        <div class="required field">
                            <label for="venue_id">Venue:</label>
                            <select id="venue_id" name="venue_id" required>
                                <?php foreach ($venues as $id => $name): ?>
                                    <option value="<?php echo $id; ?>" <?php if ($event['venue_id'] == $id) echo "selected"; ?>><?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="required field">
                            <label for="event_capacity">Capacity:</label>
                            <input type="number" id="event_capacity" name="event_capacity" value="<?php echo $event['event_capacity']; ?>" required placeholder="Capacity">
                        </div>
                        <div class="required field">
                            <label for="event_type">Event Type:</label>
                            <select id="event_type" name="event_type" required>
                                <option value="Conferences" <?php if ($event['event_type'] == 'Conferences') echo 'selected'; ?>>Conferences</option>
                                <option value="Seminars" <?php if ($event['event_type'] == 'Seminars') echo 'selected'; ?>>Seminars</option>
                                <option value="Sports Events" <?php if ($event['event_type'] == 'Sports Events') echo 'selected'; ?>>Sports Events</option>
                                <option value="Weddings" <?php if ($event['event_type'] == 'Weddings') echo 'selected'; ?>>Weddings</option>
                                <option value="Birthday Parties" <?php if ($event['event_type'] == 'Birthday Parties') echo 'selected'; ?>>Birthday Parties</option>
                                <option value="Webinars <?php if ($event['event_type'] == 'Webinars') echo 'selected'; ?>">Webinars</option>
                                <option value="Training Sessions / Workshops" <?php if ($event['event_type'] == 'Training Sessions / Workshops') echo 'selected'; ?>>Training Sessions / Workshops</option>
                                <option value="Product Launches" <?php if ($event['event_type'] == 'Product Launches') echo 'selected'; ?>>Product Launches</option>
                                <option value="Trade Shows / Exhibitions" <?php if ($event['event_type'] == 'Trade Shows / Exhibitions') echo 'selected'; ?>>Trade Shows / Exhibitions</option>
                                <option value="Non-profit / Fundraising Events" <?php if ($event['event_type'] == 'Non-profit / Fundraising Events') echo 'selected'; ?>>Non-profit / Fundraising Events</option>
                                <option value="Art and Cultural Events" <?php if ($event['event_type'] == 'Art and Cultural Events') echo 'selected'; ?>>Art and Cultural Events</option>
                                <option value="Festivals" <?php if ($event['event_type'] == 'Festivals') echo 'selected'; ?>>Festivals</option>
                                <option value="Fairs / Carnivals" <?php if ($event['event_type'] == 'Fairs / Carnivals') echo 'selected'; ?>>Fairs / Carnivals</option>
                                <option value="VIP Events" <?php if ($event['event_type'] == 'VIP Events') echo 'selected'; ?>>VIP Events</option>
                            </select>
                        </div>
                    </div>
                    <button class="ui button" type="submit" style="font-family: 'Neuropol';">Upadate the Event</button>
                </span>
            </form>
            <!-- form ends -->
            <script src="../assets/js/semantic.js"></script>
            <script src="../assets/js/jquery-3.7.1.min.js"></script>
        </body>

        </html>


<?php


    } else {
        echo "Event not found.";
    }
} else {
    echo "Invalid event ID.";
}


?>