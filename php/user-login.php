<!-- user-login.php -->
<?php
session_start();
include('db_connection.php'); // Include database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate user credentials
    $query = "SELECT * FROM User WHERE Username = ? AND Password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $username, $password); // Bind the parameters
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User login successful
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_username'] = $username;
        header('Location: user-dashboard.php'); // Redirect to user dashboard
        exit();
    } else {
        $error_message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - Hotel Management System</title>
    <link rel="stylesheet" href="../styles/userlog.css">
</head>
<body>

<!-- Login Page Container -->
<div class="login-container">
    <div class="login-box">
        <h2>User Login</h2>
        <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <p>Don't have an account? <a href="user-signup.php">Sign Up</a></p>
    </div>
</div>

</body>
</html>
