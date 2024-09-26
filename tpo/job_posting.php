<?php
    include '../db.php'; // Adjust the path if needed
    session_start();
    //print_r($_SESSION);
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php'); // Redirect to login page if not logged in
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $job_title = $_POST['job_title'];
        $company_name = $_POST['company_name'];
        $city = $_POST['city'];
        $ctc = $_POST['ctc'];
        $bond = isset($_POST['bond']) ? 1 : 0;
        $allowed_branches = isset($_POST['allowed_branch']) ? implode(', ', $_POST['allowed_branch']) : '';

        // Handle file upload for job description document with UUID
        $jd_document = null;
        if (isset($_FILES['jd_document']) && $_FILES['jd_document']['error'] == 0) {
            $upload_dir = '../uploads/job_document/';
            $uuid = uniqid(); // Generate a unique identifier
            $extension = pathinfo($_FILES['jd_document']['name'], PATHINFO_EXTENSION);
            $filename = $uuid . '.' . $extension; // Create the unique file name
            $target_file = $upload_dir . $filename;

            // Move uploaded file to target directory
            if (move_uploaded_file($_FILES['jd_document']['tmp_name'], $target_file)) {
                $jd_document = $filename; // Store the unique file name in the database
            } else {
                echo "<div class='alert alert-danger'>Error uploading the document.</div>";
            }
        }

        // Insert into job_postings table
        $query = "INSERT INTO job_postings (job_title, company_name, city, CTC, bond, allowed_branch, job_document) 
                  VALUES ('$job_title', '$company_name', '$city', '$ctc', '$bond', '$allowed_branches', '$jd_document')";

        if (mysqli_query($conn, $query)) {
            echo "<div class='alert alert-success'>Job posting successful!</div>";
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
    <title>Create Job Posting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sticky-sidebar {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
        }
    </style>
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
                        <a class="nav-link" href="../index.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Job Posting Form -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header">
                        <h2 class="text-center">Create Job Posting</h2>
                    </div>
                    <div class="card-body">
                        <!-- Use a single form wrapping all input fields -->
                        <form method="POST" action="job_posting.php" enctype="multipart/form-data">
                            <div class="accordion" id="jobFormAccordion">
                                <!-- Accordion Item 1 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Job Details
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#jobFormAccordion">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="job_title" class="form-label">Job Title:</label>
                                                    <input type="text" id="job_title" name="job_title" required class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="company_name" class="form-label">Company Name:</label>
                                                    <input type="text" id="company_name" name="company_name" required class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="city" class="form-label">City:</label>
                                                    <input type="text" id="city" name="city" required class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="ctc" class="form-label">CTC (Cost to Company):</label>
                                                    <input type="number" step="0.01" id="ctc" name="ctc" required class="form-control">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <div class="form-check">
                                                        <input type="checkbox" id="bond" name="bond" class="form-check-input">
                                                        <label for="bond" class="form-check-label">Bond Required</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Accordion Item 2 -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Additional Details
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#jobFormAccordion">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="allowed_branch" class="form-label">Allowed Branches:</label>
                                                    <div class="form-check">
                                                        <input type="checkbox" id="btech" name="allowed_branch[]" value="B.Tech" class="form-check-input">
                                                        <label for="btech" class="form-check-label">B.Tech</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" id="mca" name="allowed_branch[]" value="MCA" class="form-check-input">
                                                        <label for="mca" class="form-check-label">MCA</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" id="mscit" name="allowed_branch[]" value="MSCIT" class="form-check-input">
                                                        <label for="mscit" class="form-check-label">MSCIT</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="jd_document" class="form-label">Job Description Document (optional):</label>
                                                    <input type="file" id="jd_document" name="jd_document" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary w-100">Post Job</button>
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
