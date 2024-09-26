<?php
    include '../db.php'; 
    
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php'); // Redirect to login page if not logged in
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $student_id = $_POST['student_id'];
        $tenth_percentage = $_POST['tenth_percentage'];
        $twelfth_percentage = $_POST['twelfth_percentage'];
        $diploma_cgpa = isset($_POST['diploma_cgpa']) ? $_POST['diploma_cgpa'] : null;
        $bachelors_cgpa = $_POST['bachelors_cgpa'];
        $masters_cgpa = isset($_POST['masters_cgpa']) ? $_POST['masters_cgpa'] : null;
    
        // Handle file upload for resume
        $resume = null;
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
            $upload_dir = '../uploads/resume/';
            $uuid = uniqid(); // Generate a unique identifier
            $extension = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);
            $filename = $uuid . '.' . $extension; // Create the unique file name
            $target_file = $upload_dir . $filename;
        
            // Move uploaded file to target directory
            if (move_uploaded_file($_FILES['resume']['tmp_name'], $target_file)) {
                $resume = $filename; // Store the unique file name in the database
            } else {
                echo "<div class='alert alert-danger'>Error uploading the resume.</div>";
            }
        }
    
        // Insert into student_academic_details table
        $query = "INSERT INTO student_academic_details 
                  (student_id, tenth_percentage, twelfth_percentage, diploma_cgpa, bachelors_cgpa, masters_cgpa, resume) 
                  VALUES 
                  ('$student_id', '$tenth_percentage', '$twelfth_percentage', 
                  " . ($diploma_cgpa !== null ? "'$diploma_cgpa'" : "NULL") . ", 
                  '$bachelors_cgpa', " . ($masters_cgpa !== null ? "'$masters_cgpa'" : "NULL") . ", 
                  '$resume')";
    
        if (mysqli_query($conn, $query)) {
            echo "<div class='alert alert-success'>Academic details submitted successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }
    
        // Close the database connection
        mysqli_close($conn);
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-body .row > div {
            padding-top: 15px;
        }
        .container {
            margin-bottom: 50px;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="student_dashboard.php">Campus Recruitment System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="student_dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_jobs.php">View Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Academic Form -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card shadow-lg">
                    <div class="card-header">
                        <h3 class="text-center">Academic Details</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="acedemic_form.php" enctype="multipart/form-data">
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-6">
                                    <!-- Student ID -->
                                    <div class="mb-3">
                                        <label for="student_id" class="form-label">Student ID:</label>
                                        <input type="text" id="student_id" name="student_id" class="form-control" required>
                                    </div>

                                    <!-- 10th Percentage -->
                                    <div class="mb-3">
                                        <label for="tenth_percentage" class="form-label">10th Percentage:</label>
                                        <input type="number" id="tenth_percentage" name="tenth_percentage" class="form-control" required step="0.01">
                                    </div>

                                    <!-- 12th Percentage -->
                                    <div class="mb-3">
                                        <label for="twelfth_percentage" class="form-label">12th Percentage:</label>
                                        <input type="number" id="twelfth_percentage" name="twelfth_percentage" class="form-control" required step="0.01">
                                    </div>

                                    <!-- Diploma CGPA -->
                                    <div class="mb-3">
                                        <label for="diploma_cgpa" class="form-label">Diploma CGPA:</label>
                                        <input type="number" id="diploma_cgpa" name="diploma_cgpa" class="form-control" step="0.01">
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <!-- Bachelors CGPA -->
                                    <div class="mb-3">
                                        <label for="bachelors_cgpa" class="form-label">Bachelors CGPA:</label>
                                        <input type="number" id="bachelors_cgpa" name="bachelors_cgpa" class="form-control" required step="0.01">
                                    </div>

                                    <!-- Masters CGPA -->
                                    <div class="mb-3">
                                        <label for="masters_cgpa" class="form-label">Masters CGPA:</label>
                                        <input type="number" id="masters_cgpa" name="masters_cgpa" class="form-control" step="0.01">
                                    </div>

                                    <!-- Resume Upload -->
                                    <div class="mb-3">
                                        <label for="resume" class="form-label">Upload Resume (PDF format):</label>
                                        <input type="file" id="resume" name="resume" class="form-control" accept=".pdf" required>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary w-100">Submit Academic Details</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
