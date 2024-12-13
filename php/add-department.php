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
    $hotelId = $_POST['hotel_id'] ?? null;
    $departmentName = $_POST['department_name'] ?? null;
    $departmentHead = $_POST['department_head'] ?? null;
    $departmentRole = $_POST['department_role'] ?? null;
    $contactInfo = $_POST['contact_info'] ?? null;
    $staffCount = $_POST['staff_count'] ?? null;

    if ($hotelId && $departmentName && $departmentHead && $departmentRole && $contactInfo && $staffCount) {
        $sql = "INSERT INTO Department (HotelID, Name, DepartmentHead, DepartmentRole, ContactInfo, StaffCount) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssi", $hotelId, $departmentName, $departmentHead, $departmentRole, $contactInfo, $staffCount);

        if ($stmt->execute()) {
            header('Location: manage-departments.php');
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Department</title>
</head>
<body>
    <h2>Add Department</h2>
    <form action="add-department.php" method="POST">
        <label for="hotel_id">Hotel</label>
        <select id="hotel_id" name="hotel_id" required>
            <?php
            $hotels = $conn->query("SELECT * FROM Hotel");
            while ($hotel = $hotels->fetch_assoc()) {
                echo '<option value="' . $hotel['HotelID'] . '">' . htmlspecialchars($hotel['Name']) . '</option>';
            }
            ?>
        </select>

        <label for="department_name">Department Name</label>
        <input type="text" id="department_name" name="department_name" required>

        <label for="department_head">Department Head</label>
        <input type="text" id="department_head" name="department_head" required>

        <label for="department_role">Department Role</label>
        <input type="text" id="department_role" name="department_role" required>

        <label for="contact_info">Contact Info</label>
        <input type="text" id="contact_info" name="contact_info" required>

        <label for="staff_count">Staff Count</label>
        <input type="number" id="staff_count" name="staff_count" required>

        <button type="submit">Add Department</button>
    </form>
</body>
</html>
