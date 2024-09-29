<?php
    include '../db.php';
    if (isset($_POST['job_id']) && isset($_POST['status'])) {
        $job_id = $_POST['job_id'];
        $status = $_POST['status'];
        
        // Update the status in the job_postings table
        $query = "UPDATE job_postings SET status = ? WHERE job_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ii', $status, $job_id);

        if (mysqli_stmt_execute($stmt)) {
            echo 'Success';
        } else {
            echo 'Error: ' . mysqli_error($conn);
        }
    }
?>
