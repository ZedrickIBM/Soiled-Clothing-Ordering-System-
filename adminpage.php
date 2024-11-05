<?php
require 'adminconx.php';

session_start();
$id = $_SESSION['userID'];

// Query to get the count of users created per day
$sqlUsers = "SELECT DATE(date_created) AS creation_date, COUNT(*) AS user_count FROM users GROUP BY DATE(date_created)";
$resultUsers = $conn->query($sqlUsers);

// Prepare data for the user registration chart
$userLabels = [];
$userData = [];

while ($row = $resultUsers->fetch_assoc()) {
    $userLabels[] = $row['creation_date'];
    $userData[] = $row['user_count'];
}

// Query to get the count of orders per delivery status
$sqlOrders = "SELECT order_deliveryStatus, COUNT(*) AS status_count FROM orders GROUP BY order_deliveryStatus";
$resultOrders = $conn->query($sqlOrders);

// Prepare data for the orders chart
$orderLabels = [];
$orderData = [];

while ($row = $resultOrders->fetch_assoc()) {
    $orderLabels[] = $row['order_deliveryStatus'];
    $orderData[] = $row['status_count'];
}

// Query to get the count of products per prodType
$sqlProducts = "SELECT prodType, COUNT(*) AS product_count FROM products GROUP BY prodType";
$resultProducts = $conn->query($sqlProducts);

// Prepare data for the product type chart
$productLabels = [];
$productData = [];

while ($row = $resultProducts->fetch_assoc()) {
    $productLabels[] = $row['prodType'];
    $productData[] = $row['product_count'];
}

// Query to get the sales report data
$sqlSalesReport = "SELECT prodName, SUM(totalAmount) AS totalAmount, SUM(quantity) AS totalQuantity, deliveredDate FROM orders WHERE order_deliveryStatus = 'Delivered successfully' GROUP BY prodName, deliveredDate";
$resultSalesReport = $conn->query($sqlSalesReport);

// Prepare data for the sales report chart
$salesLabels = [];
$salesDataAmount = [];
$salesDataQuantity = [];

while ($row = $resultSalesReport->fetch_assoc()) {
    $salesLabels[] = $row['prodName'] . ' (' . $row['deliveredDate'] . ')';
    $salesDataAmount[] = $row['totalAmount'];
    $salesDataQuantity[] = $row['totalQuantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Soiled</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="adminstyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="sidebar">
    <div class="logo-container">
        <img src="images/classic.png" alt="Logo">
    </div>
    <a href="adminpage.php" class="active">Dashboard</a>
    <a href="usertables.php">Users</a>
    <a href="Aproductadd.php">Products</a>
    <a href="orderspage.php">Orders</a>
    <a href="auditlog.php">Audit Log</a>
    <a href="adminaccount.php">Admin Account</a>
    <div class="logout-container">
        <a href="loginform.php">Log out</a>
    </div>
</div>

<div class="content">
    <h2>Dashboard</h2>
    <!-- Dropdown list to select graph -->
    <select id="chartSelector" onchange="showChart()">
        <option value="users">User Registrations</option>
        <option value="orders">Order Delivery Statuses</option>
        <option value="products">Product Types</option>
        <option value="sales">Sales Report</option>
    </select>
    <!-- Container for chart -->
    <div id="chartContainer">
        <!-- Initial display is user registration chart -->
        <canvas id="userChart" width="400" height="200"></canvas>
    </div>
</div>

<script>
    function showChart() {
        var chartSelector = document.getElementById("chartSelector");
        var selectedChart = chartSelector.value;
        var chartContainer = document.getElementById("chartContainer");

        // Clear existing chart
        chartContainer.innerHTML = "";

        // Show selected chart
        if (selectedChart === "users") {
            // User registration chart
            chartContainer.innerHTML = '<canvas id="userChart" width="400" height="200"></canvas>';
            var ctxUsers = document.getElementById('userChart').getContext('2d');
            var userChart = new Chart(ctxUsers, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($userLabels); ?>,
                    datasets: [{
                        label: 'User Registrations per Day',
                        data: <?php echo json_encode($userData); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        } else if (selectedChart === "orders") {
            // Order delivery status chart
            chartContainer.innerHTML = '<canvas id="orderChart" width="400" height="200"></canvas>';
            var ctxOrders = document.getElementById('orderChart').getContext('2d');
            var orderChart = new Chart(ctxOrders, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($orderLabels); ?>,
                    datasets: [{
                        label: 'Order Delivery Statuses',
                        data: <?php echo json_encode($orderData); ?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        } else if (selectedChart === "products") {
            // Product type chart
            chartContainer.innerHTML = '<canvas id="productChart" width="400" height="200"></canvas>';
            var ctxProducts = document.getElementById('productChart').getContext('2d');
            var productChart = new Chart(ctxProducts, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($productLabels); ?>,
                    datasets: [{
                        label: 'Product Types',
                        data: <?php echo json_encode($productData); ?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        } else if (selectedChart === "sales") {
            // Sales report chart
            chartContainer.innerHTML = '<canvas id="salesChart" width="400" height="200"></canvas>';
            var ctxSales = document.getElementById('salesChart').getContext('2d');
            var salesChart = new Chart(ctxSales, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($salesLabels); ?>,
                    datasets: [{
                        label: 'Total Amount',
                        data: <?php echo json_encode($salesDataAmount); ?>,
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Total Quantity',
                        data: <?php echo json_encode($salesDataQuantity); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }
    }

    // Initially show user registration chart
    showChart();
</script>


</body>
</html>
