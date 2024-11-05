<?php
require "userconx.php";
session_start();

$userID = $_SESSION['userID'];

$sqlFetch = "SELECT * FROM users WHERE userID = '$userID'";
$result = $conn->query($sqlFetch);
$row = $result->fetch_assoc();

// Check if there is an error message in the session
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']); // Clear the error message from the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account Details | Soiled</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="accountstyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
    <img class="logo" src="images/classic.png" alt="logo">
    <nav class="navbar">
        <ul>
            <li><a href="homepage.php">Home</a></li>
            <li><a href="aboutpage.php">About</a></li>
            <li><a href="shoppage.php">Shop</a></li>
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
    <h1>Edit Account Details</h1>
    <?php if ($error_message): ?>
        <div class="error-message">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    <!-- Change Picture Section -->
    <div class="change-picture-section">
        <h2>Change Profile Picture</h2>
        <div class="profile-pic-container">
            <img src="Images/<?php echo !empty($row['profile_pic']) ? $row['profile_pic'] : 'default_pic.jpg'; ?>" alt="Profile Picture" class="profile-pic">
            <form action="update_profile_pic.php" method="post" enctype="multipart/form-data">
                <label for="file-upload" class="camera-icon"><i class="fas fa-camera"></i></label>
                <input id="file-upload" type="file" name="profile_pic" style="display: none;">
                <input type="submit" value="Upload" class="upload-btn">
            </form>
        </div>
    </div>
    <form method="post" action="update_credentials.php" class="update-form">
        <label for="fname">First Name</label>
        <input type="text" name="fname" id="fname" value="<?php echo $row['fname']; ?>">
        <br>
        <label for="lname">Last Name</label>
        <input type="text" name="lname" id="lname" value="<?php echo $row['lname']; ?>">
        <br>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php echo $row['email']; ?>">
        <br>
        <input type="submit" value="Update" class="update-btn">
    </form>
    <br>
    <a href="accountpage.php" class="back-link">Back to Account Details</a>
</div>

</body>
</html>

<?php $conn->close(); ?>
