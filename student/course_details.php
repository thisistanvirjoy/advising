<?php
if (!isset($_COOKIE['user'])) {
    header("location: signin_student.php");
    exit();
}

include("config.php");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<?php include 'sidenav.php'; ?>

<div class="main">
    <h2>All Course Details</h2>
    <table>
        <tr>
            <th>Course Code</th>
            <th>Course Title</th>
            <th>Credit Hours</th>
            <th>Prerequisite</th>
        </tr>
        <?php
        $sql = "SELECT CourseCode, CourseTitle, CreditHours, Prerequisite FROM Courses";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['CourseCode']}</td>
                        <td>{$row['CourseTitle']}</td>
                        <td>{$row['CreditHours']}</td>
                        <td>{$row['Prerequisite']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No Results Found</td></tr>";
        }

        $conn->close();
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
