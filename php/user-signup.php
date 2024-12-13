<!-- user-signup.php -->
<?php
session_start();
include('db_connection.php'); // Include database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contact_info = $_POST['contact_info'];
    $gender = $_POST['gender'];

    // Insert user data into the User table
    $query = "INSERT INTO User (Username, Email, Password, ContactInfo, Gender) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssss', $username, $email, $password, $contact_info, $gender);

    if ($stmt->execute()) {
        // Success: Redirect to user login page
        header('Location: user-login.php');
        exit();
    } else {
        $error_message = "Error: Unable to create account.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Signup - Hotel Management System</title>
    <link rel="stylesheet" href="../styles/usersign.css">
</head>
<body>

<div class="signup-container" style="background-image: url('image/home-bg.jpg');">
    <div class="signup-box">
        <h2>Create Account</h2>
        <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
        
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
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="contact_info">Contact Info:</label>
                <input type="text" id="contact_info" name="contact_info" required>
            </div>
            
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <button type="submit">Sign Up</button>
        </form>
        
        <p>Already have an account? <a href="user-login.php">Login</a></p>
    </div>
</div>

</body>
</html>
