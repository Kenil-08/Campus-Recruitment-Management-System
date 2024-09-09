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

    // Insert into users table
    $query = "INSERT INTO users (username, password, role) VALUES ('$email', '$password', 'student')";
    if (mysqli_query($conn, $query)) {
        $user_id = mysqli_insert_id($conn); // Get user_id for student table

        // Insert into students table
        $student_query = "INSERT INTO students (student_id, user_id, first_name, last_name, email, contact_number, degree, branch, batch)
                          VALUES ('$student_id' ,'$user_id', '$first_name', '$last_name', '$email', '$contact_number', '$degree', '$branch', '$batch')";
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
    <link rel="stylesheet" href="style.css"> <!-- Optional CSS -->
</head>
<body>
    <div class="registration-container">
        <h2>Student Registration</h2>

        <form method="POST" action="">
            <label for="student_id">Student ID:</label>
            <input type="text" id="student_id" name="student_id" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" required>

            <label for="degree">Degree:</label>
            <select id="degree" name="degree" required>
              <option value="" disabled selected>Select your degree</option>
              <option value="B.Tech">B.Tech</option>
              <option value="MCA">MCA</option>
              <option value="MSC IT">MSC IT</option>
            </select>

            <label for="branch">Branch:</label>
            <select id="branch" name="branch" required>
              <option value="" disabled selected>Select your branch</option>
              <option value="CE">CE</option>
              <option value="CSE">CSE</option>
              <option value="IT">IT</option>
              <option value="AI/ML">AI/ML</option>
              <option value="MCA">MCA</option>
              <option value="MSC IT">MSC IT</option>
            </select>

            <label for="batch">Batch (exa. 2025)</label>
            <input type="number" id="batch" name="batch" required>


            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="../login.php">Login here</a></p>
    </div>
</body>
</html>
