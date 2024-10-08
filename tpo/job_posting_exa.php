<?php
    include '../db.php'; 
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php'); 
        exit();
    }

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // Include PHPMailer files
    require '../PHPMailer/src/Exception.php';
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $job_title = $_POST['job_title'];
        $company_name = $_POST['company_name'];
        $city = $_POST['city'];
        $ctc = $_POST['ctc'];
        $bond = isset($_POST['bond']) ? 1 : 0;
        $allowed_branches = isset($_POST['allowed_branch']) ? implode(',', $_POST['allowed_branch']) : '';

        $jd_document = null;
        if (isset($_FILES['jd_document']) && $_FILES['jd_document']['error'] == 0) {
            $upload_dir = '../uploads/job_document/';
            $uuid = uniqid(); // Generate a unique identifier
            $extension = pathinfo($_FILES['jd_document']['name'], PATHINFO_EXTENSION);
            $filename = $uuid . '.' . $extension; 
            $target_file = $upload_dir . $filename;

            if (move_uploaded_file($_FILES['jd_document']['tmp_name'], $target_file)) {
                $jd_document = $filename; 
            } else {
                echo "<div class='alert alert-danger'>Error uploading the document.</div>";
            }
        }

        $query = "INSERT INTO job_postings (job_title, company_name, city, CTC, bond, allowed_branch, job_document) 
                  VALUES ('$job_title', '$company_name', '$city', '$ctc', '$bond', '$allowed_branches', '$jd_document')";

        if (mysqli_query($conn, $query)) {
            echo "<div class='alert alert-success'>Job posting successful!</div>";
            sendJobNotification($job_title, $company_name, $conn);
        } else {
            echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }
    }   
    function sendJobNotification($job_title, $company_name, $conn) {
        $mail = new PHPMailer(true);
    
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com'; // Your Gmail address
            $mail->Password = 'your_password_or_app_password'; // App password or Gmail password
            $mail->SMTPSecure = 'tls'; // Encryption method
            $mail->Port = 587; // TLS port

    
            // Set the sender email
            $mail->setFrom('your_email@gmail.com', 'TPO Admin');
    
            $student_query = "SELECT email FROM students";
            $student_result = mysqli_query($conn, $student_query);
    
            while ($row = mysqli_fetch_assoc($student_result)) {
                $mail->addAddress($row['email']);
            }
    
            $mail->isHTML(true);
            $mail->Subject = 'New Job Posting: ' . $job_title;
            $mail->Body    = '<p>Dear Students,</p>
                              <p>We are excited to announce a new job opportunity at <strong>' . $company_name . '</strong> for the position of <strong>' . $job_title . '</strong>.</p>
                              <p>Please log in to the Campus Recruitment System to view more details and apply for the job before the deadline.</p>
                              <p>Best regards,<br>TPO Office</p>';
            $mail->send();
            // echo 'Notification emails have been sent successfully.';
        } catch (Exception $e) {
            echo "Error: Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
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

    <!-- Job Posting Form -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header">
                        <h2 class="text-center">Create Job Posting</h2>
                    </div>
                    <div class="card-body">
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
