<?php
include '../db.php'; // Adjust the path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_title = $_POST['job_title'];
    $company_name = $_POST['company_name'];
    $city = $_POST['city'];
    $ctc = $_POST['ctc'];
    $bond = isset($_POST['bond']) ? 1 : 0; // Checkbox for bond, convert to boolean

    // Handle allowed branches (concatenate selected branches)
    $allowed_branches = isset($_POST['allowed_branch']) ? implode(', ', $_POST['allowed_branch']) : '';

    $about = $_POST['about'];

    // Insert into job_postings table
    $query = "INSERT INTO job_postings (job_title, company_name, city, CTC, bond, allowed_branch, about) 
              VALUES ('$job_title', '$company_name', '$city', '$ctc', '$bond', '$allowed_branches', '$about')";

    if (mysqli_query($conn, $query)) {
        echo "Job posting successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Posting</title>
    <link rel="stylesheet" href="style.css"> <!-- Optional: Link to a CSS file -->
</head>
<body>
    <div class="job-posting-container">
        <h2>Create Job Posting</h2>

        <form method="POST" action="job_posting.php">
            <label for="job_title">Job Title:</label>
            <input type="text" id="job_title" name="job_title" required>

            <label for="company_name">Company Name:</label>
            <input type="text" id="company_name" name="company_name" required>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>

            <label for="ctc">CTC (Cost to Company):</label>
            <input type="number" step="0.01" id="ctc" name="ctc" required>

            <label for="bond">Bond Required:</label>
            <input type="checkbox" id="bond" name="bond">

            <label for="allowed_branch">Allowed Branches:</label>
            <div>
                <input type="checkbox" id="btech" name="allowed_branch[]" value="B.Tech">
                <label for="btech">B.Tech</label><br>

                <input type="checkbox" id="mca" name="allowed_branch[]" value="MCA">
                <label for="mca">MCA</label><br>

                <input type="checkbox" id="mscit" name="allowed_branch[]" value="MSCIT">
                <label for="mscit">MSCIT</label><br>
            </div>

            <label for="about">About the Job:</label>
            <textarea id="about" name="about"></textarea>

            <button type="submit">Post Job</button>
        </form>

        <p><a href="../tpo_dashboard.php">Go back to TPO Dashboard</a></p>
    </div>
</body>
</html>
