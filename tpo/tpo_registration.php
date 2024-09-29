<?php
    include '../db.php';

    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php'); 
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
        $name = $_POST['name'];
        $email = $_POST['email'];
        $contact_number = $_POST['contact_number'];

        $query = "INSERT INTO users (username, password, role, name) VALUES ('$email', '$password', 'tpo', '$name')";
        if (mysqli_query($conn, $query)) {
            $user_id = mysqli_insert_id($conn); 
            // Insert into tpo table
            $tpo_query = "INSERT INTO tpo (user_id, name, email, contact_number)
                          VALUES ('$user_id', '$name', '$email', '$contact_number')";
            if (mysqli_query($conn, $tpo_query)) {
                echo "<div class='alert alert-success'>TPO registration successful!</div>";
            } else {
                echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPO Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="tpo_dashboard.php">Campus Recruitment System</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="tpo_dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="job_posting.php">Post a Job</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="show_jobs.php">Show Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_students.php">Students Data</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_applications.php">Applications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">TPO Registration</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact_number" class="form-label">Contact Number:</label>
                                <input type="text" id="contact_number" name="contact_number" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
