<?php
    include '../db.php';
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php'); 
        exit();
    }

    $query = "
        SELECT 
            applications.application_id AS application_id,
            students.student_id,
            students.first_name,
            students.last_name,
            students.email,
            students.degree,
            students.branch,
            students.contact_no AS contact_number,
            students.resume,
            job_postings.job_id,
            job_postings.company_name,
            job_postings.job_title,
            students.placement_status
        FROM applications
        JOIN students ON applications.user_id = students.user_id
        JOIN job_postings ON applications.job_id = job_postings.job_id
    ";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        echo "Error fetching applications data: " . mysqli_error($conn);
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
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
   
    <div class="container mt-5">
        <div class="bg-white p-4 rounded shadow-sm">
            <!-- Button to trigger the export -->
            <form method="POST" action="export_excel.php?type=applications" id="exportForm">
                <input type="hidden" name="search_query" id="search_query" value="">
                <button type="submit" name="export_excel" class="btn btn-success mb-3">Export Filtered Data to Excel</button>
            </form>

            <table id="applicationsTable" class="table table-striped">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Student ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Degree</th>
                        <th scope="col">Branch</th>
                        <th scope="col">Contact</th>
                        <th scope="col">Resume</th>
                        <th scope="col">Company Name</th>
                        <th scope="col">Job Title</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['degree']); ?></td>
                            <td><?php echo htmlspecialchars($row['branch']); ?></td>
                            <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                            <td>
                                <?php if ($row['resume']) : ?>
                                    <a href="../uploads/resumes/<?php echo htmlspecialchars($row['resume']); ?>" target="_blank">Resume</a>
                                <?php else : ?>
                                    No resume uploaded
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                            <td>
                                <select class="placement-status" data-student-id="<?php echo $row['student_id']; ?>" data-job-id="<?php echo $row['job_id']; ?>">
                                    <option value="1" <?php if ($row['placement_status'] == 1) echo 'selected'; ?>>Placed</option>
                                    <option value="0" <?php if ($row['placement_status'] == 0) echo 'selected'; ?>>Unplaced</option>
                                </select>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var table = $('#applicationsTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "lengthMenu": [5, 10, 25, 50],
                "pageLength": 10
            });
        
            // Capture the search query before form submission
            $('#exportForm').on('submit', function() {
                var searchQuery = table.search();  // Get the search term from DataTables
                $('#search_query').val(searchQuery);  // Set it in the hidden input field
            });
        });

        $('.placement-status').change(function() {
            var studentId = $(this).data('student-id');
            var jobId = $(this).data('job-id');  
            var newStatus = $(this).val();
            
            $.ajax({
                url: 'update_placement_status.php',
                type: 'POST',
                data: { student_id: studentId, job_id: jobId, placement_status: newStatus }, 
                success: function(response) {
                    alert('Placement status updated successfully.');
                },
                error: function() {
                    alert('Failed to update status.');
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
