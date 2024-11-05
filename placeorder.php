<?php
session_start(); // Start the session to get user ID if stored in session

include 'userconx.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $contactNo = $_POST['contactNo'];
    $deliveryProvince = $_POST['deliveryProvince'];
    $deliveryCity = $_POST['deliveryCity'];
    $deliveryBrgy = $_POST['deliveryBrgy'];
    $modeOfPayment = $_POST['MOP'];

    // Retrieve user ID from session (assuming it's stored there after login)
    $userID = isset($_SESSION['userID']) ? intval($_SESSION['userID']) : null;

    // Check if user is logged in
    if ($userID === null) {
        echo '<script>alert("You must be logged in to place an order."); window.location.href = "loginpage.php";</script>';
        exit;
    }

    // Start a transaction
    $conn->begin_transaction();

    // Retrieve cart items for the user
    $sqlCart = "SELECT c.prodID, c.quantity, p.prodName, p.prodPrice 
                FROM cart c 
                JOIN products p ON c.prodID = p.prodID 
                WHERE c.userID = ?";
    $stmtCart = $conn->prepare($sqlCart);
    $stmtCart->bind_param("i", $userID);
    $stmtCart->execute();
    $resultCart = $stmtCart->get_result();

    // Check if cart is empty
    if ($resultCart->num_rows === 0) {
        echo '<script>alert("Your cart is empty."); window.location.href = "shoppage.php";</script>';
        exit;
    }

    // Insert each cart item into the orders table and update stock
    while ($rowCart = $resultCart->fetch_assoc()) {
        $prodID = $rowCart['prodID'];
        $prodName = $rowCart['prodName'];
        $prodPrice = $rowCart['prodPrice'];
        $quantity = $rowCart['quantity'];
        $totalAmount = $prodPrice * $quantity;

        // Start by retrieving the current stock of the product
        $getCurrentStockSql = "SELECT stock FROM products WHERE prodID = ?";
        $stmtCurrentStock = $conn->prepare($getCurrentStockSql);
        $stmtCurrentStock->bind_param("i", $prodID);
        $stmtCurrentStock->execute();
        $resultCurrentStock = $stmtCurrentStock->get_result();

        if ($resultCurrentStock->num_rows > 0) {
            $rowCurrentStock = $resultCurrentStock->fetch_assoc();
            $currentStock = $rowCurrentStock['stock'];

            // Check if there's enough stock to fulfill the order
            if ($currentStock >= $quantity) {
                // Insert order details into orders table
                $insertOrderSql = "INSERT INTO orders (userID, contactNo, order_deliveryProvince, order_deliveryCity, order_deliverBrgy, order_MOP, prodID, prodName, prodPrice, quantity, totalAmount, order_deliveryStatus) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmtOrder = $conn->prepare($insertOrderSql);
                $stmtOrder->bind_param("isssssisdids", $userID, $contactNo, $deliveryProvince, $deliveryCity, $deliveryBrgy, $modeOfPayment, $prodID, $prodName, $prodPrice, $quantity, $totalAmount, $status);
                
                // Set the status value
                $status = 'Placed order';
                
                // Execute the prepared statement
                $stmtOrder->execute();

                // Update the stock in the products table
                $newStock = $currentStock - $quantity;
                $updateStockSql = "UPDATE products SET stock = ? WHERE prodID = ?";
                $stmtUpdateStock = $conn->prepare($updateStockSql);
                $stmtUpdateStock->bind_param("ii", $newStock, $prodID);
                $stmtUpdateStock->execute();
            } else {
                // Insufficient stock, rollback transaction and redirect with error message
                $conn->rollback();
                echo '<script>alert("Insufficient stock for product: ' . $prodName . '"); window.location.href = "cartpage.php";</script>';
                exit;
            }
        }
    }

    // Commit transaction
    $conn->commit();

    // Clear the user's cart
    $clearCartSql = "DELETE FROM cart WHERE userID = ?";
    $stmtClearCart = $conn->prepare($clearCartSql);
    $stmtClearCart->bind_param("i", $userID);
    $stmtClearCart->execute();

    // Insert audit log for placing an order
    $auditLogSql = "INSERT INTO audit_log(userID, action)
                    VALUES (?, 'Placed an Order')";
    $stmtAuditLog = $conn->prepare($auditLogSql);
    $stmtAuditLog->bind_param("i", $userID);
    $stmtAuditLog->execute();

    // Redirect with success message
    echo '<script>alert("Order placed successfully."); window.location.href = "accountpage.php";</script>';
} else {
    echo '<script>alert("Invalid request."); window.location.href = "shoppage.php";</script>';
}

$conn->close();
?>
