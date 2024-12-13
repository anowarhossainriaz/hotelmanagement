<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

// Fetch hotels from the database
$sql = "SELECT * FROM Hotel";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Hotels - Hotel Management System</title>
    <link rel="stylesheet" href="../styles/hotel.css">
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

<!-- Manage Hotels Content -->
<section class="dashboard" style="background-image: url('../image/HotelPic-8.jpg');">
    <h2>Manage Hotels</h2>

    <!-- Add Hotel Form -->
    <form action="add-hotel.php" method="POST">
        <label for="hotel_name">Hotel Name</label>
        <input type="text" id="hotel_name" name="hotel_name" required>

        <label for="contact_info">Contact Info</label>
        <input type="text" id="contact_info" name="contact_info" required>

        <label for="total_rooms">Total Rooms</label>
        <input type="number" id="total_rooms" name="total_rooms" required>

        <button type="submit">Add Hotel</button>
    </form>

    <h3>Existing Hotels</h3>

    <!-- Table to List Hotels -->
    <table>
        <thead>
            <tr>
                <th>Hotel Name</th>
                <th>Contact Info</th>
                <th>Total Rooms</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['ContactInfo']); ?></td>
                    <td><?php echo htmlspecialchars($row['TotalRooms']); ?></td>
                    <td>
                        <a href="edit-hotel.php?id=<?php echo $row['HotelID']; ?>">Edit</a> |
                        <a href="delete-hotel.php?id=<?php echo $row['HotelID']; ?>">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

</body>
</html>

<?php $conn->close(); ?>
