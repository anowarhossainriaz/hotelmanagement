<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $guestName = trim($_POST['guest_name']);
    $contactInfo = trim($_POST['contact_info']);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $nationality = trim($_POST['nationality']);
    $gender = $_POST['gender'];

    if ($guestName && $contactInfo && $email && $nationality && $gender) {
        // Insert guest data into the database
        $sql = "INSERT INTO Guest (GuestName, ContactInfo, Email, Nationality, Gender) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $guestName, $contactInfo, $email, $nationality, $gender);

        if ($stmt->execute()) {
            // Redirect to manage-guest.php after successful addition
            header('Location: manage-guests.php');
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "All fields are required and email must be valid.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Guest</title>
</head>
<body>
    <h2>Add Guest</h2>
    <form action="add-guest.php" method="POST">
        <label for="guest_name">Guest Name</label>
        <input type="text" id="guest_name" name="guest_name" required>

        <label for="contact_info">Contact Info</label>
        <input type="text" id="contact_info" name="contact_info" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="nationality">Nationality</label>
        <input type="text" id="nationality" name="nationality" required>

        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>

        <button type="submit">Add Guest</button>
    </form>
</body>
</html>
