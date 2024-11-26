<?php
if (!isset($_COOKIE['user'])) {
    header("location: signin_student.php");
    exit();
}

include("config.php");

// Fetch Student ID from the cookie
$StudentID = mysqli_real_escape_string($conn, $_COOKIE['user']);

// Fetch drop period information
$sql_drop_time = "SELECT Start_Date, End_Date FROM DropTime";
$result_drop_time = $conn->query($sql_drop_time);
if ($result_drop_time->num_rows > 0) {
    $row = $result_drop_time->fetch_assoc();
    $Start_Date = $row['Start_Date'];
    $End_Date = $row['End_Date'];
} else {
    $Start_Date = $End_Date = null;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Courses</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
<?php include 'sidenav.php'; ?>

<div class="main">
    <h2>Registered Courses</h2>
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
        </select>
        <input type="submit" value="Submit" name="save" style="font-size:20px">
    </form>
    <hr>

    <?php
    if (isset($_POST['save'])) {
        $Semester = mysqli_real_escape_string($conn, $_POST['Semester']);
        echo "<h3>Registered Courses for Semester: $Semester</h3>";
    ?>
        <table class="secondtable">
            <tr>
                <th>Course Code</th>
                <th>Course Title</th>
                <th>Credit</th>
                <th>Semester</th>
                <th>Drop Course</th>
            </tr>
            <?php
            $sql_registered = "SELECT c.CourseCode, c.CourseTitle, c.CreditHours, r.Semester
                               FROM Registrations r
                               JOIN Courses c ON r.CourseID = c.CourseID
                               WHERE r.StudentID = ? AND r.Semester = ? AND r.RegistrationStatus = 'Registered'";
            $stmt_registered = $conn->prepare($sql_registered);
            $stmt_registered->bind_param("is", $StudentID, $Semester);
            $stmt_registered->execute();
            $result_registered = $stmt_registered->get_result();

            if ($result_registered->num_rows > 0) {
                while ($row = $result_registered->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['CourseCode']}</td>
                            <td>{$row['CourseTitle']}</td>
                            <td>{$row['CreditHours']}</td>
                            <td>{$row['Semester']}</td>
                            <td>";
                    if (time() >= strtotime($Start_Date) && time() <= strtotime($End_Date)) {
                        echo "<button><a href='drop.php?CourseCode={$row['CourseCode']}&Semester={$row['Semester']}'>Drop</a></button>";
                    } else {
                        echo "<button disabled>Drop</button>";
                    }
                    echo "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No Registered Courses Found for the Selected Semester</td></tr>";
            }
            ?>
        </table>
    <?php
    }
    ?>
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
