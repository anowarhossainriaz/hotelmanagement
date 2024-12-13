<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

// Fetch staff details to edit
if (isset($_GET['id'])) {
    $staffID = $_GET['id'];
    $sql = "SELECT * FROM Staff WHERE StaffID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $staffID);
    $stmt->execute();
    $result = $stmt->get_result();
    $staff = $result->fetch_assoc();
    $stmt->close();
} else {
    header('Location: manage-staff.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['staff_name'];
    $gender = $_POST['staff_gender'];
    $contactInfo = $_POST['contact_info'];
    $salary = $_POST['salary'];
    $departmentID = $_POST['department'];

    // Update the staff details in the database
    $update_query = "UPDATE Staff SET Name = ?, Gender = ?, ContactInfo = ?, Salary = ?, DepartmentID = ? WHERE StaffID = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('sssdii', $name, $gender, $contactInfo, $salary, $departmentID, $staffID);

    if ($update_stmt->execute()) {
        // Redirect to manage staff page after successful update
        header('Location: manage-staff.php');
        exit();
    } else {
        $error_message = "Failed to update staff. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff - Hotel Management System</title>
    <link rel="stylesheet" href="../styles/staff.css">
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
    <h2>Edit Staff</h2>

    <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>

    <form action="edit-staff.php?id=<?php echo $staffID; ?>" method="POST">
        <label for="staff_name">Staff Name</label>
        <input type="text" id="staff_name" name="staff_name" value="<?php echo htmlspecialchars($staff['Name']); ?>" required>

        <label for="staff_gender">Gender</label>
        <select id="staff_gender" name="staff_gender" required>
            <option value="Male" <?php echo ($staff['Gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo ($staff['Gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
        </select>

        <label for="contact_info">Contact Info</label>
        <input type="text" id="contact_info" name="contact_info" value="<?php echo htmlspecialchars($staff['ContactInfo']); ?>" required>

        <label for="salary">Salary</label>
        <input type="number" id="salary" name="salary" value="<?php echo htmlspecialchars($staff['Salary']); ?>" required>

        <label for="department">Department</label>
        <select id="department" name="department" required>
            <?php
            // Fetching department options
            $departments = $conn->query("SELECT * FROM Department");
            while ($department = $departments->fetch_assoc()) {
                $selected = ($department['DepartmentID'] == $staff['DepartmentID']) ? 'selected' : '';
                echo "<option value='" . $department['DepartmentID'] . "' $selected>" . htmlspecialchars($department['Name']) . "</option>";
            }
            ?>
        </select>

        <button type="submit">Update Staff</button>
    </form>
</section>

</body>
</html>

<?php $conn->close(); ?>
