<?php
// Start the session for error/success messages if needed
session_start();
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Login (Student)</title>
    <link rel="stylesheet" href="../css/style2.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#"><img id="logo" src="../img/logo.png" alt="Logo"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../Admin/index.php">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="signin_student.php">Student</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Advisor/signin_advisor.php">Advisor</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Faculty/signin_faculty.php">Faculty</a>
                </li>
            </ul>
        </div>
        <marquee direction="left">
            <p>Welcome to Online Course Registration System</p>
        </marquee>
    </nav>

    <div class="container1">
        <h1 class="text-center">Login (Student)</h1>

        <?php
        // Display session messages if available
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        ?>

        <form action="login.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" id="S_id" name="S_id" placeholder="Enter your ID" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="Password" name="Password" placeholder="Enter Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
            <p id="para2">Don't have an account? <a href="signup_student.php">Sign Up/Register</a></p>
        </form>
    </div>

    <footer class="footer">
        Copyright &copy; 2024 All rights reserved by Tasnim Islam Samin
    </footer>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>
