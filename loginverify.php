<?php
require "acclog.php";
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

$sqlFetch = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sqlFetch);

if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];

    if(password_verify($password, $hashed_password)){
        $_SESSION['userID'] = $row['userID'];
        $_SESSION['userType'] = $row['userType'];

        // Insert login action into audit log
        $sql = "INSERT INTO audit_log(userID, action)
                VALUES (" . $row['userID'] . ", 'Login')";
        if($conn->query($sql) === TRUE){
            // Redirect to the appropriate page based on user type
            if ($row['userType'] == 1) {
                header("Location: adminpage.php");
            } else if ($row['userType'] == 2) {
                header("Location: homepage.php");
            } else {
                header("Location: loginerror.php");
            }
            exit; // Exit to prevent further execution
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Password verification failed
        // Redirect to login error page
        header("Location: loginerror.php");
        exit; // Exit to prevent further execution
    }
} else {
    // No user found with the provided email
    // Redirect to login error page
    header("Location: loginerror.php");
    exit; // Exit to prevent further execution
}
// Close the database connection
mysqli_close($conn);


?>


