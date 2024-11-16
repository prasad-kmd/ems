<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit();
}
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $venue_id = $_POST['venue_id'];
    $capacity = $_POST['capacity'];
    $status = $_POST['event_status'];
    $photo = '';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['photo']['name']);
        $photo = basename($_FILES['photo']['name']);

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            // File uploaded successfully
        } else {
            echo "Error uploading file.";
            exit();
        }
    }

    $sql = "UPDATE event SET event_title = ?, event_description = ?, event_photo = ?, event_date = ?, event_start_time = ?, event_end_time = ?, venue_id = ?, event_capacity = ?, event_status = ? WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssi", $title, $description, $photo, $date, $start_time, $end_time, $venue_id, $capacity, $status, $event_id);

    if ($stmt->execute()) {
        header("Location: manage_events.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>