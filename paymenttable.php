<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment Details</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }
</style>
</head>
<body>
<?php
require 'userconx.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT p.paymentID, p.orderID, p.paymentDate, p.paymentAmount, p.paymentStatus,
               o.order_deliveryProvince, o.order_deliveryCity, o.order_deliverBrgy
        FROM payment p
        INNER JOIN orders o ON p.orderID = o.orderID";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Payment ID</th><th>Order ID</th><th>Delivery Province</th><th>Delivery City</th><th>Delivery Barangay</th><th>Payment Date</th><th>Payment Amount</th><th>Payment Status</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["paymentID"] . "</td>";
        echo "<td>" . $row["orderID"] . "</td>";
        echo "<td>" . $row["order_deliveryProvince"] . "</td>";
        echo "<td>" . $row["order_deliveryCity"] . "</td>";
        echo "<td>" . $row["order_deliverBrgy"] . "</td>";
        echo "<td>" . $row["paymentDate"] . "</td>";
        echo "<td>" . $row["paymentAmount"] . "</td>";
        echo "<td>" . $row["paymentStatus"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No payment records found";
}

$conn->close();
?>
</body>
</html>
