<?php
    include '../db.php'; // Database connection
    
    session_start();
    print_r($_SESSION);
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php'); // Redirect to login page if not logged in
        exit();
    }
    $name = $_SESSION['name']; // Retrieve the name from the session  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPO Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <div class="flex-fill p-5">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h1 class="card-title h3 mb-4">Welcome <?php echo htmlspecialchars($name); ?>!</h1>
                    <p class="card-text">Select an option from the navigation menu to get started.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 position-absolute w-100" style="bottom: 0;">
        <p>&copy; <?php echo date("Y"); ?> Campus Recruitment System. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
