<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'db_connection.php';

// Get the guest ID from the URL parameter
if (isset($_GET['id'])) {
    $guest_id = $_GET['id'];

    // Fetch the guest's details from the database
    $sql = "SELECT * FROM Guest WHERE GuestID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $guest_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the guest exists
    if ($result->num_rows > 0) {
        $guest = $result->fetch_assoc();
    } else {
        // Redirect if the guest is not found
        header('Location: manage-guests.php');
        exit();
    }
}

// Handle form submission to update guest details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guest_name = $_POST['guest_name'];
    $contact_info = $_POST['contact_info'];
    $email = $_POST['email'];
    $nationality = $_POST['nationality'];
    $gender = $_POST['gender'];

    // Update guest details in the database
    $query = "UPDATE Guest SET Guestname = ?, ContactInfo = ?, Email = ?, Nationality = ?, Gender = ? WHERE GuestID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssssi', $guest_name, $contact_info, $email, $nationality, $gender, $guest_id);

    if ($stmt->execute()) {
        header('Location: manage-guests.php'); // Redirect back to manage guests page after successful update
        exit();
    } else {
        $error_message = "Failed to update guest. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Guest - Hotel Management System</title>
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

<!-- Edit Guest Content -->
<section class="dashboard" style="background-image: url('../image/user-bg.jpg');">
    <h2>Edit Guest</h2>

    <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>

    <form action="edit-guest.php?id=<?php echo $guest['GuestID']; ?>" method="POST">
        <label for="guest_name">Guest Name</label>
        <input type="text" id="guest_name" name="guest_name" value="<?php echo htmlspecialchars($guest['Guestname']); ?>" required>

        <label for="contact_info">Contact Info</label>
        <input type="text" id="contact_info" name="contact_info" value="<?php echo htmlspecialchars($guest['ContactInfo']); ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($guest['Email']); ?>" required>

        <label for="nationality">Nationality</label>
        <input type="text" id="nationality" name="nationality" value="<?php echo htmlspecialchars($guest['Nationality']); ?>" required>

        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
            <option value="Male" <?php echo ($guest['Gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo ($guest['Gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
            <option value="Other" <?php echo ($guest['Gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
        </select>

        <button type="submit">Update Guest</button>
    </form>
</section>

</body>
</html>

<?php $conn->close(); ?>
