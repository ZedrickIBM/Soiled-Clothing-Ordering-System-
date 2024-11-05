<?php
session_start(); // Start the session to get user ID if stored in session

include 'userconx.php'; // Include the database connection file

if (!isset($_SESSION['userID'])) {
    // Redirect if user is not logged in
    header("Location: loginpage.php");
    exit;
}

// Check if the cartID is provided and is a valid integer
if (isset($_POST['cartID']) && filter_var($_POST['cartID'], FILTER_VALIDATE_INT)) {
    $cartID = $_POST['cartID'];

    // Prepare SQL statement to delete the product from the cart
    $sql = "DELETE FROM cart WHERE cartID = ? AND userID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cartID, $_SESSION['userID']);

    // Execute the statement
    if ($stmt->execute()) {
        // Insert an audit log entry
        $userID = $_SESSION['userID'];
        $action = 'Removed from cart';
        $audit_sql = "INSERT INTO audit_log(userID, action) VALUES (?, ?)";
        $audit_stmt = $conn->prepare($audit_sql);
        $audit_stmt->bind_param("is", $userID, $action); // Updated to bind both userID and action
        $audit_stmt->execute();
        $audit_stmt->close();

        // Product removed successfully
        header("Location: cartpage.php"); // Redirect back to the cart page
        exit;
    } else {
        // Error occurred while removing the product
        echo "Error: Unable to remove product from cart.";
    }
} else {
    // Invalid cartID provided or missing cartID
    echo "Error: Invalid request.";
}
?>
