<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form inputs
    $hotelName = $_POST['hotel_name'];
    $contactInfo = $_POST['contact_info'];
    $totalRooms = $_POST['total_rooms'];

    // Validate input
    if (empty($hotelName) || empty($contactInfo) || empty($totalRooms)) {
        echo "All fields are required.";
        exit();
    }

    try {
        // Insert the new hotel into the database
        $sql = "INSERT INTO Hotel (Name, ContactInfo, TotalRooms) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $hotelName, $contactInfo, $totalRooms);

        if ($stmt->execute()) {
            // Redirect back to manage-hotels page with a success message
            header('Location: manage-hotels.php?message=Hotel added successfully!');
            exit();
        } else {
            echo "Error: Unable to add hotel.";
        }

        $stmt->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

$conn->close();
?>
