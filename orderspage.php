<?php
require 'adminconx.php';

$message = isset($_GET['message']) ? $_GET['message'] : '';
$sortType = isset($_GET['sortType']) ? $_GET['sortType'] : '';

// Prepare SQL query with sorting
$sql = "SELECT * FROM orders";

// Add sorting condition if a sort type is selected
if ($sortType != '') {
    $sql .= " WHERE order_deliveryStatus = ?";
}

// Order the results by delivery status, with 'Pending' orders first
$sql .= " ORDER BY CASE WHEN order_deliveryStatus = 'Pending' THEN 0 ELSE 1 END, order_deliveryStatus";

$result = $conn->prepare($sql);

// Bind the sort type parameter if necessary
if ($sortType != '') {
    $result->bind_param("s", $sortType);
}

// Execute the query
$result->execute();
$result_set = $result->get_result();

// Check for SQL query errors
if (!$result_set) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | Admin</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="adminstyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="sidebar">
    <div class="logo-container">
        <img src="images\classic.png" alt="Logo">
    </div>
    <a href="adminpage.php">Dashboard</a>
    <a href="usertables.php">Users</a>
    <a href="Aproductadd.php">Products</a>
    <a href="orderspage.php" class="active">Orders</a>
    <a href="auditlog.php">Audit Log</a>
    <a href="adminaccount.php">Admin Account</a>
    <div class="logout-container">
        <a href="loginform.php">Log out</a>
    </div>
</div>

<div class="content">
    <h2>Orders</h2>

    <!-- Sort Dropdown -->
    <form method="GET" action="">
        <label for="sortType">Sort by Delivery Status:</label>
        <select name="sortType" id="sortType" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="Shipped" <?php if ($sortType == 'Shipped') echo 'selected'; ?>>Shipped</option>
            <option value="To be shipped" <?php if ($sortType == 'To be shipped') echo 'selected'; ?>>To be shipped</option>
            <option value="Delivered successfully" <?php if ($sortType == 'Delivered successfully') echo 'selected'; ?>>Delivered successfully</option>
            <option value="Canceled order" <?php if ($sortType == 'Canceled order') echo 'selected'; ?>>Canceled order</option>
            <option value="Placed order" <?php if ($sortType == 'Placed order') echo 'selected'; ?>>Placed order</option>
        </select>
    </form>
    <br>
    <!-- Orders Table -->
<?php
if ($result_set->num_rows > 0) {
    echo "<div class='table-container'>";
    echo "<table>";
    echo "<tr><th>Order ID</th><th>User ID</th><th>Contact No</th><th>Address</th><th>Mode of Payment</th><th>Status</th><th>Date</th><th>Delivered Date</th><th>Product Name</th><th>Product Price</th><th>Quantity</th><th>Total Amount</th><th>Update Status</th></tr>";
    while($row = $result_set->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["orderID"] . "</td>";
        echo "<td>" . $row["userID"] . "</td>";
        echo "<td>" . $row["contactNo"] . "</td>";
        echo "<td>" . $row["order_deliveryProvince"] . ", " . $row["order_deliveryCity"] . ", " . $row["order_deliverBrgy"] . "</td>";
        echo "<td>" . $row["order_MOP"] . "</td>";
        echo "<td>" . $row["order_deliveryStatus"] . "</td>";
        echo "<td>" . $row["orderDate"] . "</td>";
        echo "<td>" . $row["deliveredDate"] . "</td>"; // Display deliveredDate column
        echo "<td>" . $row["prodName"] . "</td>";
        echo "<td>" . $row["prodPrice"] . "</td>";
        echo "<td>" . $row["quantity"] . "</td>";
        echo "<td>" . $row["totalAmount"] . "</td>";
        echo "<td>";
        echo "<form method='POST' action=''>";
        echo "<input type='hidden' name='orderID' value='" . $row["orderID"] . "'>";
        echo "<select name='orderStatus'>";
        echo "<option value='Shipped'" . ($row["order_deliveryStatus"] == 'Shipped' ? ' selected' : '') . ">Shipped</option>";
        echo "<option value='To be shipped'" . ($row["order_deliveryStatus"] == 'To be shipped' ? ' selected' : '') . ">To be shipped</option>";
        echo "<option value='Delivered successfully'" . ($row["order_deliveryStatus"] == 'Delivered successfully' ? ' selected' : '') . ">Delivered successfully</option>";
        echo "<option value='Canceled order'" . ($row["order_deliveryStatus"] == 'Canceled order' ? ' selected' : '') . ">Canceled order</option>";
        echo "<option value='Placed order'" . ($row["order_deliveryStatus"] == 'Placed order' ? ' selected' : '') . ">Placed order</option>";
        echo "</select>";
        echo "<button type='submit' name='updateStatus'>Update</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
} else {
    echo "0 results";
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateStatus'])) {
    $orderID = $_POST['orderID'];
    $orderStatus = $_POST['orderStatus'];

    // Check if the status is being changed to 'Delivered successfully'
    if ($orderStatus == 'Delivered successfully') {
        // Update deliveredDate to current timestamp
        $updateSql = "UPDATE orders SET order_deliveryStatus = ?, deliveredDate = CURRENT_TIMESTAMP WHERE orderID = ?";
    } else {
        // If not updating to 'Delivered successfully', update only the status
        $updateSql = "UPDATE orders SET order_deliveryStatus  = ? WHERE orderID = ?";
    }

    // Prepare and execute the update query
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("si", $orderStatus, $orderID);
    if ($stmt->execute()) {
        echo '<script>alert("Order status updated successfully."); window.location.href = "orderspage.php";</script>';
    } else {
        echo '<script>alert("Failed to update order status.");</script>';
    }
}
?>

</body>
</html>

