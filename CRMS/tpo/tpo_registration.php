<?php
include '../db.php'; // Adjust the path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];

    // Insert into users table
    $query = "INSERT INTO users (username, password, role) VALUES ('$email', '$password', 'tpo')";
    if (mysqli_query($conn, $query)) {
        $user_id = mysqli_insert_id($conn); // Get user_id for tpo table

        // Insert into tpo table
        $tpo_query = "INSERT INTO tpo (user_id, name, email, contact_number)
                      VALUES ('$user_id', '$name', '$email', '$contact_number')";
        if (mysqli_query($conn, $tpo_query)) {
            echo "TPO registration successful!";
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
    <title>TPO Registration</title>
    <link rel="stylesheet" href="style.css"> <!-- Optional: Link to a CSS file -->
</head>
<body>
    <div class="registration-container">
        <h2>TPO Registration</h2>

        <form method="POST" action="">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" required>

            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="../login.php">Login here</a></p>
    </div>
</body>
</html>
