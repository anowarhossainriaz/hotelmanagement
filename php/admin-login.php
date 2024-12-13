<!-- admin-login.php -->
<?php
session_start();
include('db_connection.php'); // Include your database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate admin credentials
    $query = "SELECT * FROM Admin WHERE Username = ? AND Password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $username, $password); // Bind the parameters
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Admin login successful
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header('Location: admin-dashboard.php'); // Redirect to admin dashboard
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
    <title>Admin Login - Hotel Management System</title>
    <link rel="stylesheet" href="../styles/adminlog.css">
</head>
<body>

<div class="login-container" style="background-image: url('../image/home-bg.jpg');">
    <div class="login-box">
        <h2>Admin Login</h2>
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
    </div>
</div>

</body>
</html>
