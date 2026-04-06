<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'];
    $seats = $_POST['seats'];

    if (empty($seats)) {
        $_SESSION['error'] = "Please select at least one seat.";
        header("Location: event_details.php?id=$event_id");
        exit;
    }

    $seatsArray = explode(',', $seats);

    $stmt = $conn->prepare("SELECT title, price FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        header("Location: dashboard.php");
        exit;
    }

    $total_price = count($seatsArray) * $event['price'];
} else {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Eventix</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="form-container large" style="margin: 40px auto;">
            <h2>Secure Checkout</h2>
            <div style="background: var(--bg-color); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <p><strong>Event:</strong> <?= htmlspecialchars($event['title']) ?></p>
                <p><strong>Seats:</strong> <?= htmlspecialchars($seats) ?></p>
                <h3 style="color: var(--secondary-color); margin-top: 10px;">Total Amount: $<?= number_format($total_price, 2) ?></h3>
            </div>
            
            <form action="book_tickets.php" method="POST">
                <input type="hidden" name="event_id" value="<?= htmlspecialchars($event_id) ?>">
                <input type="hidden" name="seats" value="<?= htmlspecialchars($seats) ?>">
                
                <h3 style="margin-bottom: 15px;">Payment Details</h3>
                <div class="form-group">
                    <label>Name on Card</label>
                    <input type="text" class="form-control" placeholder="John Doe" required>
                </div>
                <div class="form-group">
                    <label>Card Number</label>
                    <input type="text" class="form-control" placeholder="XXXX-XXXX-XXXX-XXXX" required>
                </div>
                <div style="display: flex; gap: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Expiry Date (MM/YY)</label>
                        <input type="text" class="form-control" placeholder="12/26" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>CVV</label>
                        <input type="password" class="form-control" placeholder="123" required>
                    </div>
                </div>
                <button type="submit" class="btn" style="margin-top: 10px; font-size: 1.1rem; padding: 15px;">Pay $<?= number_format($total_price, 2) ?> & Book Tickets</button>
            </form>
            <div style="text-align: center; margin-top: 15px;">
                <a href="event_details.php?id=<?= htmlspecialchars($event_id) ?>" style="color: var(--text-muted);">&larr; Cancel and Go Back</a>
            </div>
        </div>
    </div>
</body>
</html>
