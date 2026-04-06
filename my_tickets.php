<?php
session_start();
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT b.*, e.title, e.event_date, e.location 
    FROM bookings b 
    JOIN events e ON b.event_id = e.id 
    WHERE b.user_id = ? 
    ORDER BY b.booking_date DESC
");
$stmt->execute([$user_id]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets | Eventix</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h2 style="margin-bottom: 20px;">My Tickets</h2>

        <?php if(isset($_SESSION['success'])): ?>
            <div style="color: var(--secondary-color); margin-bottom: 15px; background: rgba(3,218,198,0.1); padding: 10px; border-radius: 4px;">
                <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($tickets)): ?>
            <p>You have not booked any tickets yet. <a href="dashboard.php" style="color: var(--secondary-color);">Browse Events</a></p>
        <?php else: ?>
            <div style="max-width: 800px;">
                <?php foreach ($tickets as $ticket): ?>
                    <div class="ticket-card">
                        <div class="ticket-header">
                            <h3 class="ticket-title"><?= htmlspecialchars($ticket['title']) ?></h3>
                            <span style="color: var(--text-muted); font-size: 0.9rem;">Booked on <?= date('M d, Y', strtotime($ticket['booking_date'])) ?></span>
                        </div>
                        <div class="ticket-details">
                            <p><strong>Date & Time:</strong> <?= date('l, F j, Y \a\t g:i A', strtotime($ticket['event_date'])) ?></p>
                            <p><strong>Location:</strong> <?= htmlspecialchars($ticket['location']) ?></p>
                            <p><strong>Seats:</strong> <span style="color: var(--secondary-color); font-weight: bold;"><?= htmlspecialchars($ticket['seats']) ?></span></p>
                            <p><strong>Total Paid:</strong> $<?= number_format($ticket['total_price'], 2) ?></p>
                            <p style="margin-top: 15px; font-family: monospace; color: var(--text-muted);">TICKET ID: #TKT-<?= str_pad($ticket['id'], 6, '0', STR_PAD_LEFT) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
