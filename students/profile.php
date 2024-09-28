<?php
session_start();
include '../db.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch student details
$user_id = $_SESSION['user_id'];

// Get student details from `students` table
$studentQuery = "SELECT * FROM students WHERE user_id = '$user_id'";
$studentResult = mysqli_query($conn, $studentQuery);

if ($studentResult && mysqli_num_rows($studentResult) > 0) {
    $student = mysqli_fetch_assoc($studentResult);
} else {
    echo "No student details found.";
    exit();
}

// Handle form submission for updating profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_no = $_POST['contact_no']; 
    $tenth_percentage = $_POST['tenth_percentage'];
    $twelfth_percentage = $_POST['twelfth_percentage'];
    $diploma_cgpa = $_POST['diploma_cgpa'];
    $bachelors_cgpa = $_POST['bachelors_cgpa'];
    $masters_cgpa = $_POST['masters_cgpa'];
    $active_backlog = $_POST['active_backlog'];
    $dead_backlog = $_POST['dead_backlog'];
    $resume = $_FILES['resume']['name'];

    // Update `students` table with the new values
    $updateStudentQuery = "UPDATE students SET first_name='$first_name', last_name='$last_name', contact_no='$contact_no', 
                            tenth_percentage='$tenth_percentage', twelfth_percentage='$twelfth_percentage', 
                            diploma_cgpa='$diploma_cgpa', bachelors_cgpa='$bachelors_cgpa', masters_cgpa='$masters_cgpa', active_backlog='$active_backlog', dead_backlog='$dead_backlog' 
                            WHERE user_id='$user_id'";
    mysqli_query($conn, $updateStudentQuery);

    // Optional: Handle file upload for resume
    if (isset($resume) && $resume != '') {
        $resume_tmp = $_FILES['resume']['tmp_name'];
        move_uploaded_file($resume_tmp, "../uploads/resumes/" . $resume);

        // Update resume in `students`
        $updateResumeQuery = "UPDATE students SET resume='$resume' WHERE user_id='$user_id'";
        mysqli_query($conn, $updateResumeQuery);
    }

    echo "Profile updated successfully!";
    header("Location: profile.php"); // Redirect to avoid resubmission
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-size: 0.9rem;
        }
        .container {
            max-width: 1000px;
            margin-top: 20px;
        }
        .form-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-control {
            padding: 5px;
        }
        .grid-layout {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .btn-primary {
            margin-top: 10px;
        }
        /* Minimize padding */
        .mb-3 {
            margin-bottom: 10px !important;
        }
        .card {
            margin-top: 20px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            font-size: 1.3rem;
            font-weight: bold;
            background-color: #f8f9fa;
            border-bottom: none;
            margin-bottom: 20px;
            text-align: center;
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
                    <li class="nav-item"><a class="nav-link" href="student_dashboard.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="view_jobs.php">View Jobs</a></li>
                    <li class="nav-item"><a class="nav-link" href="academic_form.php">Academic Form</a></li>
                    <li class="nav-item"><a class="nav-link" href="my_application.php">My Applications</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="../index.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Single card for the entire profile form -->
        <div class="card">
            <div class="card-header">Student Profile</div>
            <form method="POST" enctype="multipart/form-data">
                <div class="grid-layout">
                    <!-- Personal Details -->
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="contact_no" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="contact_no" name="contact_no" value="<?php echo htmlspecialchars($student['contact_no']); ?>" required>
                    </div>

                    <!-- Academic Details -->
                    <div class="mb-3">
                        <label for="tenth_percentage" class="form-label">10th Percentage</label>
                        <input type="text" class="form-control" id="tenth_percentage" name="tenth_percentage" value="<?php echo htmlspecialchars($student['tenth_percentage']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="twelfth_percentage" class="form-label">12th Percentage</label>
                        <input type="text" class="form-control" id="twelfth_percentage" name="twelfth_percentage" value="<?php echo htmlspecialchars($student['twelfth_percentage']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="diploma_cgpa" class="form-label">Diploma CGPA</label>
                        <input type="text" class="form-control" id="diploma_cgpa" name="diploma_cgpa" value="<?php echo htmlspecialchars($student['diploma_cgpa']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="bachelors_cgpa" class="form-label">Bachelors CGPA</label>
                        <input type="text" class="form-control" id="bachelors_cgpa" name="bachelors_cgpa" value="<?php echo htmlspecialchars($student['bachelors_cgpa']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="masters_cgpa" class="form-label">Masters CGPA</label>
                        <input type="text" class="form-control" id="masters_cgpa" name="masters_cgpa" value="<?php echo htmlspecialchars($student['masters_cgpa']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="active_backlog" class="form-label">Active Backlog</label>
                        <input type="text" class="form-control" id="active_backlog" name="active_backlog" value="<?php echo htmlspecialchars($student['active_backlog']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="dead_backlog" class="form-label">Dead Backlog</label>
                        <input type="text" class="form-control" id="dead_backlog" name="dead_backlog" value="<?php echo htmlspecialchars($student['dead_backlog']); ?>">
                    </div>

                    <!-- Resume Upload -->
                    <div class="mb-3">
                        <label for="resume" class="form-label">Resume</label>
                        <?php if ($student['resume']) : ?>
                            <p>Current Resume: <a href="../uploads/resume/<?php echo htmlspecialchars($student['resume']); ?>" target="_blank">View Resume</a></p>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="resume" name="resume">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
