<?php
session_start();

require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        isset($_POST['event_id'], $_POST['client_id'], $_POST['number_guests'])
    ) {
        $eventId = $_POST['event_id'];
        $clientId = $_POST['client_id'];
        $numGuests = $_POST['number_guests'];

        // Check for available seats (using a JOIN now)
        $stmt = $conn->prepare(
            'SELECT v.venue_capacity FROM event e JOIN venue v ON e.venue_id = v.venue_id WHERE e.event_id = ?'
        );
        $stmt->bind_param('i', $eventId);
        $stmt->execute();
        $stmt->bind_result($venueCapacity);
        $stmt->fetch();
        $stmt->close();

        $bookedSeatsStmt = $conn->prepare(
            "SELECT SUM(number_guests) AS total_booked FROM booking WHERE event_id = ? AND booking_status = 'confirmed'"
        );
        $bookedSeatsStmt->bind_param('i', $eventId);
        $bookedSeatsStmt->execute();
        $bookedSeatsResult = $bookedSeatsStmt->get_result();
        $bookedSeatsData = $bookedSeatsResult->fetch_assoc();
        $bookedSeats = $bookedSeatsData['total_booked'] ?? 0;

        $availableSeats = $venueCapacity - $bookedSeats;

        if ($numGuests > $availableSeats) {
            echo 'Error: Not enough available seats. Please try again.'; // Handle error appropriately
            exit();
        }

        $stmt = $conn->prepare(
            "INSERT INTO booking (event_id, client_id, booking_date, number_guests, booking_status) VALUES (?, ?, CURDATE(), ?, 'pending')"
        );
        $stmt->bind_param('iii', $eventId, $clientId, $numGuests);

        if ($stmt->execute()) {
            header(
                'Location: payment_gateway.php?booking_id=' . $stmt->insert_id
            ); // Redirect to payment gateway
            exit();
        } else {
            echo 'Error creating booking: ' . $stmt->error; // Handle error appropriately
        }

        $stmt->close();
    }
} elseif (isset($_GET['id'], $_GET['action'])) {
    // Existing booking actions (approve, reject, cancel)

    $bookingId = $_GET['id'];
    $action = $_GET['action'];

    switch ($action) {
        case 'approve':
            $newStatus = 'confirmed';
            break;

        case 'reject':
            $newStatus = 'cancelled';
            break;

        case 'cancel':
            $newStatus = 'cancelled';
            break;

        default:
            echo 'Invalid action.';
            exit();
    }

    $stmt = $conn->prepare(
        'UPDATE booking SET booking_status = ? WHERE booking_id = ?'
    );
    $stmt->bind_param('si', $newStatus, $bookingId);

    if ($stmt->execute()) {
        header('Location: manage_bookings.php');
        exit();
    } else {
        //Handle error
        echo 'Error updating booking: ' . $stmt->error;
    }
    $stmt->close();
} else {
    echo 'Invalid request.';
}

$conn->close();
