<?php
    include '../db.php';

    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php'); 
        exit();
    }
    $name = $_SESSION['name']; 

    // Get placed and unplaced student counts from the database
    $placedQuery = "SELECT COUNT(*) as placed_count FROM students WHERE placement_status = 1";
    $placedResult = mysqli_query($conn, $placedQuery);
    $placedData = mysqli_fetch_assoc($placedResult);
    $placedCount = $placedData['placed_count'];

    $unplacedQuery = "SELECT COUNT(*) as unplaced_count FROM students WHERE placement_status = 0";
    $unplacedResult = mysqli_query($conn, $unplacedQuery);
    $unplacedData = mysqli_fetch_assoc($unplacedResult);
    $unplacedCount = $unplacedData['unplaced_count'];

    // Get the package data (avg, high, low) from the job_postings table
    $packageQuery = "SELECT AVG(ctc) as avg_package, MAX(ctc) as high_package, MIN(ctc) as low_package FROM job_postings";
    $packageResult = mysqli_query($conn, $packageQuery);
    $packageData = mysqli_fetch_assoc($packageResult);
    $avgPackage = round($packageData['avg_package'], 2);
    $highPackage = $packageData['high_package'];
    $lowPackage = $packageData['low_package'];

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPO Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    <style>
        .chart-container {
            position: relative;
            width: 100%;
            max-width: 500px; 
            height: 400px; 
            margin: 0 auto; 
        }
        canvas {
            width: 100% !important;
            height: 100% !important;
        }
    </style>
</head>
<body class="font-roboto bg-light">
    <div class="d-flex vh-100">
        <!-- Sidebar -->
        <nav class="bg-primary text-white p-4 flex-shrink-0" style="width: 250px;">
            <h1 class="h4 mb-5">TPO Dashboard</h1>
            <ul class="nav flex-column">
                <li class="nav-item mb-3">
                    <a href="job_posting.php" class="nav-link text-white">Post New Job</a>
                </li>
                <li class="nav-item mb-3">
                    <a href="show_jobs.php" class="nav-link text-white">Show Job</a>
                </li>
                <li class="nav-item mb-3">
                    <a href="view_students.php" class="nav-link text-white">View Students Data</a>
                </li>
                <li class="nav-item mb-3">
                    <a href="view_applications.php" class="nav-link text-white">View Applications</a>
                </li>
                <li class="nav-item mb-3">
                    <a href="tpo_registration.php" class="nav-link text-white">Register New Faculty/Admin</a>
                </li>
                <li class="nav-item mb-3">
                    <a href="../index.php" class="nav-link text-white">Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="p-4" style="flex-grow: 1;">
            <h2>Welcome, <?php echo $name; ?>!</h2>
            <div class="row mt-4">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <canvas id="placementChart"></canvas> 
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-container">
                        <canvas id="packageChart"></canvas> 
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <form action="download_placed_students.php" method="post">
                    <button type="submit" class="btn btn-success">Download Placed Students Data</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const placedCount = <?php echo $placedCount; ?>;
        const unplacedCount = <?php echo $unplacedCount; ?>;

        const avgPackage = <?php echo $avgPackage; ?>;
        const highPackage = <?php echo $highPackage; ?>;
        const lowPackage = <?php echo $lowPackage; ?>;
 
        const ctxPlacement = document.getElementById('placementChart').getContext('2d');
        const placementChart = new Chart(ctxPlacement, {
            type: 'pie', 
            data: {
                labels: ['Placed', 'Unplaced'], 
                datasets: [{
                    data: [placedCount, unplacedCount], 
                    backgroundColor: ['#4CAF50', '#F44336'], 
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, 
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Students Placement Status'
                    }
                }
            }
        });

        const ctxPackage = document.getElementById('packageChart').getContext('2d');
        const packageChart = new Chart(ctxPackage, {
            type: 'bar', 
            data: {
                labels: ['Average Package', 'Highest Package', 'Lowest Package'], 
                datasets: [{
                    label: 'Package (in LPA)',
                    data: [avgPackage, highPackage, lowPackage], 
                    backgroundColor: ['#2196F3', '#FFC107', '#FF5722'], 
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, 
                scales: {
                    y: {
                        beginAtZero: true 
                    }
                },
                plugins: {
                    legend: {
                        display: false, 
                    },
                    title: {
                        display: true,
                        text: 'Package Overview (LPA)'
                    }
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>