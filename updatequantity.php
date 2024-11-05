<?php
session_start();

include 'userconx.php'; // Include the database connection file

if (!isset($_SESSION['userID'])) {
    // Redirect if user is not logged in
    header("Location: loginpage.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cartID = intval($_POST['cartID']);
    $quantity = intval($_POST['quantity']);

    // Retrieve the current stock for the product in the cart
    $sql = "SELECT products.stock, cart.prodID FROM cart JOIN products ON cart.prodID = products.prodID WHERE cart.cartID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cartID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stock = intval($row['stock']);

        // Validate the requested quantity against the available stock
        if ($quantity > $stock) {
            $_SESSION['error'] = "The quantity you entered exceeds the available stock.";
            header("Location: cartpage.php");
            exit;
        } else {
            // Update the quantity in the cart
            $updateSql = "UPDATE cart SET quantity = ? WHERE cartID = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("ii", $quantity, $cartID);
            $updateStmt->execute();

            header("Location: cartpage.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Invalid cart item.";
        header("Location: cartpage.php");
        exit;
    }
} else {
    header("Location: cartpage.php");
    exit;
}
?>
