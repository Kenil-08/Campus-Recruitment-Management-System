<?php
require '../Classes/PHPExcel.php'; // Include PHPExcel
include '../db.php'; // Your database connection

// Check if export is triggered
if (isset($_POST['export_excel'])) {
    // Get the search query sent from the form
    $searchQuery = isset($_POST['search_query']) ? $_POST['search_query'] : '';

    // Adjust the query to filter by the search query (in this case, company name)
    $query = "
        SELECT 
            students.student_id,
            students.first_name,
            students.last_name,
            students.email,
            students.degree,
            students.branch,
            students.contact_number,
            student_academic_details.resume,
            job_postings.company_name,
            job_postings.job_title
        FROM applications
        JOIN students ON applications.user_id = students.user_id
        JOIN job_postings ON applications.job_id = job_postings.job_id
        JOIN student_academic_details ON students.student_id = student_academic_details.student_id
        WHERE job_postings.company_name LIKE '%" . mysqli_real_escape_string($conn, $searchQuery) . "%'
    ";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error fetching data: " . mysqli_error($conn);
        exit;
    }

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $sheet = $objPHPExcel->getActiveSheet();

    // Add header row
    $sheet->setCellValue('A1', 'Student ID');
    $sheet->setCellValue('B1', 'Name');
    $sheet->setCellValue('C1', 'Email');
    $sheet->setCellValue('D1', 'Degree');
    $sheet->setCellValue('E1', 'Branch');
    $sheet->setCellValue('F1', 'Contact');
    $sheet->setCellValue('G1', 'Company Name');
    $sheet->setCellValue('H1', 'Job Title');

    // Add data rows
    $rowNum = 2; // Start from row 2
    while ($row = mysqli_fetch_assoc($result)) {
        $sheet->setCellValue('A' . $rowNum, $row['student_id']);
        $sheet->setCellValue('B' . $rowNum, $row['first_name'] . ' ' . $row['last_name']);
        $sheet->setCellValue('C' . $rowNum, $row['email']);
        $sheet->setCellValue('D' . $rowNum, $row['degree']);
        $sheet->setCellValue('E' . $rowNum, $row['branch']);
        $sheet->setCellValue('F' . $rowNum, $row['contact_number']);
        $sheet->setCellValue('G' . $rowNum, $row['company_name']);
        $sheet->setCellValue('H' . $rowNum, $row['job_title']);
        $rowNum++;
    }

    // Export the file
    $fileName = 'applications_' . date('Y-m-d_H-i-s') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $writer->save('php://output');
    exit();
}
?>
