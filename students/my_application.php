<?php
    include '../db.php'; // Adjust the path to your database connection file
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php'); // Redirect to login page if not logged in
        exit();
    }

    // Fetch applications along with student and job details
    $query = "
        SELECT 
            applications.id AS application_id,
            students.student_id,
            job_postings.company_name,
            job_postings.job_title,
            job_postings.ctc,
            job_postings.status
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
    <title>My Applications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="student_dashboard.php">Campus Recruitment System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="student_dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_jobs.php">View Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="acedemic_form.php">Acedemic Form</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="my_application.php">My Applications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
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
            <table id="myApplicationsTable" class="table table-striped">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Company Name</th>
                        <th scope="col">Job Title</th>
                        <th scope="col">CTC</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['ctc']); ?></td>
                            <td><?php echo $row['status'] == 1 ? 'Open' : 'Closed'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var table = $('#myApplicationsTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "lengthMenu": [5, 10, 25, 50],
                "pageLength": 10
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
