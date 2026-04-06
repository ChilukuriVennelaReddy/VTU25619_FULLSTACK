<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Ticketing | Login & Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 style="text-align: center; color: var(--primary-color);">Eventix</h2>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div style="color: var(--error-color); margin-bottom: 15px; text-align: center; background: rgba(207,102,121,0.1); padding: 10px; border-radius: 4px;">
                    <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['success'])): ?>
                <div style="color: var(--secondary-color); margin-bottom: 15px; text-align: center; background: rgba(3,218,198,0.1); padding: 10px; border-radius: 4px;">
                    <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="auth-tabs">
                <div class="auth-tab active" id="login-tab">Login</div>
                <div class="auth-tab" id="register-tab">Register</div>
            </div>

            <form id="login-form" action="login_action.php" method="POST">
                <div class="form-group">
                    <label for="login-email">Email</label>
                    <input type="email" name="email" id="login-email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" name="password" id="login-password" class="form-control" required>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>

            <form id="register-form" action="register_action.php" method="POST" style="display: none;">
                <div class="form-group">
                    <label for="reg-username">Username</label>
                    <input type="text" name="username" id="reg-username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="reg-email">Email</label>
                    <input type="email" name="email" id="reg-email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="reg-password">Password</label>
                    <input type="password" name="password" id="reg-password" class="form-control" required minlength="6">
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
        </div>
    </div>
    <script src="js/main.js"></script>
</body>
</html>
