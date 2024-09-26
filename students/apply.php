<?php
    include '../db.php';
    // Start session to track user data if necessary
    session_start();
    
    if (isset($_GET['job_id']) && isset($_GET['company_name'])) {
        $job_id = $_GET['job_id'];
        $company_name = urldecode($_GET['company_name']); // Decode the URL-encoded company name
        
        // Display a confirmation message or use these values to insert into the database
        echo "<h1>You are applying for a job at " . htmlspecialchars($company_name) . " (Job ID: " . htmlspecialchars($job_id) . ")</h1>";
        
        // Assuming you have user_id in session after login
        $user_id = $_SESSION['user_id'];
    
        // Insert application into the applications table
        $query = "INSERT INTO applications (user_id, job_id) VALUES ('$user_id', '$job_id')";
    
        if (mysqli_query($conn, $query)) {
            echo "<h1>Application successful for " . htmlspecialchars($company_name) . " (Job ID: " . htmlspecialchars($job_id) . ")</h1>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
?>
