<?php
require 'userconx.php';
session_start();

$userID = $_SESSION['userID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];

    // Perform some validation if needed

    $sql = "UPDATE users SET fname='$fname', lname='$lname', email='$email' WHERE userID = '$userID'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['credentials_updated'] = true;
        echo '<script>alert("Credentials updated successfully."); window.location.href = "adminaccount.php";</script>';
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        exit;
    }
} else {
    // Redirect or handle invalid request
}

$conn->close();
?>
