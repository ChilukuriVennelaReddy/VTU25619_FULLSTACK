<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<nav class="navbar">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto;">
        <a href="dashboard.php" class="navbar-brand">Eventix</a>
        <ul class="nav-links">
            <li><a href="dashboard.php">Events</a></li>
            <li><a href="my_tickets.php">My Tickets</a></li>
            <li><span style="color: var(--text-muted);">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span></li>
            <li><a href="logout.php" class="btn btn-danger btn-small" style="color:#fff;">Logout</a></li>
        </ul>
    </div>
</nav>
