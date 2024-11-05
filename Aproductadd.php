<?php
require 'adminconx.php';

$message = isset($_GET['message']) ? $_GET['message'] : '';
$sortType = isset($_GET['sortType']) ? $_GET['sortType'] : '';

// Prepare SQL query with sorting
$sql = "SELECT prodID, prodName, prodDesc, prodPrice, prodAvailability, date_added, prodImage, prodSize, prodColor, prodType, stock FROM products";

// Add sorting condition if a sort type is selected
if ($sortType != '') {
    $sql .= " WHERE prodType = ?";
}

$result = $conn->prepare($sql);

// Bind the sort type parameter if necessary
if ($sortType != '') {
    $result->bind_param("s", $sortType);
}

// Execute the query
$result->execute();
$result_set = $result->get_result();

// Check for SQL query errors
if (!$result_set) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | Admin</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="adminstyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="sidebar">
    <div class="logo-container">
        <img src="images\classic.png" alt="Logo">
    </div>
    <a href="adminpage.php" >Dashboard</a>
    <a href="usertables.php">Users</a>
    <a href="Aproductadd.php" class="active">Products</a>
    <a href="orderspage.php">Orders</a>
    <a href="auditlog.php">Audit Log</a>
    <a href="adminaccount.php">Admin Account</a>
    <div class="logout-container">
        <a href="loginform.php">Log out</a>
    </div>
</div>

<div class="content">
    <h2>Products</h2>

    <?php if (!empty($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <!-- Sort Dropdown -->
    <form method="GET" action="">
        <label for="sortType">Sort by Product Type:</label>
        <select name="sortType" id="sortType" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="Shirt" <?php if ($sortType == 'Shirt') echo 'selected'; ?>>Shirt</option>
            <option value="Pomade" <?php if ($sortType == 'Pomade') echo 'selected'; ?>>Pomade</option>
            <option value="Tube Mask" <?php if ($sortType == 'Tube Mask') echo 'selected'; ?>>Tube Mask</option>
            <option value="Sticker Pack" <?php if ($sortType == 'Sticker Pack') echo 'selected'; ?>>Sticker Pack</option>
            <option value="Handkerchief" <?php if ($sortType == 'Handkerchief') echo 'selected'; ?>>Handkerchief</option>
        </select>
        
    </form>
    <br>
    <!-- Product Table -->
    <?php
    if ($result_set->num_rows > 0) {
        echo "<div class='table-container'>";
        echo "<table>";
        echo "<tr><th>Product ID</th><th>Product Name</th><th>Description</th><th>Price</th><th>Availability</th><th>Date Added</th><th>Image</th><th>Size</th><th>Color</th><th>Type</th><th>Stock</th><th>Action</th></tr>";
        while($row = $result_set->fetch_assoc()) {
            $imagePath = $row["prodImage"];
            echo "<tr>";
            echo "<td>" . $row["prodID"] . "</td>";
            echo "<td>" . $row["prodName"] . "</td>";
            echo "<td>" . $row["prodDesc"] . "</td>";
            echo "<td>" . $row["prodPrice"] . "</td>";
            echo "<td>" . $row["prodAvailability"] . "</td>";
            echo "<td>" . $row["date_added"] . "</td>";
            echo "<td>";
            if ($imagePath) {
                echo "<img src='$imagePath' alt='Product Image' style='width: 100px;'>";
            } else {
                echo "No image";
            }
            echo "</td>";
            echo "<td>" . $row["prodSize"] . "</td>";
            echo "<td>" . $row["prodColor"] . "</td>";
            echo "<td>" . $row["prodType"] . "</td>";
            echo "<td>" . $row["stock"] . "</td>";
            echo "<td>";
            echo "<a href='edit_product.php?prodID=" . $row["prodID"] . "'><button>Edit</button></a>";
            echo "<a href='delete_product.php?prodID=" . $row["prodID"] . "' onclick='return confirm(\"Are you sure you want to delete this product?\")'><button>Delete</button></a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "0 results";
    }
    ?>

    <!-- Add Product Section -->
    <h2>Add Product</h2>
    <form method="POST" action="add_product.php" enctype="multipart/form-data">
        <label for="productName">Product Name:</label>
        <input type="text" id="productName" name="productName" required><br><br>

        <label for="productDesc">Description:</label>
        <textarea id="productDesc" name="productDesc" required></textarea><br><br>

        <label for="productPrice">Price:</label>
        <input type="text" id="productPrice" name="productPrice" pattern="\d+(\.\d{2})?" title="Please enter a valid price format (e.g., 400.00)" required><br><br>

        <label for="productAvailability">Availability:</label>
        <select name="productAvailability" id="productAvailability" required>
            <option value="Available">Available</option>
            <option value="Unavailable">Unavailable</option>
        </select><br><br>

        <label for="productType">Product Type:</label>
        <select name="productType" id="productType" required>
            <option value="Shirt">Shirt</option>
            <option value="Pomade">Pomade</option>
            <option value="Tube Mask">Tube Mask</option>
            <option value="Sticker Pack">Sticker Pack</option>
            <option value="Handkerchief">Handkerchief</option>
        </select><br><br>

        <label for="prodSize">Product Size:</label>
        <input type="text" id="prodSize" name="prodSize" pattern="^(M|L|XL)?$" title="Please enter 'M', 'L', or 'XL' for product size"><br><br>

        <label for="prodColor">Product Color:</label>
        <input type="text" id="prodColor" name="prodColor"><br><br>

        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" min="0" required><br><br>

        <label for="productImage">Product Image:</label>
        <input type="file" name="productImage" id="productImage"><br><br>

        <button type="submit">Add Product</button>
    </form>
</div>
</body>
</html>
