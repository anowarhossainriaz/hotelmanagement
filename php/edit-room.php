<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

// Fetch room details to edit
if (isset($_GET['id'])) {
    $room_id = $_GET['id'];
    $sql = "SELECT * FROM Room WHERE RoomNo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hotel_id = $_POST['hotel_id'];
    $category = $_POST['category'];
    $rent = $_POST['rent'];
    $status = $_POST['status'];

    // Update the room details in the database
    $update_query = "UPDATE Room SET HotelID = ?, Category = ?, Rent = ?, Status = ? WHERE RoomNo = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('isssi', $hotel_id, $category, $rent, $status, $room_id);

    if ($update_stmt->execute()) {
        // Redirect to manage rooms page after successful update
        header('Location: manage-rooms.php');
        exit();
    } else {
        $error_message = "Failed to update room. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room - Hotel Management System</title>
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
    <h2>Edit Room</h2>

    <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>

    <form action="edit-room.php?id=<?php echo $room_id; ?>" method="POST">
        <label for="hotel_id">Hotel</label>
        <select id="hotel_id" name="hotel_id" required>
            <?php
            // Fetching hotel options
            $hotelSql = "SELECT * FROM Hotel";
            $hotelResult = $conn->query($hotelSql);
            while ($hotel = $hotelResult->fetch_assoc()) {
                $selected = ($hotel['HotelID'] == $room['HotelID']) ? 'selected' : '';
                echo "<option value='" . $hotel['HotelID'] . "' $selected>" . htmlspecialchars($hotel['Name']) . "</option>";
            }
            ?>
        </select>

        <label for="category">Room Category</label>
        <select id="category" name="category" required>
            <option value="Single" <?php echo ($room['Category'] == 'Single') ? 'selected' : ''; ?>>Single</option>
            <option value="Double" <?php echo ($room['Category'] == 'Double') ? 'selected' : ''; ?>>Double</option>
        </select>

        <label for="rent">Rent</label>
        <input type="number" id="rent" name="rent" value="<?php echo htmlspecialchars($room['Rent']); ?>" required>

        <label for="status">Room Status</label>
        <select id="status" name="status" required>
            <option value="Available" <?php echo ($room['Status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
            <option value="Occupied" <?php echo ($room['Status'] == 'Occupied') ? 'selected' : ''; ?>>Occupied</option>
            <option value="Under Maintenance" <?php echo ($room['Status'] == 'Under Maintenance') ? 'selected' : ''; ?>>Under Maintenance</option>
        </select>

        <button type="submit">Update Room</button>
    </form>
</section>

</body>
</html>

<?php $conn->close(); ?>
