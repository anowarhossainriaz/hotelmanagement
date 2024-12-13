<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['staff_name'];
    $gender = $_POST['staff_gender'];
    $contactInfo = $_POST['contact_info'];
    $salary = $_POST['salary'];
    $departmentID = $_POST['department'];

    $sql = "INSERT INTO Staff (Name, Gender, ContactInfo, Salary, DepartmentID) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssdi', $name, $gender, $contactInfo, $salary, $departmentID);

    if ($stmt->execute()) {
        header('Location: manage-staff.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
