<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

// Fetch the hotel details based on the ID passed in the URL
if (isset($_GET['id'])) {
    $hotel_id = $_GET['id'];

    // Fetch hotel details from the database
    $query = "SELECT * FROM Hotel WHERE HotelID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $hotel_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $hotel = $result->fetch_assoc();
    } else {
        header('Location: manage-hotels.php');
        exit();
    }
}

// Handle form submission to update the hotel
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hotel_name = $_POST['hotel_name'];
    $contact_info = $_POST['contact_info'];
    $total_rooms = $_POST['total_rooms'];

    // Update hotel details in the database
    $query = "UPDATE Hotel SET Name = ?, ContactInfo = ?, TotalRooms = ? WHERE HotelID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssii', $hotel_name, $contact_info, $total_rooms, $hotel_id);

    if ($stmt->execute()) {
        header('Location: manage-hotels.php');
        exit();
    } else {
        $error_message = "Failed to update hotel. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hotel - Hotel Management System</title>
    <link rel="stylesheet" href="../styles/hotel.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="admin-dashboard.php">Dashboard</a></li>
            <li><a href="manage-hotels.php">Manage Hotels</a></li>
            <!-- Other navbar links -->
        </ul>
    </nav>

    <section>
        <h2>Edit Hotel</h2>

        <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>

        <form action="edit-hotel.php?id=<?php echo $hotel_id; ?>" method="POST">
            <label for="hotel_name">Hotel Name</label>
            <input type="text" id="hotel_name" name="hotel_name" value="<?php echo htmlspecialchars($hotel['Name']); ?>" required>

            <label for="contact_info">Contact Info</label>
            <input type="text" id="contact_info" name="contact_info" value="<?php echo htmlspecialchars($hotel['ContactInfo']); ?>" required>

            <label for="total_rooms">Total Rooms</label>
            <input type="number" id="total_rooms" name="total_rooms" value="<?php echo htmlspecialchars($hotel['TotalRooms']); ?>" required>

            <button type="submit">Update Hotel</button>
        </form>
    </section>
</body>
</html>

<?php $conn->close(); ?>
