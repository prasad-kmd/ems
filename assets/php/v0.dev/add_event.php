<?php
session_start();
include 'db_connection.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_title = $_POST['event_title'];
    $event_description = $_POST['event_description'];
    $event_date = $_POST['event_date'];
    $event_start_time = $_POST['event_start_time'];
    $event_endtime = $_POST['event_endtime'];
    $venue_id = $_POST['venue_id'];
    $event_capacity = $_POST['event_capacity'];
    $event_price = $_POST['event_price'];
    $staff_id = $_POST['staff_id'] ?: null;

    $stmt = $pdo->prepare("INSERT INTO event (event_title, event_description, event_date, event_start_time, event_endtime, venue_id, event_capacity, event_price, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$event_title, $event_description, $event_date, $event_start_time, $event_endtime, $venue_id, $event_capacity, $event_price, $staff_id])) {
        $_SESSION['success'] = "Event added successfully.";
    } else {
        $_SESSION['error'] = "Error adding event.";
    }

    header("Location: manage_events.php");
    exit();
} else {
    header("Location: manage_events.php");
    exit();
}
?>