<?php
session_start();
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$event_id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Event not found.";
    exit;
}

// Fetch booked seats
$b_stmt = $conn->prepare("SELECT seats FROM bookings WHERE event_id = ?");
$b_stmt->execute([$event_id]);
$bookings = $b_stmt->fetchAll(PDO::FETCH_ASSOC);

$bookedSeats = [];
foreach ($bookings as $b) {
    if (!empty($b['seats'])) {
        $seatsArr = explode(',', $b['seats']);
        $bookedSeats = array_merge($bookedSeats, $seatsArr);
    }
}
$bookedSeats = array_map('trim', $bookedSeats);

$rows = ['A','B','C','D','E'];
$cols = 10;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($event['title']) ?> | Eventix</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        
        <?php if(isset($_SESSION['error'])): ?>
            <div style="color: var(--error-color); margin-bottom: 15px; background: rgba(207,102,121,0.1); padding: 10px; border-radius: 4px;">
                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="single-event">
            <div class="single-event-img">
                <img src="<?= htmlspecialchars($event['image_path'] ?: 'images/placeholder_event.jpg') ?>" alt="Event Image">
            </div>
            <div class="single-event-info">
                <h1 style="color: var(--primary-color); margin-bottom: 10px;"><?= htmlspecialchars($event['title']) ?></h1>
                <p class="event-meta" style="font-size: 1.1rem;">📅 <?= date('l, F j, Y \a\t g:i A', strtotime($event['event_date'])) ?></p>
                <p class="event-meta" style="font-size: 1.1rem;">📍 <?= htmlspecialchars($event['location']) ?></p>
                <p style="margin: 20px 0;"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                
                <h2 style="margin-top:30px;">Select Your Seats</h2>
                <div class="seat-map">
                    <?php
                    foreach ($rows as $r) {
                        for ($c = 1; $c <= $cols; $c++) {
                            $seatId = $r . $c;
                            $isBooked = in_array($seatId, $bookedSeats);
                            $class = $isBooked ? 'seat booked' : 'seat';
                            echo "<div class='$class' data-seat='$seatId'>$seatId</div>";
                        }
                    }
                    ?>
                </div>

                <div class="booking-summary">
                    <h3>Booking Summary</h3>
                    <p>Selected Seats: <span id="selected-seats-list" style="color: var(--secondary-color);">None</span></p>
                    <div class="total-price" id="total-price">$0.00</div>
                    
                    <form action="checkout.php" method="POST">
                        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                        <input type="hidden" id="selected-seats-input" name="seats" value="">
                        <input type="hidden" id="base-price" value="<?= $event['price'] ?>">
                        <button type="submit" class="btn" id="book-btn" disabled style="opacity: 0.5; cursor: not-allowed;">Book Tickets</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="js/main.js"></script>
</body>
</html>
