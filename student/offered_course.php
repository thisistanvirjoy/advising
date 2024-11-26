<?php
if (!isset($_COOKIE['user'])) {
    header("location: signin_student.php");
    exit();
}

include("config.php");

// Fetch Student ID from cookie
$StudentID = mysqli_real_escape_string($conn, $_COOKIE['user']);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offered Course</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<?php include 'sidenav.php'; ?>

<div class="main">
    <h2>Offered Courses</h2>
    <form action="" method="post">
        <label>Semester:</label>
        <select name="Semester" required>
            <option value=""></option>
            <?php
            // Generate semester options dynamically
            $currentYear = date("Y");
            for ($year = 2016; $year <= $currentYear + 5; $year++) {
                foreach (["Spring", "Summer", "Autumn"] as $semester) {
                    echo "<option value='$semester-$year'>$semester-$year</option>";
                }
            }
            ?>
        </select>&nbsp;
        <input type="submit" value="Submit" name="save" style="font-size:20px">
    </form>
    <hr>
    <table>
        <tr>
            <th>Course Code</th>
            <th>Course Title</th>
            <th>Credit</th>
            <th>Prerequisite</th>
            <th>Semester</th>
            <th>Faculty Name</th>
            <th>Add Course</th>
        </tr>
        <?php
        if (isset($_POST['save'])) {
            $Semester = mysqli_real_escape_string($conn, $_POST['Semester']);

            // Calculate CGPA from StudentResults
            $sql_cgpa = "SELECT SUM(sr.Grade * c.CreditHours) / SUM(c.CreditHours) AS CGPA
                         FROM StudentResults sr
                         JOIN Courses c ON sr.CourseID = c.CourseID
                         WHERE sr.StudentID = ?";
            $stmt = $conn->prepare($sql_cgpa);
            $stmt->bind_param("i", $StudentID);
            $stmt->execute();
            $result_cgpa = $stmt->get_result();
            $row_cgpa = $result_cgpa->fetch_assoc();
            $currentCGPA = $row_cgpa['CGPA'] ?? 0;

            // Calculate remaining credits
            $maxCredits = ($currentCGPA >= 3.7) ? 16 : (($currentCGPA >= 3.0) ? 13 : (($currentCGPA >= 2.0) ? 10 : 6));
            $sql_taken = "SELECT SUM(c.CreditHours) AS TakenCredits
                          FROM Registrations r
                          JOIN Courses c ON r.CourseID = c.CourseID
                          WHERE r.StudentID = ? AND r.RegistrationStatus = 'Registered'";
            $stmt_taken = $conn->prepare($sql_taken);
            $stmt_taken->bind_param("i", $StudentID);
            $stmt_taken->execute();
            $result_taken = $stmt_taken->get_result();
            $row_taken = $result_taken->fetch_assoc();
            $takenCredits = $row_taken['TakenCredits'] ?? 0;
            $remainingCredits = $maxCredits - $takenCredits;

            echo "<center>CGPA: $currentCGPA -- Maximum Credits: $maxCredits -- Taken Credits: $takenCredits -- Remaining Credits: $remainingCredits</center>";

            // Fetch offered courses
            $sql_offered = "SELECT co.OfferingID, c.CourseCode, c.CourseTitle, c.CreditHours, c.Prerequisite, co.Semester, u.Name AS FacultyName
                            FROM CourseOfferings co
                            JOIN Courses c ON co.CourseID = c.CourseID
                            LEFT JOIN Users u ON co.FacultyID = u.UserID
                            WHERE co.Semester = ?";
            $stmt_offered = $conn->prepare($sql_offered);
            $stmt_offered->bind_param("s", $Semester);
            $stmt_offered->execute();
            $result_offered = $stmt_offered->get_result();

            if ($result_offered->num_rows > 0) {
                while ($row = $result_offered->fetch_assoc()) {
                    $Prerequisite = $row['Prerequisite'];
                    $isEligible = true;

                    // Check if prerequisites are met
                    if ($Prerequisite && $Prerequisite !== 'No Prerequisite') {
                        $sql_prereq = "SELECT * FROM StudentResults sr
                                       JOIN Courses c ON sr.CourseID = c.CourseID
                                       WHERE sr.StudentID = ? AND c.CourseCode = ? AND sr.Grade IS NOT NULL";
                        $stmt_prereq = $conn->prepare($sql_prereq);
                        $stmt_prereq->bind_param("is", $StudentID, $Prerequisite);
                        $stmt_prereq->execute();
                        $result_prereq = $stmt_prereq->get_result();
                        if ($result_prereq->num_rows === 0) {
                            $isEligible = false;
                        }
                    }

                    echo "<tr>
                            <td>{$row['CourseCode']}</td>
                            <td>{$row['CourseTitle']}</td>
                            <td>{$row['CreditHours']}</td>
                            <td>{$Prerequisite}</td>
                            <td>{$row['Semester']}</td>
                            <td>{$row['FacultyName']}</td>
                            <td>";
                    if ($isEligible && $remainingCredits > 0) {
                        echo "<button><a href='add.php?CourseID={$row['OfferingID']}'>Add</a></button>";
                    } else {
                        echo "<button disabled>Not Eligible</button>";
                    }
                    echo "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No Offered Courses Found for the Selected Semester</td></tr>";
            }
        }
        ?>
    </table>
</div>

<script>
var dropdown = document.getElementsByClassName("dropdown-btn");
for (var i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var dropdownContent = this.nextElementSibling;
        if (dropdownContent.style.display === "block") {
            dropdownContent.style.display = "none";
        } else {
            dropdownContent.style.display = "block";
        }
    });
}
</script>

</body>
</html>
