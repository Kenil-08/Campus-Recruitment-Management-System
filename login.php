<?php
session_start();
include 'db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role'] = $row['role'];

            // Redirect based on user role
            if ($row['role'] === 'student') {
                header('Location: students/student_dashboard.php');
            } elseif ($row['role'] === 'tpo') {
                header('Location: tpo_dashboard.php');
            }
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Campus Recruitment System</title>
    <link rel="stylesheet" href="style.css"> <!-- Optional: Include a stylesheet -->
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <?php
        // Display error messages
        if (isset($error)) {
            echo "<p style='color: red;'>$error</p>";
        }
        ?>

        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="students\students_registration.php">Go to Registration</a></p>
    </div>
</body>
</html>
