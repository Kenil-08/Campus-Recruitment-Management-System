<?php
    include '../db.php';

    $placedQuery = "
        SELECT 
            s.student_id, 
            s.first_name, 
            s.last_name, 
            s.email, 
            s.contact_no, 
            s.degree, 
            s.branch, 
            jp.company_name, 
            jp.job_title, 
            jp.ctc,
            p.placement_date 
        FROM 
            students s 
        INNER JOIN 
            applications a 
            ON s.user_id = a.user_id -- Use user_id for joining students and applications
        INNER JOIN 
            job_postings jp 
            ON a.job_id = jp.job_id
        INNER JOIN 
            placements p 
            ON a.job_id = jp.job_id
        WHERE 
            s.placement_status = 1"; // Only fetch placed students

    $placedResult = mysqli_query($conn, $placedQuery);

    if (!$placedResult) {
        die('Query Error: ' . mysqli_error($conn));
    }

    if (mysqli_num_rows($placedResult) > 0) {
        // Set headers to download file
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=placed_students.csv');
        // Open output stream in memory
        $output = fopen('php://output', 'w');

        // Add CSV column headers
        fputcsv($output, array(
            'Student ID', 
            'First Name', 
            'Last Name', 
            'Email', 
            'Contact No', 
            'Degree', 
            'Branch', 
            'Company Name', 
            'Job Title', 
            'CTC (in LPA)',
            'Date'
        ));

        while ($row = mysqli_fetch_assoc($placedResult)) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    } else {
        echo 'No placed students data available for download.';
    }
    mysqli_close($conn);
?>
