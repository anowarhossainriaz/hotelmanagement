<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

// Check if the room ID is provided in the URL
if (isset($_GET['id'])) {
    $room_id = $_GET['id'];

    // Prepare the delete query
    $query = "DELETE FROM Room WHERE RoomNo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $room_id);

    if ($stmt->execute()) {
        // Redirect to manage rooms page after successful deletion
        header('Location: manage-rooms.php');
        exit();
    } else {
        $error_message = "Failed to delete room. Please try again.";
    }
} else {
    // Redirect if no room ID is provided
    header('Location: manage-rooms.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Room - Hotel Management System</title>
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
    <h2>Delete Room</h2>

    <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>

    <p>Are you sure you want to delete this room?</p>
    <form action="delete-room.php?id=<?php echo $_GET['id']; ?>" method="POST">
        <button type="submit">Yes, Delete Room</button>
        <a href="manage-rooms.php"><button type="button">Cancel</button></a>
    </form>
</section>

</body>
</html>

<?php $conn->close(); ?>
