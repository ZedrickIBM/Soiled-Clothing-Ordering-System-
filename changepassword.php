<?php
require "userconx.php";
session_start();

$userID = $_SESSION['userID'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password | Soiled</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="accountstyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
    <img class = "logo" src="images/classic.png" alt="logo">
    <nav class="navbar">
        <ul>
            <li><a href="homepage.php">Home</a></li>
            <li><a href="aboutpage.php">About</a></li>
            <li><a href="shoppage.php">Shop</a></li>
            <li><div class = "logos">
            <a href = "carttable.php"><img src = "images/carticon.png" alt = "cart-icon"></a>
            <a href = "accountpage.php"><img src = "images/profileicon.png" alt = "profile-icon"></a>
            </div>
            </li>
        </ul>
    </nav>   
</div>

<div class="divider1"></div>

<div class="container1">
    <h1>Change Password</h1>
    
    <form method="post" action="update_password.php" class="update-form">
        <label for="current_password">Current Password</label>
        <input type="password" name="current_password" id="current_password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*.]).{8,}"
               title="Must contain at least one number, one uppercase and lowercase letter, one special character, and be at least 8 or more characters long" required>
        <br>
        <label for="new_password">New Password</label>
        <input type="password" name="new_password" id="new_password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*.]).{8,}"
               title="Must contain at least one number, one uppercase and lowercase letter, one special character, and be at least 8 or more characters long" required>
        <br>
        <label for="confirm_password">Confirm New Password</label>
        <input type="password" name="confirm_password" id="confirm_password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*.]).{8,}"
               title="Must contain at least one number, one uppercase and lowercase letter, one special character, and be at least 8 or more characters long" required>
        <br>
        <input type="submit" value="Update Password" class="update-btn">
    </form>
    <br>
    <a href="accountpage.php" class="back-link">Back to Account Details</a>
</div>

</body>
</html>

<?php $conn->close(); ?>
