<?php
session_start();

require_once 'db_config.php';

if (isset($_POST['booking_id'], $_POST['payment_method'])) { // Make sure payment_method is received
    $bookingId = $_POST['booking_id'];
    $paymentMethod = $_POST['payment_method']; // Get the selected payment method



    $paymentStatus = 'success'; // Simulated successful payment
    $transactionId = uniqid(); // Generate a unique transaction ID

    try {


        $conn->begin_transaction();


        $updateBookingStmt = $conn->prepare("UPDATE booking SET booking_status = 'confirmed' WHERE booking_id = ?");
        $updateBookingStmt->bind_param("i", $bookingId);

        if (!$updateBookingStmt->execute()) {
            throw new Exception("Error confirming booking: " . $updateBookingStmt->error);
        }





        $getEventStmt = $conn->prepare("SELECT event_id, event_capacity, event_remain_capacity FROM event WHERE event_id = (SELECT event_id FROM booking WHERE booking_id = ?)");
        $getEventStmt->bind_param("i", $bookingId);
        $getEventStmt->execute();

        $getEventStmt->bind_result($eventId, $eventCapacity, $eventRemainCapacity);
        $getEventStmt->fetch();
        $getEventStmt->close();




        $bookedSeatsStmt = $conn->prepare("SELECT number_guests FROM booking WHERE booking_id = ?");

        $bookedSeatsStmt->bind_param("i", $bookingId);
        $bookedSeatsStmt->execute();
        $bookedSeatsStmt->bind_result($numGuests);
        $bookedSeatsStmt->fetch();

        $bookedSeatsStmt->close();




        $newRemainingCapacity = $eventRemainCapacity - $numGuests;




        $updateEventStmt = $conn->prepare("UPDATE event SET event_remain_capacity = ? WHERE event_id = ?");


        $updateEventStmt->bind_param("ii", $newRemainingCapacity, $eventId);


        if (!$updateEventStmt->execute()) {

            throw new Exception("Error updating event capacity: " . $updateEventStmt->error);
        }



        $insertPaymentStmt = $conn->prepare("INSERT INTO payment (booking_id, payment_status, payment_date, payment_time, payment_transaction_id, payment_method, payment_amount) VALUES (?, ?, CURDATE(), CURTIME(), ?, ?, ?)");


        $amountStmt = $conn->prepare("SELECT e.event_price * b.number_guests AS total_price FROM event e JOIN booking b ON b.event_id = e.event_id WHERE b.booking_id = ?");

        $amountStmt->bind_param("i", $bookingId);
        $amountStmt->execute();

        $amountStmt->bind_result($totalAmount);
        $amountStmt->fetch();
        $amountStmt->close();


        $insertPaymentStmt->bind_param("isssd", $bookingId, $paymentStatus, $transactionId, $paymentMethod, $totalAmount);
        if (!$insertPaymentStmt->execute()) {

            throw new Exception("Error recording payment:" . $insertPaymentStmt->error);
        }


        $conn->commit(); //Commit here

        header("Location: booking_confirmation.php?booking_id=" . $bookingId);
        exit;
    } catch (Exception $e) {

        $conn->rollback();  //Rollback if any of above queries fail.
        //Handle the error.  You might display the error message, but in production, log it.
        echo "An error occurred during payment processing : " . $e->getMessage();  //Handle error

    }



    // Close statements and connection
    $insertPaymentStmt->close();
    $updateEventStmt->close();
    $updateBookingStmt->close();
    $conn->close();
} else {
    //Handle the case where booking_id or payment_method aren't sent
    echo "Incomplete request."; //Handle appropriately

}
