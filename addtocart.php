<?php
session_start(); // Start the session to get user ID if stored in session

include 'userconx.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $prodID = intval($_POST['prodID']);
    $prodName = $_POST['prodName'];
    $prodPrice = floatval($_POST['prodPrice']);
    $quantity = intval($_POST['quantity']);
    $prodSize = isset($_POST['prodSize']) ? $_POST['prodSize'] : null;
    $prodColor = isset($_POST['prodColor']) ? $_POST['prodColor'] : null;

    // Calculate total amount
    $totalAmount = $prodPrice * $quantity;

    // Retrieve user ID from session (assuming it's stored there after login)
    $userID = isset($_SESSION['userID']) ? intval($_SESSION['userID']) : null;

    // Check if user is logged in
    if ($userID === null) {
        echo '<script>alert("You must be logged in to add items to the cart."); window.location.href = "loginpage.php";</script>';
        exit;
    }

    // Prepare the SQL query to insert into cart
    $sql = "INSERT INTO cart (prodID, prodName, prodPrice, quantity, totalAmount, userID, prodSize, prodColor) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isdiidss", $prodID, $prodName, $prodPrice, $quantity, $totalAmount, $userID, $prodSize, $prodColor);

    if ($stmt->execute()) {
        // Success: Insert an audit log entry
        $audit_sql = "INSERT INTO audit_log(userID, action) VALUES (?, 'Add to cart')";
        $audit_stmt = $conn->prepare($audit_sql);
        $audit_stmt->bind_param("i", $userID);
        $audit_stmt->execute();
        $audit_stmt->close();

        // Redirect with alert
        echo '<script>alert("Added to cart."); window.location.href = "shoppage.php";</script>';
    } else {
        // Error: Display error message
        echo '<script>alert("Failed to add to cart. Please try again."); window.location.href = "shoppage.php";</script>';
    }

    $stmt->close();
} else {
    echo '<script>alert("Invalid request."); window.location.href = "shoppage.php";</script>';
}

$conn->close();
?>
