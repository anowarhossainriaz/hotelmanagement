<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

// Delete hotel based on ID
if (isset($_GET['id'])) {
    $hotel_id = $_GET['id'];

    // Delete the hotel from the database
    $query = "DELETE FROM Hotel WHERE HotelID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $hotel_id);

    if ($stmt->execute()) {
        header('Location: manage-hotels.php');
        exit();
    } else {
        echo "Error: Unable to delete the hotel.";
    }
} else {
    header('Location: manage-hotels.php');
    exit();
}
?>

<?php $conn->close(); ?>
