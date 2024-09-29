<?php
    include '../db.php';
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php');
        exit();
    }

    // Get the export type (e.g., applications or students)
    $type = isset($_GET['type']) ? $_GET['type'] : 'applications';

    // Function to export data to Excel
    function exportToExcel($filename, $data, $columns) {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=' . $filename . '.xls');

        echo implode("\t", $columns) . "\n";
        foreach ($data as $row) {
            echo implode("\t", array_values($row)) . "\n";
        }
    }
    // Check if search query is set
    $search_query = isset($_POST['search_query']) ? mysqli_real_escape_string($conn, $_POST['search_query']) : '';

    // Export Applications Data
    if ($type == 'applications') {
        $query = "
            SELECT
                students.student_id,
                students.first_name, 
                students.last_name,
                students.email,
                students.contact_no,
                job_postings.company_name, 
                job_postings.job_title,  
                applications.application_date
            FROM applications
            JOIN students ON applications.user_id = students.user_id
            JOIN job_postings ON applications.job_id = job_postings.job_id  
            WHERE job_postings.company_name LIKE '%$search_query%' 
        ";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Error fetching applications data: " . mysqli_error($conn));
        }

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        $columns = ['Student ID', 'First Name', 'Last Name', 'Email', 'Contact No', 'Company Name', 'Job Title', 'Application Date'];
        exportToExcel('applications_data', $data, $columns);
    }

    // Export Students Data
    elseif ($type == 'students') {
        $query = "
            SELECT 
                student_id, 
                first_name, 
                last_name, 
                email, 
                contact_no, 
                degree, 
                branch, 
                batch, 
                tenth_percentage, 
                twelfth_percentage, 
                diploma_cgpa, 
                bachelors_cgpa, 
                masters_cgpa,
                active_backlog,
                dead_backlog,
                placement_status 
            FROM students
        ";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Error fetching students data: " . mysqli_error($conn));
        }

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $row['placement_status'] = $row['placement_status'] == 1 ? 'Placed' : 'Unplaced';
            $data[] = $row;
        }

        $columns = [
            'Student ID', 'First Name', 'Last Name', 'Email', 'Contact No', 
            'Degree', 'Branch', 'Batch', '10th Percentage', '12th Percentage', 
            'Diploma CGPA', 'Bachelors CGPA', 'Masters CGPA', 'Active Backlog', 'Dead Backlog', 'Placement Status'
        ];
        exportToExcel('students_data', $data, $columns);
    }
    else {
        die("Invalid export type specified.");
    }
?>
