<?php
// Redirect to login if user is not authenticated
if (!isset($_COOKIE['user'])) {
    header("location: signin_student.php");
    exit();
}

include("config.php");

// Securely fetch the Student ID from the cookie
$StudentID = mysqli_real_escape_string($conn, $_COOKIE['user']);

// Fetch student details from the Users table
$sql_student = "
    SELECT 
        Users.UserID AS S_id,
        Users.Name AS S_name,
        Users.Email,
        Users.Phone,
        Advisor.UserID AS Ad_id,
        Advisor.Name AS Ad_name
    FROM 
        Users
    LEFT JOIN 
        Advising ON Users.UserID = Advising.StudentID
    LEFT JOIN 
        Users AS Advisor ON Advising.AdvisorID = Advisor.UserID
    WHERE 
        Users.UserID = ? AND Users.Role = 'Student'";

$stmt = $conn->prepare($sql_student);
$stmt->bind_param("i", $StudentID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $Id = $row["S_id"];
    $Name = $row["S_name"];
    $Email = $row["Email"];
    $Phone = $row["Phone"];
    $Ad_id = $row["Ad_id"] ?? "Not Assigned";
    $Ad_name = $row["Ad_name"] ?? "Not Assigned";
} else {
    echo "No Result";
    exit();
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include 'sidenav.php'; ?>

<div class="main">
    <h2>Welcome to Student Panel</h2>
    <div class="student-profile py-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body pt-0">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Student ID</th>
                                    <td width="2%">:</td>
                                    <td> <?php echo htmlspecialchars($Id); ?> </td>
                                </tr>
                                <tr>
                                    <th width="30%">Name</th>
                                    <td width="2%">:</td>
                                    <td> <?php echo htmlspecialchars($Name); ?> </td>
                                </tr>
                                <tr>
                                    <th width="30%">Email</th>
                                    <td width="2%">:</td>
                                    <td> <?php echo htmlspecialchars($Email); ?> </td>
                                </tr>
                                <tr>
                                    <th width="30%">Phone Number</th>
                                    <td width="2%">:</td>
                                    <td> <?php echo htmlspecialchars($Phone); ?> </td>
                                </tr>
                                <tr>
                                    <th width="30%">Advisor ID</th>
                                    <td width="2%">:</td>
                                    <td> <?php echo htmlspecialchars($Ad_id); ?> </td>
                                </tr>
                                <tr>
                                    <th width="30%">Advisor Name</th>
                                    <td width="2%">:</td>
                                    <td> <?php echo htmlspecialchars($Ad_name); ?> </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var dropdown = document.getElementsByClassName("dropdown-btn");
for (var i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var dropdownContent = this.nextElementSibling;
        dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
    });
}
</script>

</body>
</html>
