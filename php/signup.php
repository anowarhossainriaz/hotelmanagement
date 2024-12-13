<?php
session_start();
include('db_connection.php');

// Handle form submission for sign up
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contactInfo = $_POST['contactInfo'];
    $gender = $_POST['gender'];

    // Check if the username or email already exists
    $checkQuery = "SELECT * FROM User WHERE Username = ? OR Email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If username or email already exists, show error message
        $error_message = "Username or email already exists!";
    } else {
        // Insert the new user into the database
        $insertQuery = "INSERT INTO User (Username, Email, Password, ContactInfo, Gender) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('sssss', $username, $email, $password, $contactInfo, $gender);
        $stmt->execute();

        // Redirect to login page after successful sign-up
        header('Location: user-login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Sign Up</title>
    <link rel="stylesheet" href="../styles/signup1.css">
</head>
<body>

<div id="signup">
    <h2>User Sign Up</h2>
    <?php if (isset($error_message)) { echo "<p style='color: red;'>$error_message</p>"; } ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="contactInfo">Contact Info:</label>
            <input type="text" id="contactInfo" name="contactInfo">
        </div>

        <div class="form-group">
            <label for="gender">Gender:</label>
            <select id="gender" name="gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">Sign Up</button>
    </form>
</div>

</body>
</html>
