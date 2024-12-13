<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

// Fetch reservations from the database
$sql = "SELECT r.ReservationID, r.RoomNo, r.CheckInDate, r.CheckOutDate, g.Guestname AS GuestName, h.Name AS HotelName
        FROM Reservation r
        JOIN Guest g ON r.GuestID = g.GuestID
        JOIN Room rm ON r.RoomNo = rm.RoomNo
        JOIN Hotel h ON rm.HotelID = h.HotelID";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reservations - Hotel Management System</title>
    <link rel="stylesheet" href="../styles/reservationcheck.css">
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
    </ul>
</nav>

<!-- View Reservations Content -->
<section class="dashboard" style="background-image: url('../image/user-bg.jpg');">
    <h2>View Reservations</h2>

    <!-- Table to List Reservations -->
    <table>
        <thead>
            <tr>
                <th>Reservation ID</th>
                <th>Guest Name</th>
                <th>Hotel</th>
                <th>Room No</th>
                <th>Check-In Date</th>
                <th>Check-Out Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ReservationID']); ?></td>
                        <td><?php echo htmlspecialchars($row['GuestName']); ?></td>
                        <td><?php echo htmlspecialchars($row['HotelName']); ?></td>
                        <td><?php echo htmlspecialchars($row['RoomNo']); ?></td>
                        <td><?php echo htmlspecialchars($row['CheckInDate']); ?></td>
                        <td><?php echo htmlspecialchars($row['CheckOutDate']); ?></td>
                    </tr>
            <?php }
            } else { ?>
                <tr>
                    <td colspan="6">No reservations found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

</body>
</html>

<?php $conn->close(); ?>
