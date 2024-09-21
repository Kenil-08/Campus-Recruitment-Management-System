<?php
include '../db.php'; // Adjust the path to your database connection file

// Fetch job postings from the job_postings table
$query = "SELECT job_title, company_name, city, ctc, bond, allowed_branch, status, job_document FROM job_postings";
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
    <title>View Jobs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="student_dashboard.php">Campus Recruitment System</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="student_dashboard.php">Home</a>
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
                            <td><?php echo $row['status'] == 1 ? 'Open' : 'Closed'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#jobsTable').DataTable({
                "paging": true,    // Enable pagination
                "searching": true, // Enable search functionality
                "ordering": true,  // Enable column sorting
                "info": true,      // Enable information text
                "lengthMenu": [5, 10, 25, 50], // Control page length options
                "pageLength": 10   // Set initial page length
            }); 
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
