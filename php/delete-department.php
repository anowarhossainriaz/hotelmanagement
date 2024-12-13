<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

if (isset($_GET['id'])) {
    $departmentID = $_GET['id'];

    $sql = "DELETE FROM Department WHERE DepartmentID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $departmentID);

    if ($stmt->execute()) {
        header('Location: manage-departments.php');
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
