<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Recruitment System</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <header>
        <h1>Welcome to the Campus Recruitment System</h1>
    </header>
    
    <nav>
        <ul>
            <li><a href="students_registration.php">Student Registration</a></li>
            <li><a href="tpo/tpo_registration.php">TPO Registration</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>

    <section>
        <h2>About the System</h2>
        <p>This system allows students to apply for job opportunities, and TPOs (Training and Placement Officers) can manage the recruitment process. Please choose the relevant options from the menu to proceed.</p>
    </section>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Campus Recruitment System. All rights reserved.</p>
    </footer>
</body>
</html>
