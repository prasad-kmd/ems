<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

if (isset($_GET['id'])) {
    $venueId = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM venue WHERE venue_id = ?");
    $stmt->bind_param("i", $venueId);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows > 0) {
        $venue = $result->fetch_assoc();
        // $conn->close();
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Venue Details</title>
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
                        Edit a Venue
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
            <!-- menu begins -->
            <div class="ui fluid vertical menu" style="padding: 5px;">
                <span class="item" style="font-family: Neuropol;">Edit Existing Venue Member</span>
            </div>
            <!-- menu ends -->
            <!-- Nav Bar ends -->
            <!-- form begins -->
            <form class="ui form" style="padding: 25px;" method="POST" action="process_edit_venue.php" enctype="multipart/form-data">
                <span style="font-family: 'Orbit';">
                    <input type="hidden" name="venue_id" value="<?php echo $venue['venue_id']; ?>">
                    <div class="four fluid fields">
                        <div class="required field">
                            <label for="venue_name">Name:</label>
                            <input type="text" id="venue_name" name="venue_name" value="<?php echo isset($venue['venue_name']) ? htmlspecialchars($venue['venue_name']) : ''; ?>" required>
                        </div>
                        <div class="required field">
                            <label for="venue_capacity">Capacity:</label>
                            <input type="number" id="venue_capacity" name="venue_capacity" value="<?php echo $venue['venue_capacity']; ?>" required>
                        </div>
                        <div class="required field">
                            <label for="venue_email">Email:</label>
                            <input type="email" id="venue_email" name="venue_email" value="<?php echo isset($venue['venue_email']) ? htmlspecialchars($venue['venue_email']) : ''; ?>" required>
                        </div>
                        <div class="required field">
                            <label for="venue_phone">Phone:</label>
                            <input type="tel" id="venue_phone" name="venue_phone" value="<?php echo isset($venue['venue_phone']) ? htmlspecialchars($venue['venue_phone']) : ''; ?>" required>
                        </div>
                    </div>
                    <div class="two fluid fields">
                        <div class="required field">
                            <label for="venue_photo">Photo:</label>
                            <?php if ($venue['venue_photo']): ?>
                                <img src="<?php echo $venue['venue_photo'] ?>" alt="Venue Photo" width="150"><br>

                            <?php endif; ?>
                        </div>
                        <div class="required field">
                            <label for="venue_photo">New Photo:</label>
                            <input type="file" id="venue_photo" name="venue_photo"><br><br>
                            <input type="hidden" name="existing_photo" value="<?php echo $venue['venue_photo']; ?>">
                        </div>
                    </div>
                    <div class="two fluid fields">
                        <div class="required field">
                            <label for="venue_address">Address:</label>
                            <textarea id="venue_address" name="venue_address" required><?php echo isset($venue['venue_address']) ? htmlspecialchars($venue['venue_address']) : ''; ?></textarea>
                        </div>
                        <div class="required field">
                            <label for="venue_description">Description:</label>
                            <textarea id="venue_description" name="venue_description"><?php echo isset($venue['venue_description']) ? htmlspecialchars($venue['venue_description']) : ''; ?></textarea>
                        </div>
                    </div>
                    <button class="ui fluid button" type="submit" style="font-family: 'Neuropol';">Upadate the Staff Details</button>
                </span>
            </form>
            <!-- form ends -->
            <script src="../assets/js/semantic.js"></script>
            <script src="../assets/js/jquery-3.7.1.min.js"></script>


        </body>

        </html>
<?php
        //Close the connection after use
        $conn->close();
    } else {

        echo "Venue not found."; //Handle appropriately

    }

    $stmt->close();
} else {

    echo "Invalid Venue ID.";
}



?>