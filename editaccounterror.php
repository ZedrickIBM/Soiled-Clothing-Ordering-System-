<?php
require "userconx.php";
session_start();

$userID = $_SESSION['userID'];

$sqlFetch = "SELECT * FROM users WHERE userID = '$userID'";
$result = $conn->query($sqlFetch);
$row = $result->fetch_assoc();

$error_msg = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Credentials | Soiled</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="accountstyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .error {
            color: red;
            font-size: 1em;
        }
    </style>
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
    <h1>Edit Credentials</h1>
    
    <?php if ($error_msg): ?>
        <p class="error"><?php echo htmlspecialchars($error_msg); ?></p>
    <?php endif; ?>

    <form method="post" action="update_credentials.php" class="update-form">
        <label for="fname">First Name</label>
        <input type="text" name="fname" id="fname" value="<?php echo htmlspecialchars($row['fname']); ?>">
        <br>
        <label for="lname">Last Name</label>
        <input type="text" name="lname" id="lname" value="<?php echo htmlspecialchars($row['lname']); ?>">
        <br>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($row['email']); ?>">
        <br>
        <input type="submit" value="Update" class="update-btn">
    </form>
    <br>
    <a href="accountpage.php" class="back-link">Back to Account Details</a>
</div>

</body>
</html>

<?php $conn->close(); ?>
