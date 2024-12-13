<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

// Fetch staff from the database
$sql = "SELECT s.StaffID, s.Name AS StaffName, s.Gender, s.ContactInfo, s.Salary, d.Name AS DepartmentName
        FROM Staff s
        JOIN Department d ON s.DepartmentID = d.DepartmentID";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff - Hotel Management System</title>
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

<!-- Manage Staff Content -->
<section class="dashboard" style="background-image: url('../image/HotelPic-7.jpg');">
    <h2>Manage Staff</h2>

    <!-- Add Staff Form -->
    <form action="add-staff.php" method="POST">
        <label for="staff_name">Staff Name</label>
        <input type="text" id="staff_name" name="staff_name" required>

        <label for="staff_gender">Gender</label>
        <select id="staff_gender" name="staff_gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>

        <label for="contact_info">Contact Info</label>
        <input type="text" id="contact_info" name="contact_info" required>

        <label for="salary">Salary</label>
        <input type="number" id="salary" name="salary" required>

        <label for="department">Department</label>
        <select id="department" name="department" required>
            <?php
            // Fetch all departments to populate the dropdown
            $departmentQuery = "SELECT * FROM Department";
            $departmentResult = $conn->query($departmentQuery);
            while ($department = $departmentResult->fetch_assoc()) {
                echo "<option value='" . $department['DepartmentID'] . "'>" . htmlspecialchars($department['Name']) . "</option>";
            }
            ?>
        </select>

        <button type="submit">Add Staff</button>
    </form>

    <h3>Existing Staff</h3>

    <!-- Table to List Staff -->
    <table>
        <thead>
            <tr>
                <th>Staff Name</th>
                <th>Gender</th>
                <th>Contact Info</th>
                <th>Salary</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['StaffName']); ?></td>
                    <td><?php echo htmlspecialchars($row['Gender']); ?></td>
                    <td><?php echo htmlspecialchars($row['ContactInfo']); ?></td>
                    <td><?php echo htmlspecialchars($row['Salary']); ?></td>
                    <td><?php echo htmlspecialchars($row['DepartmentName']); ?></td>
                    <td>
                        <a href="edit-staff.php?id=<?php echo $row['StaffID']; ?>">Edit</a> |
                        <a href="delete-staff.php?id=<?php echo $row['StaffID']; ?>">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

</body>
</html>

<?php $conn->close(); ?>
