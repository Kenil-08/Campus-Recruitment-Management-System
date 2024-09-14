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
                header('Location: tpo/tpo_dashboard.php');
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
    <title>Campus Recruitment System</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Header Section -->
    <header class="bg-primary text-white py-2">
        <div class="container d-flex align-items-center justify-content-center">
            <div class="logo flex-shrink-0">
                <a href="index.php">
                    <img src="assets/logo.png" alt="Logo" class="rounded-circle" width="64" height="64">
                </a>
            </div>
            <nav>
                <h1 class="h3 font-weight-bold text-center ml-3">Campus Recruitment Management System</h1>
            </nav>
        </div>
    </header>

    <!-- Main content: Login Form -->
    <div class="container mt-5 d-flex flex-column align-items-center justify-content-center">
        <div class="card shadow-lg p-4 w-100" style="max-width: 400px;">
            <h2 class="text-center font-weight-bold mb-4">Login</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username" class="font-weight-bold">Username:</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password" class="font-weight-bold">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-block font-weight-bold">Login</button>
                </div>
            </form>

            <p class="mt-4 text-center">Don't have an account? 
                <a href="students/students_registration.php" class="text-primary">Go to Registration</a>
            </p>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




