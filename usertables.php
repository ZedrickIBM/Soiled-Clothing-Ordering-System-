<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users | Admin</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="adminstyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="sidebar">
    <div class="logo-container">
        <img src="images\classic.png" alt="Logo">
    </div>
    <a href="adminpage.php">Dashboard</a>
    <a href="usertables.php" class="active">Users</a>
    <a href="Aproductadd.php">Products</a>
    <a href="orderspage.php">Orders</a>
    <a href="auditlog.php">Audit Log</a>
    <a href="adminaccount.php">Admin Account</a>
    <div class="logout-container">
        <a href="loginform.php">Log out</a>
    </div>
</div>

<div class="content">
    <h2>Users</h2>
<?php
require 'userconx.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$records_per_page = 10;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($current_page - 1) * $records_per_page;

$sql = "SELECT userID, fname, lname, email, userType, date_created FROM users";
$result = $conn->query($sql);
$total_records = $result->num_rows;
$total_pages = ceil($total_records / $records_per_page);

$sql = "SELECT userID, fname, lname, email, userType, date_created FROM users LIMIT $start_from, $records_per_page";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>User ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>User Type</th><th>Date Created</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["userID"] . "</td>";
        echo "<td>" . $row["fname"] . "</td>";
        echo "<td>" . $row["lname"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["userType"] . "</td>";
        echo "<td>" . $row["date_created"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

$conn->close();
?>
</div>

<div class="pagination">
    <?php if ($current_page > 1): ?>
        <a href="usertables.php?page=<?php echo $current_page - 1; ?>">Previous</a>
    <?php endif; ?>
    
    <span>Page <?php echo $current_page; ?> of <?php echo $total_pages; ?></span>

    <?php if ($current_page < $total_pages): ?>
        <a href="usertables.php?page=<?php echo $current_page + 1; ?>">Next</a>
    <?php endif; ?>
</div>

</body>
</html>
