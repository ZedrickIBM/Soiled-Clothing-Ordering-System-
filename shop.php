<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop at Soiled</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" type="text/css" href="homestyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <img class="logo" src="images/classic.png" alt="logo">
    <nav class="navbar">
        <ul>
            <li><a href="soiled.php">Home</a></li>
            <li><a href="aboutus.html">About</a></li>
            <li><a href="shop.php" style="font-weight: 600;">Shop</a></li>
            <li><div class="logos"><a href="loginform.php">Login</a></div></li>
        </ul>
    </nav>   
</div>
<div class="divider1"></div>
<div class="categories-sorting-container">
    <!-- Product Categories -->
    <div class="product-categories">
        <ul>
            <li><a href="shop.php?category=Shirt">Shirt</a></li>
            <li><a href="shop.php?category=Pomade">Pomade</a></li>
            <li><a href="shop.php?category=Tube%20Mask">Tube Mask</a></li>
            <li><a href="shop.php?category=Sticker%20Pack">Sticker Pack</a></li>
            <li><a href="shop.php?category=Handkerchief">Handkerchief</a></li>
        </ul>
    </div>

    <!-- Sorting Dropdown -->
    <div class="sorting-dropdown">
        <form method="GET" action="shoppage.php">
            <label for="sort">Sort:</label>
            <select name="sort" id="sort" onchange="this.form.submit()">
                <option value="" <?php echo isset($_GET['sort']) && $_GET['sort'] == '' ? 'selected' : ''; ?>>Featured</option>
                <option value="az" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'az' ? 'selected' : ''; ?>>Show A-Z</option>
                <option value="low_price" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'low_price' ? 'selected' : ''; ?>>Show lowest price first</option>
                <option value="high_price" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'high_price' ? 'selected' : ''; ?>>Show highest price first</option>
            </select>
        </form>
    </div>
</div>

<div class="container1">
    <?php
    include 'userconx.php'; // Include the database connection file

    // Initialize sorting and category variables
    $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
    $category = isset($_GET['category']) ? $_GET['category'] : '';

    // Prepare SQL query with sorting and category filters
    $sql = "SELECT prodID, prodName, prodDesc, MIN(prodPrice) as prodPrice, prodImage FROM products";

    // Add filtering for product availability
    $sql .= " WHERE prodAvailability = 'Available'";

    // Filter by category if specified
    if ($category) {
        $sql .= " AND prodType = '$category'";
    }

    // Group by product name to avoid duplicate entries
    $sql .= " GROUP BY prodName";

    // Add sorting based on selected criteria
    switch ($sort) {
        case 'az':
            $sql .= " ORDER BY prodName ASC";
            break;
        case 'low_price':
            $sql .= " ORDER BY prodPrice ASC";
            break;
        case 'high_price':
            $sql .= " ORDER BY prodPrice DESC";
            break;
        default:
            // No sorting specified
            break;
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product-container">';
            echo '<div class="product">';
            echo '<a href="loginform.php">';
            echo '<img class="product-image" src="' . $row["prodImage"] . '" alt="Product Image">';
            echo '<h2>' . $row["prodName"] . '</h2>';
            echo '<p>Php' . number_format($row["prodPrice"], 2) . '</p>';
            echo '</a>';
            echo '</div>'; // Close .product div
            echo '</div>'; // Close .product-container div
        }
    } else {
        echo "No products found.";
    }

    $conn->close();
    ?>
</div>
</body>
</html>
