<?php
include 'userconx.php'; // Include the database connection file

// Retrieve parameters from the GET request
$productId = $_GET['prodID'];
$size = $_GET['prodSize'];
$color = $_GET['prodColor'];

// Prepare and execute the query to fetch the stock
$stmt = $conn->prepare("SELECT stock FROM products WHERE prodID = ? AND prodSize = ? AND prodColor = ?");
$stmt->bind_param("iss", $productId, $size, $color);
$stmt->execute();
$result = $stmt->get_result();

// Check if the result is not null
if ($result !== null) {
    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Fetch the stock value
        $row = $result->fetch_assoc();
        $stock = $row['stock'];
        // Return the stock value
        echo $stock;
    } else {
        echo "Stock not found";
    }
} else {
    echo "Query execution error: " . $conn->error; // Provide the error message if query execution fails
}

// Close the database connection
$conn->close();
?>
