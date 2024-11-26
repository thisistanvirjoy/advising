<?php
include("config.php");

if (isset($_GET['Course_Code'])) {
    // Securely fetch student ID from cookie
    if (!isset($_COOKIE['user'])) {
        header("location: signin_student.php");
        exit();
    }
    $StudentID = mysqli_real_escape_string($conn, $_COOKIE['user']);

    // Fetch course details from GET parameters
    $Course_Code = mysqli_real_escape_string($conn, $_GET['Course_Code']);
    $Course_Title = mysqli_real_escape_string($conn, $_GET['Course_Title']);
    $Credit = mysqli_real_escape_string($conn, $_GET['Credit']);
    $Semester = mysqli_real_escape_string($conn, $_GET['Semester']);

    // Get the CourseID from the Courses table
    $sql_course = "SELECT CourseID FROM Courses WHERE CourseCode = ?";
    $stmt_course = $conn->prepare($sql_course);
    $stmt_course->bind_param("s", $Course_Code);
    $stmt_course->execute();
    $result_course = $stmt_course->get_result();

    if ($result_course->num_rows > 0) {
        $row_course = $result_course->fetch_assoc();
        $CourseID = $row_course['CourseID'];

        // Check if the course is already marked as dropped
        $sql_check_drop = "SELECT * FROM DroppedCourses WHERE StudentID = ? AND CourseID = ?";
        $stmt_check_drop = $conn->prepare($sql_check_drop);
        $stmt_check_drop->bind_param("ii", $StudentID, $CourseID);
        $stmt_check_drop->execute();
        $result_check_drop = $stmt_check_drop->get_result();

        if ($result_check_drop->num_rows > 0) {
            echo "<script>
                    alert('Request to drop this course has already been sent.');
                    window.location.href = 'registered_course.php';
                  </script>";
        } else {
            // Insert into DroppedCourses table
            $sql_drop = "INSERT INTO DroppedCourses (StudentID, CourseID, DropReason, BatchYear) VALUES (?, ?, ?, ?)";
            $stmt_drop = $conn->prepare($sql_drop);
            $DropReason = "Student initiated drop request"; // Default reason
            $BatchYear = date("Y"); // Assuming current year as BatchYear
            $stmt_drop->bind_param("iisi", $StudentID, $CourseID, $DropReason, $BatchYear);

            if ($stmt_drop->execute()) {
                // Update the Registrations table to mark the course as dropped
                $sql_update_registration = "UPDATE Registrations SET RegistrationStatus = 'Dropped' WHERE StudentID = ? AND CourseID = ?";
                $stmt_update_registration = $conn->prepare($sql_update_registration);
                $stmt_update_registration->bind_param("ii", $StudentID, $CourseID);

                if ($stmt_update_registration->execute()) {
                    echo "<script>
                            alert('Request sent to advisor for course drop.');
                            window.location.href = 'registered_course.php';
                          </script>";
                } else {
                    echo "Error updating registration status: " . $stmt_update_registration->error;
                }
            } else {
                echo "Error inserting into DroppedCourses: " . $stmt_drop->error;
            }
        }
    } else {
        echo "<script>
                alert('Invalid Course Code. Please try again.');
                window.location.href = 'registered_course.php';
              </script>";
    }

    // Close all statements
    $stmt_course->close();
    $stmt_check_drop->close();
    $stmt_drop->close();
    $stmt_update_registration->close();
}

$conn->close();
?>
