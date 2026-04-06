<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $event_id = $_POST['event_id'];
    $seats = $_POST['seats'];

    if (empty($seats)) {
        $_SESSION['error'] = "Please select at least one seat.";
        header("Location: event_details.php?id=$event_id");
        exit;
    }

    $seatsArray = explode(',', $seats);

    // Fetch event price
    $stmt = $conn->prepare("SELECT price FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        $_SESSION['error'] = "Event not found.";
        header("Location: dashboard.php");
        exit;
    }

    // Double check if seats are already booked
    $b_stmt = $conn->prepare("SELECT seats FROM bookings WHERE event_id = ?");
    $b_stmt->execute([$event_id]);
    $bookings = $b_stmt->fetchAll(PDO::FETCH_ASSOC);
    $bookedSeats = [];
    foreach ($bookings as $b) {
        if (!empty($b['seats'])) {
            $bookedSeats = array_merge($bookedSeats, explode(',', $b['seats']));
        }
    }
    
    foreach ($seatsArray as $sq) {
        if (in_array(trim($sq), $bookedSeats)) {
            $_SESSION['error'] = "One or more selected seats have already been booked.";
            header("Location: event_details.php?id=$event_id");
            exit;
        }
    }

    $total_price = count($seatsArray) * $event['price'];

    try {
        $insert = $conn->prepare("INSERT INTO bookings (user_id, event_id, seats, total_price) VALUES (?, ?, ?, ?)");
        if ($insert->execute([$user_id, $event_id, $seats, $total_price])) {
            $_SESSION['success'] = "Tickets booked successfully!";
            header("Location: my_tickets.php");
            exit;
        } else {
            $_SESSION['error'] = "Booking failed.";
            header("Location: event_details.php?id=$event_id");
            exit;
        }
    } catch (PDOException $e) {
        $errorMsg = "Database error. Please try again.";
        if (strpos($e->getMessage(), 'FOREIGN KEY (`user_id`)') !== false) {
            $errorMsg = "Your user session is invalid because the database changed. Please click Logout and log in again.";
        }
        $_SESSION['error'] = $errorMsg;
        header("Location: event_details.php?id=$event_id");
        exit;
    }
} else {
    header("Location: dashboard.php");
    exit;
}
