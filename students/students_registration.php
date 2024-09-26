<?php
    include '../db.php'; // Database connection

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $student_id = $_POST['student_id'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $contact_number = $_POST['contact_number'];
        $degree = $_POST['degree'];
        $branch = $_POST['branch'];
        $batch = $_POST['batch'];

        $name = $first_name . ' ' . $last_name;
        // Insert into users table
        $query = "INSERT INTO users (username, password, role, name) VALUES ('$email', '$password', 'student', '$name')";
        if (mysqli_query($conn, $query)) {
            $user_id = mysqli_insert_id($conn); // Get user_id for student table

            // Insert into students table
            $student_query = "INSERT INTO students (student_id, user_id, first_name, last_name, email, contact_number, degree, branch, batch)
                              VALUES ('$student_id', '$user_id', '$first_name', '$last_name', '$email', '$contact_number', '$degree', '$branch', '$batch')";
            if (mysqli_query($conn, $student_query)) {
                echo "Student registration successful!";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow-lg p-4 w-100" style="max-width: 500px;">
        <h2 class="text-center font-weight-bold mb-4">Student Registration</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="student_id">Student ID:</label>
                <input type="text" id="student_id" name="student_id" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="text" id="contact_number" name="contact_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="degree">Degree:</label>
                <select id="degree" name="degree" class="form-control" required>
                    <option value="" disabled selected>Select your degree</option>
                    <option value="B.Tech">B.Tech</option>
                    <option value="MCA">MCA</option>
                    <option value="MSC IT">MSC IT</option>
                </select>
            </div>
            <div class="form-group">
                <label for="branch">Branch:</label>
                <select id="branch" name="branch" class="form-control" required>
                    <option value="" disabled selected>Select your branch</option>
                    <option value="CE">CE</option>
                    <option value="CSE">CSE</option>
                    <option value="IT">IT</option>
                    <option value="AI/ML">AI/ML</option>
                    <option value="MCA">MCA</option>
                    <option value="MSC IT">MSC IT</option>
                </select>
            </div>
            <div class="form-group">
                <label for="batch">Batch (e.g. 2025):</label>
                <input type="number" id="batch" name="batch" class="form-control" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </div>
        </form>
        <p class="mt-3 text-center">Already have an account? <a href="../index.php" class="text-primary">Login here</a></p>
    </div>
    <!-- Bootstrap JS (Optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
