<?php
if (!isset($_COOKIE['user'])) {
    header("location: signin_student.php");
    exit();
}

include("config.php");

// Fetch Student ID from the cookie
$StudentID = mysqli_real_escape_string($conn, $_COOKIE['user']);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Results</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<?php include 'sidenav.php'; ?>

<div class="main">
    <h2>All Results</h2>
    <table>
        <tr>
            <th>Course Code</th>
            <th>Course Title</th>
            <th>Credit</th>
            <th>GPA</th>
            <th>Grade Point</th>
            <th>Semester</th>
            <th>Status</th>
        </tr>
        <?php
        // Query to fetch student results
        $sql = "SELECT c.CourseCode, c.CourseTitle, c.CreditHours, sr.Grade, sr.Semester, sr.AcademicYear
                FROM StudentResults sr
                JOIN Courses c ON sr.CourseID = c.CourseID
                WHERE sr.StudentID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $StudentID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $totalCredits = 0;
            $totalGradePoints = 0;
            while ($row = $result->fetch_assoc()) {
                $grade = $row['Grade'];
                $gradePoint = calculateGradePoint($grade, $row['CreditHours']);
                $status = $grade ? "Completed" : "In Progress";

                echo "<tr>
                        <td>{$row['CourseCode']}</td>
                        <td>{$row['CourseTitle']}</td>
                        <td>{$row['CreditHours']}</td>
                        <td>{$grade}</td>
                        <td>{$gradePoint}</td>
                        <td>{$row['Semester']} - {$row['AcademicYear']}</td>
                        <td>{$status}</td>
                      </tr>";

                if ($grade) {
                    $totalCredits += $row['CreditHours'];
                    $totalGradePoints += $gradePoint;
                }
            }

            $cgpa = $totalCredits > 0 ? round($totalGradePoints / $totalCredits, 2) : 0;
        } else {
            echo "<tr><td colspan='7'>No Results Found</td></tr>";
        }

        $stmt->close();
        ?>
        <tr>
            <th colspan="2">Total</th>
            <th><?php echo $totalCredits ?? 0; ?></th>
            <th>CGPA</th>
            <th><?php echo $cgpa ?? "N/A"; ?></th>
            <th colspan="2"></th>
        </tr>
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

<?php
// Function to calculate grade points
function calculateGradePoint($grade, $creditHours) {
    $gradePointMap = [
        'A' => 4.0,
        'A-' => 3.7,
        'B+' => 3.3,
        'B' => 3.0,
        'B-' => 2.7,
        'C+' => 2.3,
        'C' => 2.0,
        'C-' => 1.7,
        'D+' => 1.3,
        'D' => 1.0,
        'F' => 0.0
    ];

    return isset($gradePointMap[$grade]) ? $gradePointMap[$grade] * $creditHours : 0;
}
?>
