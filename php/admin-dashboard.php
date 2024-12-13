<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

// Fetch admin data (optional: display logged-in admin name)
$adminUsername = $_SESSION['admin_username'];
?>

<!DOCTYPE html>
<html lang="en
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hotel Management System</title>
    <link rel="stylesheet" href="../styles/admin1.css">
</head>
<body>

<!-- Navbar for Admin -->
<nav>
    <ul>
        <li><a href="admin-dashboard.php">Dashboard</a></li>
        <li><a href="manage-hotels.php">Manage Hotels</a></li>
        <li><a href="manage-rooms.php">Manage Rooms</a></li>
        <li><a href="manage-guests.php">Manage Guests</a></li>
        <li><a href="manage-departments.php">Manage Departments</a></li>
        <li><a href="manage-staff.php">Manage Staff</a></li>
        <li><a href="reservations.php">View Reservations</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<!-- Admin Dashboard Content -->
<section class="dashboard" style="background-image: url('../image/HotelPic-6.webp');">
    <h2>Welcome, Admin: <?php echo $adminUsername; ?></h2>
    <p>Select an option below to manage the hotel system</p>

    <div class="button-group">
        <a href="manage-hotels.php" class="button">
            <img src="../image/admin-bg.png" alt="Manage Hotels">
            Manage Hotels
        </a>
        <a href="manage-rooms.php" class="button">
            <img src="../image/user-bg.jpg" alt="Manage Rooms">
            Manage Rooms
        </a>
        <a href="manage-guests.php" class="button">
            <img src="../image/Hotelpic-2.jpeg" alt="Manage Guests">
            Manage Guests
        </a>
        <a href="manage-departments.php" class="button">
            <img src="../image/HotelPic-5.jpg" alt="Manage Departments">
            Manage Departments
        </a>
        <a href="manage-staff.php" class="button">
            <img src="../image/HotelPic-4.jpg" alt="Manage Staff">
            Manage Staff
        </a>
        <a href="reservations.php" class="button">
            <img src="../image/HotelPic-3.jpg" alt="View Reservations">
            View Reservations
        </a>
    </div>
</section>

</body>
</html>

