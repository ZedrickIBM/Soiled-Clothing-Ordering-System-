<?php
session_start(); // Start the session to get user ID if stored in session

include 'userconx.php'; // Include the database connection file

if (!isset($_SESSION['userID'])) {
    // Redirect if user is not logged in
    header("Location: loginpage.php");
    exit;
}

$userID = $_SESSION['userID'];

// Retrieve user information
$sqlUser = "SELECT * FROM users WHERE userID = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("i", $userID);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$rowUser = $resultUser->fetch_assoc();

// Combine fName and lName to create the username
$username = $rowUser['fname'] . ' ' . $rowUser['lname'];

// Retrieve products from the cart for the current user
$sqlCart = "SELECT * FROM cart WHERE userID = ?";
$stmtCart = $conn->prepare($sqlCart);
$stmtCart->bind_param("i", $userID);
$stmtCart->execute();
$resultCart = $stmtCart->get_result();

// Calculate subtotal and fetch products from the cart
$subtotal = 0;
$products = array();
while ($rowCart = $resultCart->fetch_assoc()) {
    $subtotal += $rowCart['prodPrice'] * $rowCart['quantity'];
    $products[] = $rowCart;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Soiled</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="homestyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
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
<div class="container2">
    <h1>Checkout</h1>
    <h2>Order Summary</h2>
    <div class="order-summary">
        <p><strong>User Name:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p><strong>Subtotal:</strong> Php<?php echo number_format($subtotal, 2); ?></p>
    </div>
    <h2>Products in Cart</h2>
    <div class="cart-products">
        <?php foreach ($products as $product): ?>
            <div class="product-container1">
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($product['prodName']); ?></h3>
                    <p>Price: Php<?php echo number_format($product['prodPrice'], 2); ?></p>
                    <p>Quantity: <?php echo htmlspecialchars($product['quantity']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <br>
    <form method="post" action="placeorder.php">
        <label for="contactNo">Contact Number:</label><br>
        <input type="text" id="contactNo" name="contactNo" style="width:250px;border-radius:15px" required><br><br>

        <label for="deliveryProvince">Province:</label><br>
        <input type="text" id="deliveryProvince" name="deliveryProvince" style="width:250px;border-radius:15px" required><br><br>

        <label for="deliveryCity">City:</label><br>
        <input type="text" id="deliveryCity" name="deliveryCity" style="width:250px;border-radius:15px" required><br><br>

        <label for="deliveryBrgy">Barangay:</label><br>
        <input type="text" id="deliveryBrgy" name="deliveryBrgy" style="width:250px;border-radius:15px" required><br><br>

        <label>Mode of Payment:</label>
        <p style="margin: 0; font-weight: bold;">Cash on Delivery</p>
        
        <div class="buy-button">
            <button type="submit" style="margin-left:-100px">Place Order</button>
        </div>
    </form>
</div>
</body>
</html>
