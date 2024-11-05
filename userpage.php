<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <title>User</title>
    <link rel="icon" type="image/x-icon" href="logo.png">
    <link rel="stylesheet" type="text/css" href="userstyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd; /* Border for the entire table */
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd; /* Border between rows */
        }

        th {
            background-color: #f2f2f2;
        }

        h1 {
            margin-top: 0;
        }

        form {
            margin-top: 20px;
        }

        input[type="submit"] {
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
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
        <table>
            <tr>
                <td>First Name</td>
                <td><?php echo $row['fname']; ?></td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td><?php echo $row['lname']; ?></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><?php echo $row['password']; ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo $row['email']; ?></td>
            </tr>
        </table>
        <form method="post" action="update_picture.php" enctype="multipart/form-data">
    <label for="profile_pic">Upload New Profile Picture:</label>
    <input type="file" class="profile_pic" id="profile_pic">
    <input type="submit" value="Upload">
<img src="Images/<?php echo $row['profile_pic']; ?>" alt="Profile Picture">
</form>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="submit" name="logout" value="Logout">
        </form>
    <?php } ?>
    </div>
</body>
</html>
