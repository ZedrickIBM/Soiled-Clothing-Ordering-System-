<?php
require 'userconx.php';
session_start();

$userID = $_SESSION['userID'];
$error = ""; // Initialize error variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Perform some validation if needed

    // Check if the current password matches the one in the database
    $sql = "SELECT password FROM users WHERE userID = '$userID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];
        if (password_verify($currentPassword, $storedPassword)) {
            // Current password matches, proceed to update
            if ($newPassword === $confirmPassword) {
                // Hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update the password in the database
                $updateSql = "UPDATE users SET password='$hashedPassword' WHERE userID = '$userID'";

                if ($conn->query($updateSql) === TRUE) {
                    $_SESSION['password_changed'] = true;
                    echo '<script>alert("Changed password successfully."); window.location.href = "adminaccount.php";</script>';
                    exit;
                } else {
                    $error = "Error updating password: " . $conn->error;
                }
            } else {
                $error = "New password and confirm password do not match.";
            }
        } else {
            $error = "Current password is incorrect.";
        }
    } else {
        $error = "User not found.";
    }
} else {
    // Redirect or handle invalid request
}

$conn->close();
?>


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Admin Password | Soiled</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="adminstyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="sidebar">
    <div class="logo-container">
        <img src="images\classic.png" alt="Logo">
    </div>
    <a href="adminpage.php" >Home</a>
    <a href="usertables.php">Users</a>
    <a href="Aproductadd.php">Products</a>
    <a href="orderstable.php">Orders</a>
    <a href="auditlog.php">Audit Log</a>
    <a href="adminaccount.php" class="active">Admin Account</a>
    <div class="logout-container">
        <a href="loginform.php">Log out</a>
    </div>
</div>

<div class="content">
    <div class="divider1"></div>

    <div class="container1">
    <h1>Change Admin Password</h1>
    
    <?php if($error !== ""): ?>
        <label class="error"><?php echo $error; ?></label>
    <?php endif; ?>
    
    <form method="post" action="changeadminpassword.php" class="update-form">
        <label for="currentPassword" >Current Password</label>
        <input type="password" name="currentPassword" id="currentPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*.]).{8,}"
               title="Must contain at least one number, one uppercase and lowercase letter, one special character, and be at least 8 or more characters long" required>
        <br>
        <label for="newPassword">New Password</label>
        <input type="password" name="newPassword" id="newPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*.]).{8,}"
               title="Must contain at least one number, one uppercase and lowercase letter, one special character, and be at least 8 or more characters long" required>
        <br>
        <label for="confirmPassword">Confirm New Password</label>
        <input type="password" name="confirmPassword" id="confirmPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*.]).{8,}"
               title="Must contain at least one number, one uppercase and lowercase letter, one special character, and be at least 8 or more characters long" required>
        <br>
        <input type="submit" value="Change Password" class="update-btn">
    </form>
    <br>
    <a href="adminaccount.php" class="back-link">Back to Admin Account</a>
</div>

</div>

</body>
</html>
