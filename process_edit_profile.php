<?php
session_start();

require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staffId = $_POST['staff_id'];
    $staffName = $_POST["staff_name"];
    $staffEmail = $_POST["staff_email"];
    $staffPhone = $_POST["staff_phone"];


    // Handle optional password change
    $newPassword = "";
    if (!empty($_POST["new_password"])) {
        if ($_POST["new_password"] === $_POST["confirm_password"]) {
            $newPassword = password_hash($_POST["new_password"], PASSWORD_DEFAULT); // Hash the password
        } else {
            // Passwords don't match! Handle error (e.g., display an error message and redirect back to edit_profile.php)
            echo "Passwords do not match. Please try again.<br>";
            echo "<a href='edit_profile.php'>Back to Edit Profile</a>";
            exit;
        }
    }

    // Handle File uploads if a new file was selected (similar to other file upload examples)
    $photoPath = $_POST['existing_photo']; // Get existing photo path from hidden field

    if (isset($_FILES['staff_photo']) && $_FILES['staff_photo']['error'] == 0) {
        $allowedTypes = array("image/jpeg", "image/jpg", "image/png", "image/gif", "image/webp");
        if (in_array($_FILES['staff_photo']['type'], $allowedTypes)) {

            $ext = pathinfo($_FILES['staff_photo']['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid() . "." . $ext;
            $targetPath = "uploads/" . $newFileName;

            if (move_uploaded_file($_FILES['staff_photo']['tmp_name'], $targetPath)) {
                // Delete the old image if it's not the default image
                if ($photoPath && file_exists($photoPath) && $photoPath !== 'images/default_profile.jpg') {
                    unlink($photoPath);
                }
                $photoPath = $targetPath;
            } else {
                // Handle the upload error
                echo "Error uploading file.";
            }
        } else {
            // Handle the invalid file type error
            echo "Invalid file type.";
        }
    }






    // Update staff information (two queries based on password change or not)
    try {


        if (empty($newPassword)) { // No password change
            $stmt = $conn->prepare("UPDATE staff SET staff_name=?, staff_email=?, staff_phone=?, staff_photo=? WHERE staff_id=?");
            $stmt->bind_param("ssssi", $staffName, $staffEmail, $staffPhone, $photoPath, $staffId);
        } else { // Password change

            $stmt = $conn->prepare("UPDATE staff SET staff_name=?, staff_email=?, staff_phone=?, staff_photo=?, staff_password=? WHERE staff_id=?");
            $stmt->bind_param("sssssi", $staffName, $staffEmail, $staffPhone, $photoPath, $newPassword, $staffId);
        }





        if ($stmt->execute()) {

            header("Location: admin_dashboard.php");
            exit;
        } else {
            echo "Error updating profile: " . $stmt->error; // Handle error appropriately
        }
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {

        echo "Error: " . $e->getMessage();
    }
}
