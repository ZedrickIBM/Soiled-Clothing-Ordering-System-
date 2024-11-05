<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | Admin</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="adminstyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="sidebar">
    <div class="logo-container">
        <img src="images\classic.png" alt="Logo">
    </div>
    <a href="adminpage.php">Home</a>
    <a href="usertables.php">Users</a>
    <a href="productstables.php" class="active">Products</a>
    <a href="orderstable.php">Orders</a>
    <a href="#">Admin Account</a>
    <div class="logout-container">
        <a href="loginform.php">Log out</a>
    </div>
</div>

<div class="content">
    <?php
    require 'adminconx.php';

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT prodID, prodName, prodDesc, prodPrice, prodAvailability, date_added FROM products";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div class='table-container'>";
        echo "<table>";
        echo "<tr><th>Product ID</th><th>Product Name</th><th>Description</th><th>Price</th><th>Availability</th><th>Date Added</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["prodID"] . "</td>";
            echo "<td>" . $row["prodName"] . "</td>";
            echo "<td>" . $row["prodDesc"] . "</td>";
            echo "<td>" . $row["prodPrice"] . "</td>";
            echo "<td>" . $row["prodAvailability"] . "</td>";
            echo "<td>" . $row["date_added"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "0 results";
    }

    $conn->close();
    ?>
</div>

</body>
</html>
