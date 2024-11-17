<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../admin_login.php");
    exit;
}

require_once '../db_config.php';

if (isset($_GET['id'])) {
    $staffId = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id = ?");
    $stmt->bind_param("i", $staffId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $staff = $result->fetch_assoc();


?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Staff Details</title>
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
                        Edit a Staff Member
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
            <!-- menu begins -->
            <div class="ui fluid vertical menu" style="padding: 5px;">
                <span class="item" style="font-family: Neuropol;">Edit Existing Staff Member</span>
            </div>
            <!-- menu ends -->
            <!-- Nav Bar ends -->
            <!-- form begins -->
            <form class="ui form" style="padding: 25px;" method="POST" action="process_edit_staff.php" enctype="multipart/form-data">
                <span style="font-family: 'Orbit';">
                    <input type="hidden" name="staff_id" value="<?php echo $staff['staff_id']; ?>">
                    <div class="two fluid fields">
                        <div class="required field">
                            <label for="staff_name">Name:</label>
                            <input type="text" id="staff_name" name="staff_name" value="<?php echo $staff['staff_name']; ?>" required />
                        </div>
                        <div class="required field">
                            <label for="staff_email">Email:</label>
                            <input type="email" id="staff_email" name="staff_email" value="<?php echo $staff['staff_email']; ?>" required />
                        </div>
                    </div>
                    <div class="two fluid fields">
                        <div class="required field">
                            <label for="staff_phone">Phone:</label>
                            <input type="tel" id="staff_phone" name="staff_phone" value="<?php echo $staff['staff_phone']; ?>" required />
                        </div>
                        <div class="required field">
                            <label for="staff_role">Role:</label>
                            <select id="staff_role" name="staff_role" required>
                                <option value="Manager" <?php if ($staff['staff_role'] == 'Manager') echo 'selected'; ?>>Manager</option>
                                <option value="Event Organizer" <?php if ($staff['staff_role'] == 'Event Organizer') echo 'selected'; ?>>Event Organizer</option>
                                <option value="System Administrator" <?php if ($staff['staff_role'] == 'System Administrator') echo 'selected'; ?>>System Administrator</option>
                            </select>
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
        $conn->close(); // Close the connection after fetching the data
    } else {
        echo "Staff member not found.";
    }

    $stmt->close(); // Close the statement
} else {
    echo "Invalid staff ID.";
}
?>