<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

// Fetch rooms from the database
$sql = "SELECT r.RoomNo, r.Category, r.Rent, r.Status, h.Name AS HotelName 
        FROM Room r 
        JOIN Hotel h ON r.HotelID = h.HotelID";
$result = $conn->query($sql);

// Fetch all hotels for the room assignment
$hotelSql = "SELECT * FROM Hotel";
$hotelResult = $conn->query($hotelSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms - Hotel Management System</title>
    <link rel="stylesheet" href="../styles/room.css">
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

<!-- Manage Rooms Content -->
<section class="dashboard" style="background-image: url('../image/user-bg.jpg');">
    <h2>Manage Rooms</h2>

    <!-- Add Room Form -->
    <form action="add-room.php" method="POST">
        <label for="hotel_id">Hotel</label>
        <select id="hotel_id" name="hotel_id" required>
            <?php while ($hotel = $hotelResult->fetch_assoc()) { ?>
                <option value="<?php echo $hotel['HotelID']; ?>"><?php echo htmlspecialchars($hotel['Name']); ?></option>
            <?php } ?>
        </select>

        <label for="category">Room Category</label>
        <select id="category" name="status" required>
            <option value="Single">Single</option>
            <option value="Double">Double</option>
           
            </select>

        <label for="rent">Rent</label>
        <input type="number" id="rent" name="rent" required>

        <label for="status">Room Status</label>
        <select id="status" name="status" required>
            <option value="Available">Available</option>
            <option value="Occupied">Occupied</option>
            <option value="Under Maintenance">Under Maintenance</option>
        </select>

        <button type="submit">Add Room</button>
    </form>

    <h3>Existing Rooms</h3>

    <!-- Table to List Rooms -->
    <table>
        <thead>
            <tr>
                <th>Room No</th>
                <th>Hotel</th>
                <th>Category</th>
                <th>Rent</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['RoomNo']); ?></td>
                    <td><?php echo htmlspecialchars($row['HotelName']); ?></td>
                    <td><?php echo htmlspecialchars($row['Category']); ?></td>
                    <td><?php echo htmlspecialchars($row['Rent']); ?></td>
                    <td><?php echo htmlspecialchars($row['Status']); ?></td>
                    <td>
                        <a href="edit-room.php?id=<?php echo $row['RoomNo']; ?>">Edit</a> |
                        <a href="delete-room.php?id=<?php echo $row['RoomNo']; ?>">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

</body>
</html>

<?php $conn->close(); ?>
