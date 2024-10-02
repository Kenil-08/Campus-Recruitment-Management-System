<?php
    include '../db.php';
    session_start();
    
    if (isset($_GET['job_id']) && isset($_GET['company_name'])) {
        $job_id = $_GET['job_id'];
        $company_name = urldecode($_GET['company_name']); 
      
        //have user_id in session after login
        $user_id = $_SESSION['user_id'];
    
        // Insert application into the applications table
        $query = "INSERT INTO applications (user_id, job_id, application_date) VALUES ('$user_id', '$job_id', NOW())";
    
        if (mysqli_query($conn, $query)) {
            echo "<h1>Application successful for " . htmlspecialchars($company_name) . " (Job ID: " . htmlspecialchars($job_id) . ")</h1>";
            header("Location: /Campus-Recruitment-Management-System/students/student_dashboard.php");
            exit(); 
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
?>
