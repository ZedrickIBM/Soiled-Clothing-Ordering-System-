<?php
// Include the database connection file
include 'userconx.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the product ID, size, and color from the POST data
    $productId = $_POST['productId'];
    $size = $_POST['size'];
    $color = $_POST['color'];

    // Update the stock in the database based on the selected product ID, size, and color
    $sql = "UPDATE products SET stock = stock - 1 WHERE prodID = ? AND prodSize = ? AND prodColor = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $productId, $size, $color);

    // Execute the update query
    if ($stmt->execute()) {
        // Retrieve the updated stock quantity for display
        $updatedStock = "SELECT stock FROM products WHERE prodID = ? AND prodSize = ? AND prodColor = ?";
        $stmt2 = $conn->prepare($updatedStock);
        $stmt2->bind_param("iss", $productId, $size, $color);
        $stmt2->execute();
        $result = $stmt2->get_result();

        // Check if the query returned a row
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo $row['stock']; // Send the updated stock quantity as the response
        } else {
            echo "Error: Product not found.";
        }
    } else {
        echo "Error updating stock.";
    }

    // Close the prepared statements
    $stmt->close();
    $stmt2->close();

    // Close the database connection
    $conn->close();
}
?>
