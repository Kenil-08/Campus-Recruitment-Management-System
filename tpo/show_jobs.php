<?php
    include '../db.php'; 

    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php'); 
        exit();
    }
    
    $query = "SELECT job_id, job_title, company_name, city, ctc, bond, allowed_branch, status, job_document FROM job_postings";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error fetching job data: " . mysqli_error($conn);
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posted Jobs</title>
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

    <!-- Jobs Data Table -->
    <div class="container mt-5">
        <div class="bg-white p-4 rounded shadow-sm">

            <table id="jobsTable" class="table table-striped">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Job Title</th>
                        <th scope="col">Company Name</th>
                        <th scope="col">City</th>
                        <th scope="col">CTC</th>
                        <th scope="col">Bond Required</th>
                        <th scope="col">Allowed Branches</th>
                        <th scope="col">Job Document</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['city']); ?></td>
                            <td><?php echo htmlspecialchars($row['ctc']); ?></td>
                            <td><?php echo $row['bond'] ? 'Yes' : 'No'; ?></td>
                            <td><?php echo htmlspecialchars($row['allowed_branch']); ?></td>
                            <td>
                                <?php if ($row['job_document']) : ?>
                                    <a href="../uploads/job_document/<?php echo htmlspecialchars($row['job_document']); ?>" target="_blank">View Document</a>
                                <?php else : ?>
                                    No document uploaded
                                <?php endif; ?>
                            </td>
                            <td>
                                <select class="form-select job-status" data-job-id="<?php echo $row['job_id']; ?>">
                                    <option value="1" <?php echo ($row['status'] == 1) ? 'selected' : ''; ?>>Open</option>
                                    <option value="0" <?php echo ($row['status'] == 0) ? 'selected' : ''; ?>>Closed</option>
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
            $('#jobsTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "lengthMenu": [5, 10, 25, 50],
                "pageLength": 10
            });

            // Change job status
            $('.job-status').change(function() {
                var jobId = $(this).data('job-id');
                var status = $(this).val();

                $.ajax({
                    url: 'update_job_status.php',
                    type: 'POST',
                    data: {
                        job_id: jobId,
                        status: status
                    },
                    success: function(response) {
                        if (response === 'Success') {
                            alert('Job status updated successfully');
                        } else {
                            alert('Error updating job status');
                        }
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
