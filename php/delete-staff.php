<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

if (isset($_GET['id'])) {
    $staffID = $_GET['id'];

    $sql = "DELETE FROM Staff WHERE StaffID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $staffID);

    if ($stmt->execute()) {
        header('Location: manage-staff.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    header('Location: manage-staff.php');
    exit();
}
$conn->close();
?>
