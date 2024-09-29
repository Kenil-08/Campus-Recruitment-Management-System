<?php
    include '../db.php';
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php'); 
        exit();
    }

    if (isset($_POST['student_id']) && isset($_POST['job_id']) && isset($_POST['placement_status'])) {
        // Sanitize inputs to avoid SQL injection
        $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
        $job_id = mysqli_real_escape_string($conn, $_POST['job_id']);
        $placement_status = mysqli_real_escape_string($conn, $_POST['placement_status']);

        $updateQuery = "UPDATE students SET placement_status = '$placement_status' WHERE student_id = '$student_id'";

        if (mysqli_query($conn, $updateQuery)) {
            if ($placement_status == 1) { 
                $checkQuery = "SELECT * FROM placements WHERE student_id = '$student_id' AND job_id = '$job_id'";
                $checkResult = mysqli_query($conn, $checkQuery);

                if (mysqli_num_rows($checkResult) == 0) {
                    $insertQuery = "INSERT INTO placements (student_id, job_id, placement_date) VALUES ('$student_id', '$job_id', NOW())";

                    if (mysqli_query($conn, $insertQuery)) {
                        echo "Placement status updated successfully and recorded.";
                    } else {
                        echo "Error recording placement status: " . mysqli_error($conn);
                    }
                } else {
                    echo "Placement already exists for this student and job.";
                }
            } else {
                // If marked as unplaced, Delete the entry from the placements table
                $deleteQuery = "DELETE FROM placements WHERE student_id = '$student_id' AND job_id = '$job_id'";
                if (mysqli_query($conn, $deleteQuery)) {
                    echo "Placement status updated to unplaced and record deleted.";
                } else {
                    echo "Error deleting placement record: " . mysqli_error($conn);
                }
            }
        } else {
            echo "Error updating placement status: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid request.";
    }
    mysqli_close($conn);
?>
