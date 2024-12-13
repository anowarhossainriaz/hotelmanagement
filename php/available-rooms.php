<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: user-login.php');
    exit();
}

require_once 'db_connection.php';

// Fetch available rooms from the database
$sql = "SELECT r.RoomNo, r.Category, r.Rent, r.Status, h.Name AS HotelName
        FROM Room r
        JOIN Hotel h ON r.HotelID = h.HotelID
        WHERE r.Status = 'Available'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Rooms - Hotel Management System</title>
    <link rel="stylesheet" href="../styles/aroom.css">
</head>
<body>

<!-- Navbar for User -->
<nav>
    <ul>
        <li><a href="user-dashboard.php">Dashboard</a></li>
        <li><a href="available-rooms.php">Available Rooms</a></li>
        <li><a href="make-reservation.php">Make Reservation</a></li>
    </ul>
</nav>

<!-- Available Rooms Content -->
<section class="dashboard" style="background-image: url('../image/user-bg.jpg');">
    <h2>Available Rooms</h2>

    <!-- Table to List Available Rooms -->
    <table>
        <thead>
            <tr>
                <th>Room Number</th>
                <th>Room Category</th>
                <th>Rent</th>
                <th>Hotel Name</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) { 
                while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['RoomNo']); ?></td>
                    <td><?php echo htmlspecialchars($row['Category']); ?></td>
                    <td><?php echo '$' . htmlspecialchars($row['Rent']); ?></td>
                    <td><?php echo htmlspecialchars($row['HotelName']); ?></td>
                </tr>
            <?php } 
            } else { ?>
                <tr>
                    <td colspan="4">No available rooms at the moment.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

</body>
</html>

<?php $conn->close(); ?>
