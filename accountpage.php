<?php
require 'userconx.php';
session_start();

if(isset($_POST['logout'])){
    session_destroy();
    header("Location: loginform.php");
    exit;
}

$userID = $_SESSION['userID'];

$sqlFetch = "SELECT * FROM users WHERE userID = '$userID'";
$result = $conn->query($sqlFetch); 
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account | Soiled</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="accountstyles.css">
    <!-- Add font awesome for camera icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Add CSS for circular profile picture -->
    <style>
        .order-container {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }
        .order-details, .product-details {
            width: 48%;
        }
        #no-orders-message {
            margin-top: 50px;
            margin-left: 20px;
        }
        .filter-buttons button {
            font-family: montserrat;
            border: none;
            color: #231f20;
            font-weight: 600;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }

        .filter-buttons button:hover {
            background-color: #45a049;
        }

        .filter-buttons button:focus {
            outline: none;
        }

        .filter-buttons button:active {
            background-color: #3e8e41;
        }
    </style>
</head>
<body>

<div class="container">
    <img class="logo" src="images/classic.png" alt="logo">
    <nav class="navbar">
        <ul>
            <li><a href="homepage.php">Home</a></li>
            <li><a href="aboutpage.php">About</a></li>
            <li><a href="shoppage.php">Shop</a></li>
            <li>
                <div class="logos">
                    <a href="cartpage.php"><img src="images/carticon.png" alt="cart-icon"></a>
                    <a href="accountpage.php"><img src="images/profileicon.png" alt="profile-icon"></a>
                </div>
            </li>
        </ul>
    </nav>
</div>

<div class="divider1"></div>

<div class="container1">
    <?php if ($result->num_rows > 0) { ?>
        <h1>Account Details</h1>
        <div class="profile-pic-container">
            <img src="Images/<?php echo !empty($row['profile_pic']) ? $row['profile_pic'] : 'default_pic.png'; ?>" alt="Profile Picture" class="profile-pic">
        </div>
        <br>
        <label><b><?php echo $row['fname'] . " " . $row['lname']; ?></b></label>
        <br>
        <label style="font-size: 16px;"><?php echo $row['email']; ?></label>
        <br><br>
        <a href="editaccount.php" class="edit-link">Edit Account Details</a>
        <br>
        <a href="changepassword.php" class="password-link">Change Password</a>
        <br>
        <br>
        <form method="post" action="logoutaudit.php">
            <a href="logoutaudit.php" class="logout-link">Logout</a>
        </form>
        <h2>Order History</h2>
        
        <?php
            $sqlOrders = "SELECT * FROM orders WHERE userID = '$userID'";
            $resultOrders = $conn->query($sqlOrders);

            if ($resultOrders->num_rows > 0) {
                echo '<div class="filter-buttons">
                    <button onclick="filterOrders(\'Placed order\')">Placed Orders <i class="fas fa-shopping-cart"></i></button>
                    <button onclick="filterOrders(\'To be shipped\')">To be shipped <i class="fas fa-truck"></i></button>
                    <button onclick="filterOrders(\'Shipped\')">Shipped <i class="fas fa-shipping-fast"></i></button>
                    <button onclick="filterOrders(\'Delivered successfully\')">Delivered <i class="fas fa-check-circle"></i></button>
                    <br>
                    <button onclick="showAllOrders()">All Orders</button>
                    <button onclick="filterOrders(\'Canceled order\')">Canceled orders</button>
                </div>';

                while ($rowOrder = $resultOrders->fetch_assoc()) {
                    echo "<div class='order-container'>";
                    
                    // Order Details
                    echo "<div class='order-details'>";
                    echo "<h3>Order Details</h3>";
                    echo "<p>Contact No: " . $rowOrder['contactNo'] . "</p>";
                    echo "<p>Address: " . $rowOrder['order_deliverBrgy'] . ", " . $rowOrder['order_deliveryCity'] . ", " . $rowOrder['order_deliveryProvince'] . "</p>";
                    echo "<p>Mode of Payment:  Cash on Delivery </p>";
                    echo "<p>Status: " . $rowOrder['order_deliveryStatus'] . "</p>";
                    echo "<p>Date: " . $rowOrder['orderDate'] . "</p>";
                    if ($rowOrder['order_deliveryStatus'] == 'Delivered successfully') {
                        echo "<p>Delivered Date: " . $rowOrder['deliveredDate'] . "</p>";
                    }
                    echo "</div>";

                    // Product Details
                    echo "<div class='product-details'>";
                    echo "<h3>Product:</h3>";
                    echo "<p>Product Name: " . $rowOrder['prodName'] . "</p>";
                    echo "<p>Product Price: " . $rowOrder['prodPrice'] . "</p>";
                    echo "<p>Quantity: " . $rowOrder['quantity'] . "</p>";
                    echo "<p>Total Amount: " . $rowOrder['totalAmount'] . "</p>";
                    echo "</div>";

                    echo "</div>";
                }
            } else {
                echo "<p>No orders found.</p>";
            }
        ?>

    <?php } ?>
    <div id="no-orders-message" style="display: none;">No orders found.</div>

</div>
<script>
function filterOrders(status) {
    var orders = document.getElementsByClassName
    ('order-container');
    var foundOrders = false;
    for (var i = 0; i < orders.length; i++) {
        var orderStatus = orders[i].getElementsByClassName('order-details')[0].getElementsByTagName('p')[3].innerText.split(": ")[1];
        if (orderStatus === status) {
            orders[i].style.display = 'flex';
            foundOrders = true;
        } else {
            orders[i].style.display = 'none';
        }
    }
    if (!foundOrders) {
        document.getElementById('no-orders-message').style.display = 'block';
    } else {
        document.getElementById('no-orders-message').style.display = 'none';
    }
}

function showAllOrders() {
    var orders = document.getElementsByClassName('order-container');
    for (var i = 0; i < orders.length; i++) {
        orders[i].style.display = 'flex';
    }
    document.getElementById('no-orders-message').style.display = 'none';
}

</script>
</body>
</html>
