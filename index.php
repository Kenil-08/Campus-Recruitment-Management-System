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
                header('Location: tpo/job_posting.php');
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
    
    <!-- Tailwind CSS -->
    <link href="public\css\style.css" rel="stylesheet"> <!-- Update this to your Tailwind CSS path -->
</head>
<body class="font-sans bg-gray-100 h-screen">

    <!-- Header Section -->
    <header class="bg-blue-900 text-white py-2">
        <div class="container mx-auto flex items-center justify-center gap-6">
            <div class="logo flex-shrink-0">
                <a href="index.php">
                    <img src="assets/logo.png" alt="Logo" class="w-16 h-16 rounded-full">
                </a>
            </div>
            <nav>
                <h1 class="text-3xl font-semibold text-center">Campus Recruitment Management System</h1>
            </nav>
        </div>
    </header>

    <!-- Main content: Login Form -->
    <div class="w-full max-w-full mx-auto my-14 flex flex-col items-center justify-center p-4">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">

            <h2 class="text-3xl font-bold text-center mb-6">Login</h2>

            <?php if (isset($error)): ?>
                <p class="text-red-600 text-center mb-4"><?= $error; ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 font-bold mb-2">Username:</label>
                    <input type="text" id="username" name="username" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-bold mb-2">Password:</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 transition">Login</button>
                </div>
            </form>

            <p class="mt-6 text-center">Don't have an account? 
                <a href="students/students_registration.php" class="text-blue-600 hover:underline">Go to Registration</a>
            </p>
        </div>
    </div>
</body>
</html>
