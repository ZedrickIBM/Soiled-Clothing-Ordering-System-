<?php
session_start(); // Start the session to get user ID if stored in session

include 'userconx.php'; // Include the database connection file

if (!isset($_SESSION['userID'])) {
    // Redirect if user is not logged in
    header("Location: loginpage.php");
    exit;
}

$userID = $_SESSION['userID'];

// Retrieve products from the cart for the current user
$sql = "SELECT cart.*, products.stock FROM cart JOIN products ON cart.prodID = products.prodID WHERE cart.userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

// Initialize subtotal variable
$subtotal = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart | Soiled</title>
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
<div class="container1">
    <h1>My Cart</h1>
    <?php
    if ($result->num_rows > 0) {
        // Display products in the cart
        while ($row = $result->fetch_assoc()) {
            $productTotal = $row['prodPrice'] * $row['quantity'];
            echo '<div class="product-container1">';
            echo '<div class="product-info">';
            echo '<h2>' . htmlspecialchars($row["prodName"]) . '</h2>';
            echo '<p>Price: Php' . number_format($row["prodPrice"], 2) . '</p>';
            
            // Add input field for editing quantity
            echo '<form method="post" action="updatequantity.php" onsubmit="return validateQuantity(' . htmlspecialchars($row["stock"]) . ')">';
            echo '<input type="hidden" name="cartID" value="' . htmlspecialchars($row['cartID']) . '">';
            echo '<label for="quantity">Quantity:</label>';
            echo '<input type="number" id="quantity_' . htmlspecialchars($row['cartID']) . '" name="quantity" value="' . htmlspecialchars($row['quantity']) . '" min="1" max="' . htmlspecialchars($row['stock']) . '" step="1" required>';
            echo '<button type="submit">Update</button>';
            echo '</form>';
            
            // Conditionally display size if it exists
            if (!empty($row["prodSize"])) {
                echo '<p>Size: ' . htmlspecialchars($row["prodSize"]) . '</p>';
            }

            // Conditionally display color if it exists
            if (!empty($row["prodColor"])) {
                echo '<p>Color: ' . htmlspecialchars($row["prodColor"]) . '</p>';
            }

            // Display total for the product
            echo '<p>Total: Php' . number_format($productTotal, 2) . '</p>';

            // Add remove button
            echo '<form method="post" action="removefromcart.php">';
            echo '<input type="hidden" name="cartID" value="' . htmlspecialchars($row['cartID']) . '">';
            echo '<button type="submit">Remove</button>';
            echo '</form>';
            echo '</div>'; // Close .product-info div
            echo '</div>'; // Close .product-container1 div

            // Update subtotal
            $subtotal += $productTotal;
        }

        // Display subtotal
        echo '<p class="subtotal">Subtotal: Php' . number_format($subtotal, 2) . '</p>';
        echo '</div>
        <div class="buy-button">
            <button onclick="buy()">Checkout</button>
        </div>';
    } else {
        echo '<p class="empty-cart-message">Your cart is empty.</p>';
    }
    ?>

<script>
    function validateQuantity(maxStock) {
        var quantityInput = document.querySelector('input[name="quantity"]');
        var enteredQuantity = parseInt(quantityInput.value);

        if (enteredQuantity > maxStock) {
            alert("The quantity you entered exceeds the available stock.");
            return false;
        }
        return true;
    }

    function buy() {
        // Perform buy action, e.g., checkout process
        alert("Buy button clicked. Redirecting to checkout...");
        window.location.href = "checkout.php";
    }
</script>
</body>
</html>
