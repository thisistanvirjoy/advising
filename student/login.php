<?php
require('config.php');

// Fetch and sanitize input
$s_id = isset($_POST['S_id']) ? mysqli_real_escape_string($conn, $_POST['S_id']) : '';
$password = isset($_POST['Password']) ? mysqli_real_escape_string($conn, $_POST['Password']) : '';

// Check if inputs are provided
if (empty($s_id) || empty($password)) {
    echo "<script>
            alert('Please fill in all fields.');
            window.location.href = 'signin_student.php';
          </script>";
    exit();
}

// Query the Users table for a student
$sql = "SELECT UserID, Password FROM Users WHERE UserID = ? AND Role = 'Student'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $s_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // Verify password
    if ($row['Password'] === $password) {
        // Set a secure cookie for user identification
        setcookie("user", $s_id, time() + (86400 * 30), "/", "", true, true); // Secure and HTTP-only cookie
        header("location: student.php");
    } else {
        echo "<script>
                alert('Invalid credentials. Please try again.');
                window.location.href = 'signin_student.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid credentials. Please try again.');
            window.location.href = 'signin_student.php';
          </script>";
}

// Close connections
$stmt->close();
$conn->close();
?>
