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
        <html>

        <head>
            <title>Edit Venue</title>
        </head>

        <body>

            <h2>Edit Venue</h2>
            <form action="process_edit_venue.php" method="post" enctype="multipart/form-data">


                <input type="hidden" name="venue_id" value="<?php echo $venue['venue_id']; ?>">

                <label for="venue_name">Name:</label>
                <input type="text" id="venue_name" name="venue_name" value="<?php echo isset($venue['venue_name']) ? htmlspecialchars($venue['venue_name']) : ''; ?>" required><br><br>


                <label for="venue_description">Description:</label>
                <textarea id="venue_description" name="venue_description"><?php echo isset($venue['venue_description']) ? htmlspecialchars($venue['venue_description']) : ''; ?></textarea><br><br>

                <label for="venue_photo">Photo:</label>

                <?php if ($venue['venue_photo']): ?>
                    <img src="<?php echo $venue['venue_photo'] ?>" alt="Venue Photo" width="150"><br>

                <?php endif; ?>




                <input type="file" id="venue_photo" name="venue_photo"><br><br>


                <input type="hidden" name="existing_photo" value="<?php echo $venue['venue_photo']; ?>">



                <label for="venue_address">Address:</label>
                <textarea id="venue_address" name="venue_address" required><?php echo isset($venue['venue_address']) ? htmlspecialchars($venue['venue_address']) : ''; ?></textarea><br><br>


                <label for="venue_capacity">Capacity:</label>
                <input type="number" id="venue_capacity" name="venue_capacity" value="<?php echo $venue['venue_capacity']; ?>" required><br><br>

                <label for="venue_email">Email:</label>
                <input type="email" id="venue_email" name="venue_email" value="<?php echo isset($venue['venue_email']) ? htmlspecialchars($venue['venue_email']) : ''; ?>" required><br><br>


                <label for="venue_phone">Phone:</label>
                <input type="tel" id="venue_phone" name="venue_phone" value="<?php echo isset($venue['venue_phone']) ? htmlspecialchars($venue['venue_phone']) : ''; ?>" required><br><br>

                <input type="submit" value="Update Venue">


            </form>




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