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

    // Fetch existing department details
    $sql = "SELECT * FROM Department WHERE DepartmentID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $departmentID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $department = $result->fetch_assoc();
    } else {
        echo "Department not found.";
        exit();
    }
    $stmt->close();
} else {
    header('Location: manage-departments.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $departmentID = $_POST['department_id'];
    $name = $_POST['department_name'];
    $head = $_POST['department_head'];
    $role = $_POST['department_role'];
    $contactInfo = $_POST['contact_info'];
    $staffCount = $_POST['staff_count'];

    $sql = "UPDATE Department 
            SET Name = ?, DepartmentHead = ?, DepartmentRole = ?, ContactInfo = ?, StaffCount = ? 
            WHERE DepartmentID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $name, $head, $role, $contactInfo, $staffCount, $departmentID);

    if ($stmt->execute()) {
        header('Location: manage-departments.php');
        exit();
    } else {
        $error_message = "Failed to update department. Please try again.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Department - Hotel Management System</title>
    <link rel="stylesheet" href="../styles/department.css">
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
    <h2>Edit Department</h2>

    <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>

    <form action="edit-department.php?id=<?php echo $departmentID; ?>" method="POST">
        <input type="hidden" name="department_id" value="<?php echo $department['DepartmentID']; ?>">

        <label for="department_name">Department Name</label>
        <input type="text" id="department_name" name="department_name" value="<?php echo htmlspecialchars($department['Name']); ?>" required>

        <label for="department_head">Department Head</label>
        <input type="text" id="department_head" name="department_head" value="<?php echo htmlspecialchars($department['DepartmentHead']); ?>" required>

        <label for="department_role">Department Role</label>
        <input type="text" id="department_role" name="department_role" value="<?php echo htmlspecialchars($department['DepartmentRole']); ?>" required>

        <label for="contact_info">Contact Info</label>
        <input type="text" id="contact_info" name="contact_info" value="<?php echo htmlspecialchars($department['ContactInfo']); ?>" required>

        <label for="staff_count">Staff Count</label>
        <input type="number" id="staff_count" name="staff_count" value="<?php echo htmlspecialchars($department['StaffCount']); ?>" required>

        <button type="submit">Save Changes</button>
    </form>
</section>

</body>
</html>
