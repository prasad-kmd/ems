<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $venueId = $_POST['venue_id'];
    $venueName = $_POST["venue_name"];
    $venueDescription = $_POST["venue_description"];
    $venueAddress = $_POST["venue_address"];
    $venueCapacity = $_POST["venue_capacity"];
    $venueEmail = $_POST["venue_email"];
    $venuePhone = $_POST["venue_phone"];



    // Handle file upload (with optional file update and old image deletion)
    $photoPath = $_POST['existing_photo'];

    if (isset($_FILES['venue_photo']) && $_FILES['venue_photo']['error'] == 0) {
        $allowedTypes = array("image/jpeg", "image/jpg", "image/png", "image/gif", "image/webp");
        if (in_array($_FILES['venue_photo']['type'], $allowedTypes)) {
            $ext = pathinfo($_FILES['venue_photo']['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid() . "." . $ext;
            $targetPath = "uploads/" . $newFileName;

            if (move_uploaded_file($_FILES['venue_photo']['tmp_name'], $targetPath)) {

               if (!empty($photoPath) && file_exists($photoPath) && $photoPath !=='images/default_profile.jpg') { //Check if $photoPath not null or default
                  unlink($photoPath);    //Delete Old image
               }

                $photoPath = $targetPath;

            } else {
                 // Handle the upload error appropriately
            }
        } else {
            // Handle invalid file type...
        }
    }






    $stmt = $conn->prepare("UPDATE venue SET venue_name=?, venue_description=?, venue_photo=?, venue_address=?, venue_capacity=?, venue_email=?, venue_phone=? WHERE venue_id=?");
    $stmt->bind_param("ssssiisi", $venueName, $venueDescription, $photoPath, $venueAddress, $venueCapacity, $venueEmail, $venuePhone, $venueId);



    if ($stmt->execute()) {
        header("Location: manage_venues.php");
        exit;
    } else {
        echo "Error updating venue: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>