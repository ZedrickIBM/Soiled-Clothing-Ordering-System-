<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Soiled</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="loginstyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">   
</head>
<body>
<div class="container1">
    <img class = "logo" src="images/classic.png" alt="logo">
    <nav class="navbar">
        <ul>
            <li><a href="soiled.php" >Home</a></li>
            <li><a href="aboutus.html">About</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><div class = "logos">
            <a href="loginform.php" style="font-weight: 600;">Login</a>
            </div>
            </li>
        </ul>
    </nav>   
</div>
<div class="divider1"></div>
    <div class="container">
        <h2 style="font-size: 32px;">Login</h2>
        <form action="loginverify.php" method="POST">
            <input type="email" name="email" id= "email" placeholder="Email" required>
            <div style="position: relative;">
                <input type="password" name="password" id="password" placeholder="Password" required><br>
            <br>
            <!--<div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div>-->
            <br>
            <br>
            <input type="submit" value="Sign In">
            <br>
            <br>
            <div class="create-account">
                <label>No account yet?<a href="registerform.php"> <b>Create Account</b></a></label>
            </div>
        </form>
    </div>
</body>
</html>
