<?php
include("config.php");

if (isset($_GET['CourseID'])) {
    // Securely get the student ID from cookies
    if (isset($_COOKIE['user'])) {
        $StudentID = mysqli_real_escape_string($conn, $_COOKIE['user']);
    } else {
        echo "<script>
                alert('User not authenticated. Please log in.');
                window.location.href = 'signin_student.php';
              </script>";
        exit();
    }

    // Get the CourseID from the GET request
    $CourseID = mysqli_real_escape_string($conn, $_GET['CourseID']);

    // Check if the course approval request already exists
    $sql_query = "SELECT * FROM CourseApprovals WHERE StudentID = ? AND CourseID = ?";
    $stmt = $conn->prepare($sql_query);
    $stmt->bind_param("ii", $StudentID, $CourseID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
                alert('Request has already been sent.');
                window.location.href = 'Offered_Course.php';
              </script>";
    } else {
        // Insert the new course approval request
        $sql_query2 = "INSERT INTO CourseApprovals (StudentID, CourseID, ApprovedBy) VALUES (?, ?, NULL)";
        $stmt2 = $conn->prepare($sql_query2);
        $stmt2->bind_param("ii", $StudentID, $CourseID);

        if ($stmt2->execute()) {
            echo "<script>
                    alert('Request sent to advisor for approval.');
                    window.location.href = 'Offered_Course.php';
                  </script>";
        } else {
            echo "Error: " . $stmt2->error;
        }
    }

    // Close statements
    $stmt->close();
    $stmt2->close();
}
?>
