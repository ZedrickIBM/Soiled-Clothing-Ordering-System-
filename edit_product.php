<?php
require 'adminconx.php';

$prodID = $_GET['prodID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $productName = $_POST['productName'];
    $productDesc = $_POST['productDesc'];
    $productPrice = $_POST['productPrice'];
    $productAvailability = $_POST['productAvailability'];
    $productType = $_POST['productType'];
    $prodSize = $_POST['prodSize'];
    $prodColor = $_POST['prodColor'];
    $stock = $_POST['stock']; // Retrieve stock from form
    $prodID = $_POST['prodID'];

    // Initialize imagePath variable
    $imagePath = null;

    // Check if a new image is provided
    if ($_FILES['productImage']['error'] == UPLOAD_ERR_OK) {
        $targetDir = __DIR__ . '/products/'; // Absolute path to the products directory
        $targetFile = $targetDir . basename($_FILES['productImage']['name']);

        if (move_uploaded_file($_FILES['productImage']['tmp_name'], $targetFile)) {
            $imagePath = 'products/' . basename($_FILES['productImage']['name']);
        } else {
            // Error handling if image upload fails
            $message = "Sorry, there was an error uploading your file.";
        }
    } else {
        // If no new image is provided, retain the existing image path
        $imagePath = $_POST['existingImage'];
    }

    // Prepare SQL statement to update product in the database
    $sql = "UPDATE products SET prodName=?, prodDesc=?, prodPrice=?, prodAvailability=?, prodImage=?, prodType=?, prodSize=?, prodColor=?, stock=? WHERE prodID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssssssi", $productName, $productDesc, $productPrice, $productAvailability, $imagePath, $productType, $prodSize, $prodColor, $stock, $prodID);

    // Execute SQL statement
    if ($stmt->execute()) {
        $message = "Product updated successfully.";

        // Add JavaScript alert to display the message
        echo '<script>alert("' . $message . '");</script>';

        // Redirect back to the products page after updating the product
        header("Location: Aproductadd.php?message=" . urlencode($message));
        exit();
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect back to the products page after updating the product
    header("Location: Aproductadd.php?message=" . urlencode($message));
    exit();
} else {
    // Fetch product details
    $sql = "SELECT * FROM products WHERE prodID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $prodID);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Close statement
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | Admin</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="adminstyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="content">
    <h2>Edit Product</h2>
    <form method="POST" action="edit_product.php" enctype="multipart/form-data">
        <input type="hidden" name="prodID" value="<?php echo $product['prodID']; ?>">
        <input type="hidden" name="existingImage" value="<?php echo $product['prodImage']; ?>">

        <label for="productName">Product Name:</label>
        <input type="text" id="productName" name="productName" value="<?php echo $product['prodName']; ?>" required><br><br>

        <label for="productDesc">Description:</label>
        <textarea id="productDesc" name="productDesc" required><?php echo $product['prodDesc']; ?></textarea><br><br>

        <label for="productPrice">Price:</label>
        <input type="text" id="productPrice" name="productPrice" pattern="\d+(\.\d{2})?" title="Please enter a valid price format (e.g., 400.00)" value="<?php echo $product['prodPrice']; ?>" required><br><br>

        <label for="productAvailability">Availability:</label>
        <select name="productAvailability" id="productAvailability" required>
            <option value="Available" <?php if ($product['prodAvailability'] == 'Available') echo 'selected'; ?>>Available</option>
            <option value="Unavailable" <?php if ($product['prodAvailability'] == 'Unavailable') echo 'selected'; ?>>Unavailable</option>
        </select><br><br>

        <label for="productType">Product Type:</label>
        <select name="productType" id="productType" required>
            <option value="Shirt" <?php if ($product['prodType'] == 'Shirt') echo 'selected'; ?>>Shirt</option>
            <option value="Pomade" <?php if ($product['prodType'] == 'Pomade') echo 'selected'; ?>>Pomade</option>
            <option value="Tube Mask" <?php if ($product['prodType'] == 'Tube Mask') echo 'selected'; ?>>Tube Mask</option>
            <option value="Sticker Pack" <?php if ($product['prodType'] == 'Sticker Pack') echo 'selected'; ?>>Sticker Pack</option>
            <option value="Handkerchief" <?php if ($product['prodType'] == 'Handkerchief') echo 'selected'; ?>>Handkerchief</option>
        </select><br><br>

        <label for="prodSize">Product Size:</label>
        <input type="text" id="prodSize" name="prodSize" pattern="^(M|L|XL)$" title="Please enter 'M', 'L', or 'XL' for product size" value="<?php echo $product['prodSize']; ?>"><br><br>

        <label for="prodColor">Product Color:</label>
        <input type="text" id="prodColor" name="prodColor" value="<?php echo $product['prodColor']; ?>"><br><br>

        <label for="stock">Stock:</label> <!-- Added input for stock -->
        <input type="number" id="stock" name="stock" min="0" value="<?php echo $product['stock']; ?>" required><br><br>

        <label for="prodImage">Product Image:</label>
        <input type="file" name="productImage" id="productImage"><br><br>
        <?php if ($product['prodImage']): ?>
            <img src="<?php echo $product['prodImage']; ?>" alt="Product Image" style="width: 100px;"><br><br>
        <?php endif; ?>

        <button type="submit">Update Product</button>
    </form>
</div>
</body>
</html>
