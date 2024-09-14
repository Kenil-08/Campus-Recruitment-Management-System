<?php
include '../db.php'; // Adjust the path to your database connection file

// Fetch job postings from the job_postings table
$query = "SELECT job_title, company_name, city, ctc, bond, allowed_branch, about, status FROM job_postings";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Jobs Table -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">Available Jobs</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Job Title</th>
                        <th>Company Name</th>
                        <th>City</th>
                        <th>CTC</th>
                        <th>Bond Required</th>
                        <th>Allowed Branches</th>
                        <th>About</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo $row['job_title']; ?></td>
                            <td><?php echo $row['company_name']; ?></td>
                            <td><?php echo $row['city']; ?></td>
                            <td><?php echo $row['ctc']; ?></td>
                            <td><?php echo $row['bond'] ? 'Yes' : 'No'; ?></td>
                            <td><?php echo $row['allowed_branch']; ?></td>
                            <td><?php echo $row['about']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="student_dashboard.php" class="btn btn-link">Go back to Student Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
