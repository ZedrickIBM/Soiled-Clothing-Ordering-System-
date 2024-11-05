<?php
require "userconx.php";
session_start();

$userID = $_SESSION['userID'];
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "New passwords do not match.";
    } else {
        $sqlFetch = "SELECT password FROM users WHERE userID = '$userID'";
        $result = $conn->query($sqlFetch);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($current_password, $row['password'])) {
                $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $sqlUpdate = "UPDATE users SET password = '$new_password_hashed' WHERE userID = '$userID'";

                if ($conn->query($sqlUpdate) === TRUE) {
                    $success = "Password updated successfully.";
                    
                    // Insert audit log
                    $audit_sql = $conn->prepare("INSERT INTO audit_log (userID, action) VALUES (?, 'Update Password')");
                    $audit_sql->bind_param("i", $userID);
                    $audit_sql->execute();
                } else {
                    $error = "Error updating password: " . $conn->error;
                }
            } else {
                $error = "Current password is incorrect.";
            }
        } else {
            $error = "User not found.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password | Soiled</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="accountstyles.css">
</head>
<body>

<div class="container">
    <img class="logo" src="images/classic.png" alt="logo">
    <nav class="navbar">
        <ul>
            <li><a href="homepage.php">Home</a></li>
            <li><a href="aboutpage.php">About</a></li>
            <li><a href="productstables.php">Shop</a></li>
            <li>
                <div class="logos">
                    <a href="carttable.php"><img src="images/carticon.png" alt="cart-icon"></a>
                    <a href="accountpage.php"><img src="images/profileicon.png" alt="profile-icon"></a>
                </div>
            </li>
        </ul>
    </nav>   
</div>

<div class="divider1"></div>

<div class="container1">
    <h1>Change Password</h1>
    
    <form method="post" action="update_password.php" class="update-form">
        <?php if (!empty($error)): ?>
            <label class="error"><?php echo $error; ?></label>
        <?php elseif (!empty($success)): ?>
            <label class="success"><?php echo $success; ?></label>
        <?php endif; ?>
        <br>
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
