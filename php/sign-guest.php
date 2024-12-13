<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: user-login.php');
    exit();
}

require_once 'db_connection.php';

// Fetch logged-in user data (if any)
$userUsername = $_SESSION['user_username'];
$new_guest = null;

// Handle form submission for signing guest
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guest_name = $_POST['guest_name'];
    $contact_info = $_POST['contact_info'];
    $email = $_POST['email'];
    $nationality = $_POST['nationality'];
    $gender = $_POST['gender'];

    // Insert into database
    $query = "INSERT INTO Guest (Guestname, ContactInfo, Email, Nationality, Gender) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssss', $guest_name, $contact_info, $email, $nationality, $gender);

    if ($stmt->execute()) {
        // Get the ID of the newly inserted guest
        $new_guest_id = $conn->insert_id;

        // Fetch the newly added guest's details
        $details_query = "SELECT * FROM Guest WHERE GuestID = ?";
        $details_stmt = $conn->prepare($details_query);
        $details_stmt->bind_param('i', $new_guest_id);
        $details_stmt->execute();
        $result = $details_stmt->get_result();
        $new_guest = $result->fetch_assoc();

        // Store the guest ID in session for future access
        $_SESSION['guest_id'] = $new_guest['GuestID'];
    } else {
        $error_message = "Failed to sign guest. Please try again.";
    }
}

// Optionally, fetch existing guest information if needed
$guest_info = null;
if (isset($_SESSION['guest_id'])) {
    $guest_id = $_SESSION['guest_id'];

    $info_query = "SELECT * FROM Guest WHERE GuestID = ?";
    $info_stmt = $conn->prepare($info_query);
    $info_stmt->bind_param('i', $guest_id);
    $info_stmt->execute();
    $guest_info = $info_stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Guest - Hotel Management System</title>
    <link rel="stylesheet" href="../styles/guestu.css">
</head>
<body>

<!-- Navbar for User -->
<nav>
    <ul>
        <li><a href="user-dashboard.php">Dashboard</a></li>
        <li><a href="available-rooms.php">View Available Rooms</a></li>
        <li><a href="make-reservation.php">Make A Reservation</a></li>
        <li><a href="check-reservation.php">Check Reservation</a></li>
    </ul>
</nav>

<!-- Sign Guest Content -->
<section class="dashboard" style="background-image: url('../image/user-bg.jpg');">
    <h2>Sign Guest</h2>

    <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>

    <!-- Show guest information if they are already signed -->
    <?php if ($guest_info) { ?>
        <h3>Your Information</h3>
        <table>
            <tr><th>Guest ID</th><td><?php echo htmlspecialchars($guest_info['GuestID']); ?></td></tr>
            <tr><th>Name</th><td><?php echo htmlspecialchars($guest_info['Guestname']); ?></td></tr>
            <tr><th>Email</th><td><?php echo htmlspecialchars($guest_info['Email']); ?></td></tr>
            <tr><th>Contact Info</th><td><?php echo htmlspecialchars($guest_info['ContactInfo']); ?></td></tr>
            <tr><th>Nationality</th><td><?php echo htmlspecialchars($guest_info['Nationality'] ?? 'N/A'); ?></td></tr>
            <tr><th>Gender</th><td><?php echo htmlspecialchars($guest_info['Gender'] ?? 'N/A'); ?></td></tr>
        </table>
    <?php } ?>

    <!-- Sign Guest Form -->
    <form action="sign-guest.php" method="POST">
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

        <button type="submit">Sign Guest</button>
    </form>

    <!-- Optionally show the new guest details if added -->
    <?php if ($new_guest) { ?>
        <h3>New Guest Added Successfully</h3>
        <table>
            <tr><th>Guest ID</th><td><?php echo htmlspecialchars($new_guest['GuestID']); ?></td></tr>
            <tr><th>Name</th><td><?php echo htmlspecialchars($new_guest['Guestname']); ?></td></tr>
            <tr><th>Email</th><td><?php echo htmlspecialchars($new_guest['Email']); ?></td></tr>
            <tr><th>Contact Info</th><td><?php echo htmlspecialchars($new_guest['ContactInfo']); ?></td></tr>
            <tr><th>Nationality</th><td><?php echo htmlspecialchars($new_guest['Nationality'] ?? 'N/A'); ?></td></tr>
            <tr><th>Gender</th><td><?php echo htmlspecialchars($new_guest['Gender'] ?? 'N/A'); ?></td></tr>
        </table>
    <?php } ?>
</section>

</body>
</html>

<?php $conn->close(); ?>
