<?php
require 'adminconx.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $productName = $_POST['productName'];
    $productDesc = $_POST['productDesc'];
    $productPrice = $_POST['productPrice'];
    $productAvailability = $_POST['productAvailability'];
    $productType = $_POST['productType'];
    $prodSize = $_POST['prodSize']; // Add this line to retrieve prodSize
    $prodColor = $_POST['prodColor']; // Add this line to retrieve prodColor

    // Initialize imagePath variable
    $imagePath = null;

    // Validate and process image upload (if provided)
    if ($_FILES['productImage']['error'] == UPLOAD_ERR_OK) {
        $targetDir = __DIR__ . '/products/'; // Absolute path to the products directory

        // Ensure the products directory exists
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $targetFile = $targetDir . basename($_FILES['productImage']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if file is an actual image
        $check = getimagesize($_FILES['productImage']['tmp_name']);
        if ($check !== false) {
            // Allow only certain file types (e.g., jpeg, jpg, png)
            if ($imageFileType === 'jpg' || $imageFileType === 'jpeg' || $imageFileType === 'png') {
                if (move_uploaded_file($_FILES['productImage']['tmp_name'], $targetFile)) {
                    $imagePath = 'products/' . basename($_FILES['productImage']['name']);
                } else {
                    $message = "Sorry, there was an error uploading your file.";
                }
            } else {
                $message = "Sorry, only JPG, JPEG, and PNG files are allowed.";
            }
        } else {
            $message = "File is not an image.";
        }
    } else {
        // Handle other cases of upload errors
        if ($_FILES['productImage']['error'] !== UPLOAD_ERR_NO_FILE) {
            $message = "Error uploading file: " . $_FILES['productImage']['error'];
        }
    }

    // Check if imagePath is still null, indicating no image was uploaded
    if ($imagePath === null) {
        $message = "Please select an image to upload.";
    }

    // Proceed to insert data into the database if no errors occurred
    if (!isset($message)) {
        // Prepare SQL statement to insert product into database
        $sql = "INSERT INTO products (prodName, prodDesc, prodPrice, prodAvailability, prodImage, prodSize, prodColor, prodType, date_added) VALUES (?, ?, ?, ?, ?, ?, ?, ?, current_timestamp())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsssss", $productName, $productDesc, $productPrice, $productAvailability, $imagePath, $prodSize, $prodColor, $productType); // Update binding parameters

        // Execute SQL statement
        if ($stmt->execute()) {
            $message = "Product added successfully.";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close statement and connection
        $stmt->close();
    }

    // Close connection
    $conn->close();
} else {
    // Redirect to the products page if accessed directly without form submission
    header("Location: Aproductadd.php");
    exit();
}

// Redirect back to the products page after adding the product
header("Location: Aproductadd.php?message=" . urlencode($message));
exit();
?>
