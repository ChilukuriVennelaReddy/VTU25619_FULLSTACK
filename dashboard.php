<?php
session_start();
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$stmt = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Eventix</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h2 style="margin-bottom: 20px;">Available Events</h2>
        <div class="events-grid">
            <?php foreach ($events as $event):
                $eventDate = strtotime($event['event_date']);
                $now = time();
                $status = 'upcoming';
                $statusClass = 'status-upcoming';
                if ($eventDate < $now) {
                    $status = 'completed';
                    $statusClass = 'status-completed';
                }
            ?>
            <div class="event-card">
                <img src="<?= htmlspecialchars($event['image_path'] ?: 'images/placeholder_event.jpg') ?>" alt="Event Image" class="event-image">
                <div class="event-details">
                    <span class="event-status <?= $statusClass ?>"><?= ucfirst($status) ?></span>
                    <h3 class="event-title"><?= htmlspecialchars($event['title']) ?></h3>
                    <p class="event-meta">📅 <?= date('M d, Y - h:i A', $eventDate) ?></p>
                    <p class="event-meta">📍 <?= htmlspecialchars($event['location']) ?></p>
                    <div class="event-price">$<?= number_format($event['price'], 2) ?></div>
                    <a href="event_details.php?id=<?= $event['id'] ?>" class="btn">View Details</a>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($events)): ?>
                <p>No events available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
