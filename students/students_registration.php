<?php
    include '../db.php'; 

    $registration_success = ''; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $student_id = $_POST['student_id'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $contact_no = $_POST['contact_no'];
        $degree = $_POST['degree'];
        $branch = $_POST['branch'];
        $batch = $_POST['batch'];
        $tenth_percentage = $_POST['tenth_percentage'];
        $twelfth_percentage = $_POST['twelfth_percentage'];
        $diploma_cgpa = $_POST['diploma_cgpa'];
        $bachelors_cgpa = $_POST['bachelors_cgpa'];
        $masters_cgpa = $_POST['masters_cgpa'];

        $name = $first_name . ' ' . $last_name;

        
        $resume = null;
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
            $upload_dir = '../uploads/resume/';
            $uuid = uniqid(); // Generate a unique identifier
            $extension = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);
            $filename = $uuid . '.' . $extension; // Create the unique file name
            $target_file = $upload_dir . $filename;
        
            
            if (move_uploaded_file($_FILES['resume']['tmp_name'], $target_file)) {
                $resume = $filename; 
            } else {
                echo "<div class='alert alert-danger'>Error uploading the resume.</div>";
            }
        }

        if (!preg_match('/^[0-9]{10}$/', $contact_no)) {
            echo "Invalid mobile number. Please enter a valid 10-digit number.";
        } else {
            $query = "INSERT INTO users (username, password, role, name) VALUES ('$email', '$password', 'student', '$name')";
            if (mysqli_query($conn, $query)) {
                $user_id = mysqli_insert_id($conn); 

                $student_query = "INSERT INTO students (student_id, user_id, first_name, last_name, email, contact_no, degree, branch, batch, tenth_percentage, twelfth_percentage, diploma_cgpa, bachelors_cgpa, masters_cgpa, resume)
                                  VALUES ('$student_id', '$user_id', '$first_name', '$last_name', '$email', '$contact_no', '$degree', '$branch', '$batch', '$tenth_percentage', '$twelfth_percentage', '$diploma_cgpa', '$bachelors_cgpa', '$masters_cgpa', '$resume')";
                if (mysqli_query($conn, $student_query)) {
                    $registration_success = "Student registration successful!"; 
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sticky-sidebar {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
        }
        .required::after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Student Registration Form -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header">
                        <h5 class="text-center">Student Registration</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="accordion" id="registrationFormAccordion">
                                <!-- Accordion Item 1: Personal Details -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Personal Details
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#registrationFormAccordion">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="student_id" class="form-label required">Student ID:</label>
                                                    <input type="text" id="student_id" name="student_id" required class="form-control" >
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="first_name" class="form-label required">First Name:</label>
                                                    <input type="text" id="first_name" name="first_name" required class="form-control" pattern="[A-Za-z]+" title="First name should only contain letters.">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="last_name" class="form-label required">Last Name:</label>
                                                    <input type="text" id="last_name" name="last_name" required class="form-control" pattern="[A-Za-z]+" title="Last name should only contain letters.">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="contact_no" class="form-label required">Contact Number:</label>
                                                    <input type="tel" id="contact_no" name="contact_no" required class="form-control" pattern="[0-9]{10}" maxlength="10" title="Please enter a 10-digit mobile number.">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="email" class="form-label required">Email:</label>
                                                    <input type="email" id="email" name="email" required class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="password" class="form-label required">Password:</label>
                                                    <input type="password" id="password" name="password" required class="form-control" pattern="^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{5,8}$" 
                                                    title="Password must be between 5-8 characters, contain at least one letter, one digit, and one special character.">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Accordion Item 2: Academic Details -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Academic Details
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#registrationFormAccordion">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="degree" class="form-label required">Degree:</label>
                                                    <select id="degree" name="degree" required class="form-select">
                                                        <option value="">Select Degree</option>
                                                        <option value="B.Tech">B.Tech</option>
                                                        <option value="MCA">MCA</option>
                                                        <option value="MSCIT">MSCIT</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="branch" class="form-label required">Branch:</label>
                                                    <select id="branch" name="branch" required class="form-select">
                                                        <option value="">Select Branch</option>
                                                        <option value="CSE">CSE</option>
                                                        <option value="IT">IT</option>
                                                        <option value="CE">CE</option>
                                                        <option value="AIML">AI/ML</option>
                                                        <option value="MCA">MCA</option>
                                                        <option value="MSCIT">MSCIT</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="tenth_percentage" class="form-label required">10th Percentage:</label>
                                                    <input type="number" id="tenth_percentage" name="tenth_percentage" required class="form-control" step="0.01" min="0" max="100">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="twelfth_percentage" class="form-label">12th Percentage:</label>
                                                    <input type="number" id="twelfth_percentage" name="twelfth_percentage" class="form-control" step="0.01" min="0" max="100">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="diploma_cgpa" class="form-label">Diploma GPA:</label>
                                                    <input type="number" id="diploma_cgpa" name="diploma_cgpa" class="form-control" step="0.01" min="0" max="10">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="bachelors_cgpa" class="form-label required">Bachelor's CGPA:</label>
                                                    <input type="number" id="bachelors_cgpa" name="bachelors_cgpa" required class="form-control" step="0.01" min="0" max="10">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="masters_cgpa" class="form-label">Master's CGPA:</label>
                                                    <input type="number" id="masters_cgpa" name="masters_cgpa" class="form-control" step="0.01" min="0" max="10">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="batch" class="form-label required">Batch (Exa: 2025):</label>
                                                    <input type="text" id="batch" name="batch" required class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Accordion Item 3: Documents -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Documents
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#registrationFormAccordion">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="resume" class="form-label required">Upload Resume:</label>
                                                    <input type="file" id="resume" name="resume" required class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary w-100">Register</button>
                            </div>
                        </form>
                        <p class="mt-3 text-center">Already have an account? <a href="../index.php" class="text-primary">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
