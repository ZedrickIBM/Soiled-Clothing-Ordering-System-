<?php
require 'adminconx.php';

session_start();
$id = $_SESSION['userID'];
$sql = "SELECT * FROM users WHERE userID = '$id'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Soiled</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="adminstyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <!-- Add font awesome for camera icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Add CSS for circular profile picture -->
</head>
<body>

<div class="sidebar">
    <div class="logo-container">
        <img src="images\classic.png" alt="Logo">
    </div>
    <a href="adminpage.php">Dashboard</a>
    <a href="usertables.php" >Users</a>
    <a href="Aproductadd.php">Products</a>
    <a href="orderspage.php">Orders</a>
    <a href="auditlog.php" >Audit Log</a>
    <a href="adminaccount.php" class="active">Admin Account</a>
    <div class="logout-container">
        <a href="loginform.php">Log out</a>
    </div>
</div>


<div class="content">
    <?php if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        ?>
        <!-- Display profile picture with circular shape -->
        <h2>Admin Account Details</h2>
        <div class="profile-pic-container">
            <img src="Images/<?php echo !empty($row['profile_pic']) ? $row['profile_pic'] : 'default_pic.jpg'; ?>" alt="Profile Picture" class="profile-pic">
        </div>
        <br>
        <!-- Display user's details -->
        <label><b><?php echo $row['fname'] . " " . $row['lname']; ?></b></label>
        <br>
        <label style="font-size: 16px;"><?php echo $row['email']; ?></label>
        <br>
        <br>
        <a href="editadminaccount.php" class="edit-link">Edit Credentials</a>
        <br>
        <a href="changeadminpassword.php" class="password-link">Change Password</a>
        <?php } else
        {
            echo "<p>No admin found with that ID: $id</p>";
    } ?>
</div>

</body>
</html>
