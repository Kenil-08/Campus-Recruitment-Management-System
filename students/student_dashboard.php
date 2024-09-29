<?php
    include '../db.php'; // Database connection

    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php'); // Redirect to login page if not logged in
        exit();
    }
    $name = $_SESSION['name']; // Retrieve the name from the session

    // Fetch student's degree from the students table
    $user_id = $_SESSION['user_id'];
    $student_query = "SELECT degree FROM students WHERE user_id = '$user_id'";
    $student_result = mysqli_query($conn, $student_query);

    if ($student_result && mysqli_num_rows($student_result) > 0) {
        $student_row = mysqli_fetch_assoc($student_result);
        $student_degree = $student_row['degree']; // e.g., B.Tech, MCA, etc.
    } else {
        echo "Error fetching student degree: " . mysqli_error($conn);
        exit;
    }

    // Fetch job postings from the job_postings table including job_id, filtering by allowed_branch
    $query = "SELECT job_id, job_title, company_name, city, ctc, bond, allowed_branch, status, job_document
                FROM job_postings WHERE FIND_IN_SET('$student_degree', allowed_branch) > 0 AND status = 1";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error fetching job data: " . mysqli_error($conn);
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .sidebar {
            background-color: #2c3e50;
            min-height: 100vh;
        }
        .sidebar a {
            color: #ecf0f1;
        }
        .sidebar a:hover {
            color: #bdc3c7;
        }
        .card {
            border-radius: 8px;
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body class="bg-light">

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
                <div class="sidebar-sticky pt-3">
                <h1 class="card-title h3 mb-4 text-white"><?php echo htmlspecialchars($name); ?></h1>
                    <ul class="nav flex-column">
                        <!-- <li class="nav-item mb-3">
                            <a class="nav-link" href="view_jobs.php">Available Jobs</a>
                        </li> -->
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="my_application.php">My Applications</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="profile.php">My Profile</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="../index.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="row mt-4">
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['job_title']); ?></h5>
                                    <p class="card-text">
                                        <strong>Company:</strong> <?php echo htmlspecialchars($row['company_name']); ?><br>
                                        <strong>City:</strong> <?php echo htmlspecialchars($row['city']); ?><br>
                                        <strong>CTC:</strong> <?php echo htmlspecialchars($row['ctc']); ?><br>
                                        <strong>Bond Required:</strong> <?php echo $row['bond'] ? 'Yes' : 'No'; ?><br>
                                        <strong>Allowed Branches:</strong> <?php echo htmlspecialchars($row['allowed_branch']); ?>
                                    </p>
                                    <?php if ($row['job_document']) : ?>
                                        <a href="../uploads/job_document/<?php echo htmlspecialchars($row['job_document']); ?>" class="btn btn-primary btn-sm" target="_blank">View Document</a>
                                    <?php else : ?>
                                        <span>No document uploaded</span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer">
                                    <?php
                                        $job_id = $row['job_id'];
                                        $user_id = $_SESSION['user_id']; 

                                        $check_query = "SELECT * FROM applications WHERE job_id = '$job_id' AND user_id = '$user_id'";
                                        $check_result = mysqli_query($conn, $check_query);

                                        if (mysqli_num_rows($check_result) > 0) {
                                            echo '<span class="text-success">Applied</span>';
                                        } else {
                                            echo '<a href="#" class="btn btn-success btn-sm" onclick="confirmApply(' . $job_id . ', \'' . urlencode($row['company_name']) . '\')">Apply</a>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <!-- <footer class="footer bg-dark text-white text-center py-2">
        <p>&copy; <?php echo date("Y"); ?> Campus Recruitment System. All rights reserved.</p>
    </footer> -->
    
    <script> 
        function confirmApply(jobId, companyName) {
            if(confirm("Are you sure you want to apply for this job?")) {
                window.location.href = `apply.php?job_id=${jobId}&company_name=${companyName}`;
            }
        }
    </script>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
