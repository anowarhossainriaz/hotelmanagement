<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

// Handle form submission to add a new room
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hotel_id = $_POST['hotel_id'];
    $category = $_POST['category'];
    $rent = $_POST['rent'];
    $status = $_POST['status'];

    // Insert the new room into the database
    $query = "INSERT INTO Room (HotelID, Category, Rent, Status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isss', $hotel_id, $category, $rent, $status);

    if ($stmt->execute()) {
        // Redirect to the manage rooms page after successful insertion
        header('Location: manage-rooms.php');
        exit();
    } else {
        $error_message = "Failed to add room. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room - Hotel Management System</title>
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

<section>
    <h2>Add Room</h2>

    <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>

    <form action="add-room.php" method="POST">
        <label for="hotel_id">Hotel</label>
        <select id="hotel_id" name="hotel_id" required>
            <?php
            // Fetching hotel options
            $hotelSql = "SELECT * FROM Hotel";
            $hotelResult = $conn->query($hotelSql);
            while ($hotel = $hotelResult->fetch_assoc()) {
                echo "<option value='" . $hotel['HotelID'] . "'>" . htmlspecialchars($hotel['Name']) . "</option>";
            }
            ?>
        </select>

        <label for="category">Room Category</label>
        <select id="category" name="category" required>
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
</section>

</body>
</html>

<?php $conn->close();?>