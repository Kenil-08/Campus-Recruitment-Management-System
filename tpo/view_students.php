<?php
    include '../db.php';
    session_start();
   
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php');
        exit();
    }
    
    $query = "SELECT student_id, first_name, last_name, email, contact_no, degree, branch, placement_status FROM students";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error fetching student data: " . mysqli_error($conn);
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
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

    <!-- Students Data Table -->
    <div class="container mt-5">
        <div class="bg-white p-4 rounded shadow-sm">
            <form method="POST" action="export_excel.php?type=students" id="exportForm">
                <input type="hidden" name="search_query" id="search_query" value="">
                <button type="submit" name="export_excel" class="btn btn-success mb-3">Export Data to Excel</button>
            </form>

            <table id="studentTable" class="table table-striped">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Student ID</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Contact No</th>
                        <th scope="col">Degree</th>
                        <th scope="col">Branch</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['contact_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['degree']); ?></td>
                            <td><?php echo htmlspecialchars($row['branch']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#studentTable').DataTable({
                "paging": true,   
                "searching": true, 
                "ordering": true,  
                "info": true,      
                "lengthMenu": [5, 10, 25, 50], 
                "pageLength": 10  
            }); 
        });
        
        $(document).ready(function() {
            $('.placement-status').change(function() {
                var studentId = $(this).data('student-id');
                var newStatus = $(this).val();
            
                $.ajax({
                    url: 'update_placement_status.php',
                    type: 'POST',
                    data: { student_id: studentId, placement_status: newStatus },
                    success: function(response) {
                        alert('Placement status updated successfully.');
                    },
                    error: function() {
                        alert('Failed to update status.');
                    }
                });
            });
        });
    </script>                        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
