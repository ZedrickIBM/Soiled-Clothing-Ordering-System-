<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="userstyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

</head>
<body>
<div class="container">
    <img class = "logo" src="images/classic.png" alt="logo">
    <nav class="navbar">
        <ul>
            <li><a href="homepage.php" style="font-weight: 600;">Home</a></li>
            <li><a href="aboutpage.php">About</a></li>
            <li><a href="productstables.php">Shop</a></li>
            <li><a href="#stories">Stories</a></li>
            <li><div class = "logos">
            <a href = "carttable.php"><img src = "images/carticon.png" alt = "cart-icon"></a>
            <a href = "userprofile.php"><img src = "images/profileicon.png" alt = "profile-icon"></a>
            </div>
            </li>
        </ul>
    </nav>   
</div>
    <div class="container1">
        <?php
            require "userconx.php";
            session_start();

            if(isset($_POST['logout'])){
                // Destroy the session
                session_destroy();

                // Redirect to login form
                header("Location: loginform.php");
                exit;
            }

            $userID = $_SESSION['userID'];

            $sqlFetch = "SELECT * FROM users WHERE userID = '$userID'";
            $result = $conn->query($sqlFetch); 
            $row = $result->fetch_assoc();
        ?>

        <?php if ($result->num_rows > 0) { ?>
            <h1>Welcome <?php echo $row['fname'] . " " . $row['lname']; ?> !</h1>
            <img src="Images/<?php echo $row['profile_pic']; ?>" alt="Profile Picture" class="profile-pic">
            <form method="post" action="update_picture.php" enctype="multipart/form-data" class="upload-form">
                <label for="profile_pic">Upload New Profile Picture:</label>
                <input type="file" name="profile_pic" id="profile_pic">
                <input type="submit" value="Upload">
            </form>
            <form method="post" action="update_credentials.php">
                <table>
                    <tr>
                        <td>First Name</td>
                        <td><input type="text" name="fname" value="<?php echo $row['fname']; ?>"></td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td><input type="text" name="lname" value="<?php echo $row['lname']; ?>"></td>
                    </tr>
                </table>
                <br>
                <input type="submit" value="Update">
                
            </form>
            <form method="post" action="logoutaudit.php">
                <input type="submit" name="logout" value="Logout">
            </form>
        <?php } ?>
    </div>
</body>
</html>
