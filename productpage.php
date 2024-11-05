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
            <li><a href="homepage.php">Home</a></li>
            <li><a href="aboutpage.php">About</a></li>
            <li><a href="shoppage.php" style="font-weight: 600;">Shop</a></li>
            <li>
                <div class="logos">
                    <a href="cartpage.php"><img src="images/carticon.png" alt="cart-icon"></a>
                    <a href="accountpage.php"><img src="images/profileicon.png" alt="profile-icon"></a>
                </div>
            </li>
        </ul>
    </nav>
</div>
<div class="divider1"></div>
<div class="product-details">
    <?php
    include 'userconx.php'; // Include the database connection file

    // Retrieve product details based on the product ID from the URL parameter
    if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
        $productId = intval($_GET['id']);
        $sql = "SELECT * FROM products WHERE prodID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo '<img src="' . htmlspecialchars($row["prodImage"]) . '" alt="Product Image">';
            echo '<div class="product-info">';
            echo '<h2>' . htmlspecialchars($row["prodName"]) . '</h2>';
            echo '<p>' . htmlspecialchars($row["prodDesc"]) . '</p>';
            echo '<p>Price: Php' . number_format($row["prodPrice"], 2) . '</p>';
            
            // Display stock information
            echo '<p id="stock-info">Stock: ' . htmlspecialchars($row["stock"]) . '</p>';

            // Retrieve all variations of the product
            $variationsSql = "SELECT * FROM products WHERE prodName = ?";
            $variationsStmt = $conn->prepare($variationsSql);
            $variationsStmt->bind_param("s", $row["prodName"]);
            $variationsStmt->execute();
            $variationsResult = $variationsStmt->get_result();

            $variations = [];
            while ($variation = $variationsResult->fetch_assoc()) {
                $variations[] = $variation;
            }

            // Check if there are any sizes and colors
            $sizes = array_filter(array_unique(array_column($variations, 'prodSize')));
            $colors = array_filter(array_unique(array_column($variations, 'prodColor')));

            if (!empty($sizes)) {
                // Output size options
                echo '<label for="size">Size:</label>';
                echo '<select id="size" name="prodSize">';
                foreach ($sizes as $size) {
                    echo '<option value="' . htmlspecialchars($size) . '">' . htmlspecialchars($size) . '</option>';
                }
                echo '</select>';
            }

            if (!empty($colors)) {
                // Output color options
                echo '<label for="color">Color:</label>';
                echo '<select id="color" name="prodColor">';
                foreach ($colors as $color) {
                    echo '<option value="' . htmlspecialchars($color) . '">' . htmlspecialchars($color) . '</option>';
                }
                echo '</select>';
            }

            echo '<form method="post" action="addtocart.php">'; // Form tag start
            echo '<label for="quantity">Quantity:</label>';
            echo '<input type="number" id="quantity" name="quantity" min="1" value="1">';
            
            echo '<input type="hidden" name="prodID" id="prodID" value="' . htmlspecialchars($row['prodID']) . '">';
            echo '<input type="hidden" name="prodName" value="' . htmlspecialchars($row['prodName']) . '">';
            echo '<input type="hidden" name="prodPrice" value="' . htmlspecialchars($row['prodPrice']) . '">';
            echo '<button type="submit">Add to Cart</button>';
            echo '</form>'; // Form tag end
            echo '</div>'; // Close .product-info div

            // Output variations data as a JavaScript variable
            echo '<script>';
            echo 'var variations = ' . json_encode($variations) . ';';
            echo '</script>';
        } else {
            echo "Product not found.";
        }
    }

    $conn->close();
    ?>
</div>

<script>
    // Function to update the stock info based on selected size and color
    function updateStockInfo() {
        var sizeElement = document.getElementById("size");
        var colorElement = document.getElementById("color");
        var selectedSize = sizeElement ? sizeElement.value : null;
        var selectedColor = colorElement ? colorElement.value : null;
        var stockInfo = document.getElementById("stock-info");
        var quantityInput = document.getElementById("quantity");

        // Find the matching variation
        var matchingVariation = variations.find(function(variation) {
            return (variation.prodSize === selectedSize || !selectedSize) && 
                   (variation.prodColor === selectedColor || !selectedColor);
        });

        if (matchingVariation) {
            stockInfo.innerText = 'Stock: ' + matchingVariation.stock;
            quantityInput.setAttribute("max", matchingVariation.stock);
            document.getElementById("prodID").value = matchingVariation.prodID;
        } else {
            stockInfo.innerText = 'Stock: 0';
            quantityInput.setAttribute("max", 0);
            quantityInput.value = 0;
        }
    }

    // Add event listeners to size and color dropdowns if they exist
    var sizeElement = document.getElementById("size");
    var colorElement = document.getElementById("color");
    if (sizeElement) sizeElement.addEventListener("change", updateStockInfo);
    if (colorElement) colorElement.addEventListener("change", updateStockInfo);

    // Initial call to set the stock info based on the default selections
    updateStockInfo();
</script>

<div class="back-to-shop">
    <a href="shoppage.php">Back to Shop</a>
</div>
</body>
</html>
