<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Soiled Web Shop</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="regisstyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
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
            <a href="loginform.php">Login</a>
            </div>
            </li>
        </ul>
    </nav>   
</div>
<div class="divider1"></div>
    <div class="container">
        <h2 style="font-size: 32px;">Register</h2>
        <form action="regissubmit.php" method="POST">
            <input type="text" name="fname" placeholder="First Name" required><br>
            <input type="text" name="lname" placeholder="Last Name" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <div style="position: relative;">
                <input type="password" name="password" id="password" placeholder = "Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*.]).{8,}"
               title="Must contain at least one number, one uppercase and lowercase letter, one special character, and be at least 8 or more characters long" required><br>
                <br>
                <?php if (isset($_GET['error'])): ?>
                    <label class="error"><?php echo htmlspecialchars($_GET['error']); ?></label><br>
                <?php endif; ?>
                <br>
            </div>
            <input type="submit" value="Create">
            <br>
            <br>
            <div class="create-account">
                <label>Already have an account? <a href="loginform.php"><b>Login</b></a></label>
            </div>
        </form>
    </div>
</body>
</html>
