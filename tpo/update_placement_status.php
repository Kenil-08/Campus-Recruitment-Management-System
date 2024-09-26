<?php
    include '../db.php';

    // Check if student_id and placement_status are set in the POST request
    if (isset($_POST['student_id']) && isset($_POST['placement_status'])) {
        // Sanitize inputs to avoid SQL injection
        $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
        $placement_status = mysqli_real_escape_string($conn, $_POST['placement_status']);

        // Update query to change the placement status
        $query = "UPDATE students SET placement_status = '$placement_status' WHERE student_id = '$student_id'";

        // Execute the query
        if (mysqli_query($conn, $query)) {
            // Respond with success
            echo "Placement status updated successfully.";
        } else {
            // Respond with error if query fails
            echo "Error updating placement status: " . mysqli_error($conn);
        }
    } else {
        // Respond with error if POST data is not set
        echo "Invalid request.";
    }

    mysqli_close($conn);
?>
