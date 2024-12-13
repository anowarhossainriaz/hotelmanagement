<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

// Fetch guests from the database
$sql = "SELECT * FROM Guest";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Guests - Hotel Management System</title>
    <link rel="stylesheet" href="../styles/guest.css">
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

<!-- Manage Guests Content -->
<section class="dashboard" style="background-image: url('../image/Hotelpic-2.jpeg');">
    <h2>Manage Guests</h2>

    <!-- Add Guest Form -->
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

    <h3>Existing Guests</h3>

    <!-- Table to List Guests -->
    <table>
        <thead>
            <tr>
                <th>Guest ID</th>
                <th>Guest Name</th>
                <th>Contact Info</th>
                <th>Email</th>
                <th>Nationality</th>
                <th>Gender</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['GuestID']); ?></td>
                    <td><?php echo htmlspecialchars($row['Guestname']); ?></td>
                    <td><?php echo htmlspecialchars($row['ContactInfo']); ?></td>
                    <td><?php echo htmlspecialchars($row['Email']); ?></td>
                    <td><?php echo htmlspecialchars($row['Nationality']); ?></td>
                    <td><?php echo htmlspecialchars($row['Gender']); ?></td>
                    <td>
                        <a href="edit-guest.php?id=<?php echo $row['GuestID']; ?>">Edit</a> |
                        <a href="delete-guest.php?id=<?php echo $row['GuestID']; ?>">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

</body>
</html>

<?php $conn->close(); ?>
