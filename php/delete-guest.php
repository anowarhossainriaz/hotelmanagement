<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

// Delete guest based on ID
if (isset($_GET['id'])) {
    $guest_id = $_GET['id'];

    // Delete the guest from the database
    $query = "DELETE FROM Guest WHERE GuestID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $guest_id);

    if ($stmt->execute()) {
        header('Location: manage-guests.php');
        exit();
    } else {
        echo "Error: Unable to delete the guest.";
    }
} else {
    header('Location: manage-guests.php');
    exit();
}
?>

<?php $conn->close(); ?>
