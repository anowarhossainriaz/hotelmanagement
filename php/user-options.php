<?php
session_start();
include('db_connection.php'); // Include the database connection

// Check if the user is already logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: user-dashboard.php');
    exit();
}

// Handle Sign Up Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert into database
    $query = "INSERT INTO User (Username, Email, Password, Gender) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssss', $name, $email, $password, $gender);

    if ($stmt->execute()) {
        $success_message = "Sign up successful! Please log in.";
    } else {
        $error_message = "Sign up failed. Username or email may already exist.";
    }
}

// Handle Login Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];

    $query = "SELECT * FROM User WHERE Username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_username'] = $user['Username'];
            header('Location: user-dashboard.php');
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No account found with that username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Options</title>
    <link rel="stylesheet" href="../styles/useroption.css">
</head>
<body>
<div id="login">
    <h2>User Options</h2>
    <?php if (isset($success_message)) echo "<p style='color: green;'>$success_message</p>"; ?>
    <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>

    <!-- Sign Up Form -->
    <form method="POST" action="">
        <h3>Sign Up</h3>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" name="signup">Sign Up</button>
    </form>

    <!-- Login Form -->
    <form method="POST" action="">
        <h3>Login</h3>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" name="login">Login</button>
    </form>
</div>
</body>
</html>
