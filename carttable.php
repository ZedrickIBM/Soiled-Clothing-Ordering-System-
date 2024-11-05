<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cart</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
<style>
    table {
        border-collapse: collapse;
        width: 100%;
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

$sql = "SELECT c.cartID, c.prodID, p.prodName, p.prodPrice, c.quantity, (p.prodPrice * c.quantity) AS totalAmount
        FROM cart c
        INNER JOIN products p ON c.prodID = p.prodID";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table class='cart-table'>";
    echo "<tr><th>Cart ID</th><th>Product ID</th><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total Amount</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["cartID"] . "</td>";
        echo "<td>" . $row["prodID"] . "</td>";
        echo "<td>" . $row["prodName"] . "</td>";
        echo "<td>" . $row["prodPrice"] . "</td>";
        echo "<td>" . $row["quantity"] . "</td>";
        echo "<td>" . $row["totalAmount"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Cart is empty";
}

$conn->close();
?>
</body>
</html>
