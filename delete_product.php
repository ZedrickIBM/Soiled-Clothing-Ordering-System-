<?php
require 'adminconx.php';

if (isset($_GET['prodID'])) {
    $prodID = $_GET['prodID'];

    // Prepare the SQL statement to delete the product
    $sql = "DELETE FROM products WHERE prodID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $prodID);

    // Execute the SQL statement
    if ($stmt->execute()) {
        $message = "Product deleted successfully.";
    } else {
        $message = "Error deleting product: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Redirect back to the products page with a message
    header("Location: Aproductadd.php?message=" . urlencode($message));
    exit();
} else {
    // Redirect back if prodID is not set
    header("Location: Aproductadd.php?message=" . urlencode("Invalid product ID."));
    exit();
}
?>
