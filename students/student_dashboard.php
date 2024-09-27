<?php
    include '../db.php'; // Database connection

    session_start();
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
    <title>Student Dashboard</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .sidebar {
            background-color: #2c3e50;
            min-height: 100vh;
        }
        .sidebar a {
            color: #ecf0f1;
        }
        .sidebar a:hover {
            color: #bdc3c7;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body class="bg-light">

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
                <div class="sidebar-sticky pt-3">
                    <h1 class="h4 text-white text-center font-weight-bold mb-4">Student Dashboard</h1>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="view_jobs.php">Available Jobs</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="my_application.php">My Applications</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="acedemic_form.php">Acedemic Form</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="profile.php">My Profile</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="../index.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
                <div class="card shadow-sm p-4">
                    <h1 class="h3 font-weight-bold">Hello <?php echo htmlspecialchars($name); ?> !</h1>
                    <p class="lead">Browse through the available job postings and apply for the ones that interest you.</p>
                </div>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer bg-dark text-white text-center py-2">
        <p>&copy; <?php echo date("Y"); ?> Campus Recruitment System. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
