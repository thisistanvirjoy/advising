<?php
include 'config.php';

if (isset($_POST['save'])) {
    // Securely fetch form inputs
    $S_name = mysqli_real_escape_string($conn, $_POST['S_name']);
    $Email = mysqli_real_escape_string($conn, $_POST['Email']);
    $Phone = mysqli_real_escape_string($conn, $_POST['Phone']);
    $Password = mysqli_real_escape_string($conn, $_POST['Password']);

    // Check if email already exists
    $check_email_query = "SELECT * FROM Users WHERE Email = ?";
    $stmt = $conn->prepare($check_email_query);
    $stmt->bind_param("s", $Email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
                alert('Email already registered. Please log in.');
                window.location.href = 'signin_student.php';
              </script>";
        exit();
    }

    // Insert new student record into Users table
    $role = 'Student';
    $insert_query = "INSERT INTO Users (Name, Email, Phone, Password, Role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sssss", $S_name, $Email, $Phone, $Password, $role);

    if ($stmt->execute()) {
        echo "<script>
                alert('Your account has been created successfully. Please log in.');
                window.location.href = 'signin_student.php';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Signup (Student)</title>

    <link rel="stylesheet" href="../css/style2.css">
</head>
<body>

<?php include '../partials/nav.php'; ?>

<div class="container2">
    <h1 class="text-center">Signup (Student)</h1>

    <form action="" method="post">
        <div class="form-group">
            <input type="text" class="form-control" id="S_Name" name="S_name" placeholder="Enter your Name" required>
        </div>
        <div class="form-group">
            <input type="email" class="form-control" id="Email" name="Email" placeholder="Enter your Email" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="Phone" name="Phone" placeholder="Enter your Phone Number" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="Password" name="Password" placeholder="Enter Password" required>
        </div>
        <button type="submit" class="btn btn-primary" value="Submit" name="save">Signup</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
    </form>
</div>

<?php include '../partials/footer.php'; ?>

<!-- Optional JavaScript -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
