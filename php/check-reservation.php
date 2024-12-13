<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: user-login.php');
    exit();
}

require_once 'db_connection.php';

// Get the logged-in guest's ID (assumed to be stored in session)
$guest_id = $_SESSION['guest_id']; // Make sure this session variable is set during login

// Fetch reservations for the logged-in guest from the database
$sql = "SELECT r.ReservationID, r.RoomNo, r.CheckInDate, r.CheckOutDate, g.Guestname AS GuestName, h.Name AS HotelName
        FROM Reservation r
        JOIN Guest g ON r.GuestID = g.GuestID
        JOIN Room rm ON r.RoomNo = rm.RoomNo
        JOIN Hotel h ON rm.HotelID = h.HotelID
        WHERE r.GuestID = ?";  // Filter by the logged-in guest's ID
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $guest_id); // Bind the guest_id to the SQL query
$stmt->execute();
$result = $stmt->get_result();
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

<!-- Navbar for User -->
<nav>
    <ul>
        <li><a href="user-dashboard.php">Dashboard</a></li>
        <li><a href="available-rooms.php">Available Rooms</a></li>
        <li><a href="make-reservation.php">Make Reservation</a></li>
    </ul>
</nav>

<!-- View Reservations Content -->
<section class="dashboard" style="background-image: url('../image/HotelPic-2.jpg');">
    <h2>View Your Reservations</h2>

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
