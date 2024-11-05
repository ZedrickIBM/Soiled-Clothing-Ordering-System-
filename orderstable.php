<?php require('userconx.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

<div class="container">
    <h1 class="mt-5 mb-4">Orders</h1>

    <?php
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $limit = 30; // Number of records per page
    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number

    // Calculate offset for pagination
    $offset = ($page - 1) * $limit;

    $sql = "SELECT o.orderID, o.userID, o.cartID, o.contactNo, o.order_deliveryProvince, o.order_deliveryCity, o.order_deliverBrgy, o.order_MOP, o.order_deliveryStatus, o.orderDate,
            u.fname, u.lname,
            c.prodName, c.prodPrice, c.quantity, c.totalAmount
            FROM orders o
            INNER JOIN users u ON o.userID = u.userID
            INNER JOIN cart c ON o.cartID = c.cartID
            LIMIT $limit OFFSET $offset";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table class='table table-striped table-bordered'>";
        echo "<thead class='thead-dark'>";
        echo "<tr><th>Order ID</th><th>User Name</th><th>Cart ID</th><th>Contact No</th><th>Delivery Province</th><th>Delivery City</th><th>Delivery Barangay</th><th>Mode of Payment</th><th>Delivery Status</th><th>Order Date</th><th>Product Name</th><th>Product Price</th><th>Quantity</th><th>Total Amount</th></tr>";
        echo "</thead>";
        echo "<tbody>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["orderID"] . "</td>";
            echo "<td>" . $row["fname"] . " " . $row["lname"] . "</td>";
            echo "<td>" . $row["cartID"] . "</td>";
            echo "<td>" . $row["contactNo"] . "</td>";
            echo "<td>" . $row["order_deliveryProvince"] . "</td>";
            echo "<td>" . $row["order_deliveryCity"] . "</td>";
            echo "<td>" . $row["order_deliverBrgy"] . "</td>";
            echo "<td>" . $row["order_MOP"] . "</td>";
            echo "<td>" . $row["order_deliveryStatus"] . "</td>";
            echo "<td>" . $row["orderDate"] . "</td>";
            echo "<td>" . $row["prodName"] . "</td>";
            echo "<td>" . $row["prodPrice"] . "</td>";
            echo "<td>" . $row["quantity"] . "</td>";
            echo "<td>" . $row["totalAmount"] . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";

        // Pagination links
        $sql = "SELECT COUNT(*) AS total FROM orders";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $total_records = $row['total'];
        $total_pages = ceil($total_records / $limit);

        echo "<nav aria-label='Page navigation'>";
        echo "<ul class='pagination justify-content-center'>";
        if ($page > 1) {
            echo "<li class='page-item'><a class='page-link' href='?page=".($page - 1)."'>Previous</a></li>";
        }
        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<li class='page-item ".($page == $i ? 'active' : '')."'><a class='page-link' href='?page=".$i."'>".$i."</a></li>";
        }
        if ($page < $total_pages) {
            echo "<li class='page-item'><a class='page-link' href='?page=".($page + 1)."'>Next</a></li>";
        }
        echo "</ul>";
        echo "</nav>";

        // Add PDF conversion button
        echo "<div class='mt-3'>";
        echo "<a href='gen_pdf.php' target='_blank' class='btn btn-primary'>Download PDF</a>";
        echo "</div>";
    } else {
        echo "<p>No orders found</p>";
    }

    $conn->close();
    ?>
</div>
</body>
</html>
