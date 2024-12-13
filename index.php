<?php
session_start();

// Check if any user or admin is already logged in, redirect to respective dashboards
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: php/user-dashboard.php'); // Redirect to user dashboard if already logged in
    exit();
} elseif (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: php/admin-dashboard.php'); // Redirect to admin dashboard if already logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management System</title>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>

<!-- Landing Page Content -->
<section class="landing-page">
    <h1>Welcome to the Hotel Management System</h1>
    <p>Please choose your login type:</p>

    <!-- Adding a Picture -->
    <div>
        <img src="image/home-bg.jpg" alt="Hotel Management System" style="width: 100%; max-width: 600px; border-radius: 10px; margin-bottom: 20px;">
    </div>
    
    <!-- Login Options -->
    <div class="login-options">
        <a href="php/admin-login.php" class="button">Admin Login</a>
        <a href="php/user-login.php" class="button">User Login</a>
    </div>
</section>

</body>
</html>
