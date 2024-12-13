<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

// Fetch departments from the database
$sql = "SELECT D.DepartmentID, D.Name, D.DepartmentHead, D.DepartmentRole, D.StaffCount, H.Name AS HotelName 
        FROM Department D
        INNER JOIN Hotel H ON D.HotelID = H.HotelID";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Departments - Hotel Management System</title>
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

<section class="dashboard" style="background-image: url('../image/home-bg.jpg');">
    <h2>Manage Departments</h2>

    <!-- Add Department Form -->
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

    <h3>Existing Departments</h3>

    <!-- Table to List Departments -->
    <table>
        <thead>
            <tr>
                <th>Department Name</th>
                <th>Department Head</th>
                <th>Role</th>
                <th>Staff Count</th>
                <th>Hotel</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['DepartmentHead']); ?></td>
                        <td><?php echo htmlspecialchars($row['DepartmentRole']); ?></td>
                        <td><?php echo htmlspecialchars($row['StaffCount']); ?></td>
                        <td><?php echo htmlspecialchars($row['HotelName']); ?></td>
                        <td>
                            <a href="edit-department.php?id=<?php echo $row['DepartmentID']; ?>" class="edit-btn">Edit</a> |
                            <a href="delete-department.php?id=<?php echo $row['DepartmentID']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this department?');">Delete</a>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6">No departments found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

</body>
</html>

<?php $conn->close(); ?>
