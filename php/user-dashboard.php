
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: user-login.php');
    exit();
}

// Fetch user data (optional: display logged-in user name)
$userUsername = $_SESSION['user_username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Hotel Management System</title>
    <link rel="stylesheet" href="../styles/userdash.css">
</head>
<body>

<!-- Navbar for User -->
<nav>
    <ul>
        <li><a href="user-dashboard.php">Dashboard</a></li>
        <li><a href="available-rooms.php">View Available Rooms</a></li>
        <li><a href="make-reservation.php">Make A Reservation</a></li>
        <li><a href="check-reservation.php">Check Reservation</a></li>
        <li><a href="sign-guest.php">Sign Guest</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<!-- User Dashboard Content -->
<section class="dashboard" style="background-image: url('../image/user-bg.jpg');">
    <h2>Welcome, User: <?php echo $userUsername; ?></h2>
    <p>Select an option below to manage your reservations</p>

    <div class="button-group">
        <a href="available-rooms.php" class="button">
            <img src="../image/HotelPic-1.jpg" alt="Available Rooms">
            View Available Rooms
        </a>
        <a href="make-reservation.php" class="button">
            <img src="../image/HotelPic-2.jpeg" alt="Make Reservation">
            Make A Reservation
        </a>
        <a href="check-reservation.php" class="button">
            <img src="../image/HotelPic-6.webp" alt="Check Reservation">
            Check Reservation
        </a>
        <a href="sign-guest.php" class="button">
            <img src="../image/HotelPic-8.jpg" alt="Sign Guest">
            Sign Guest
        </a>
    </div>
</section>

</body>
</html>

