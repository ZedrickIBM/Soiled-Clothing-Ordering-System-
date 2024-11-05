<?php
include 'userconx.php'; // Include the database connection file

if(isset($_GET['name']) && isset($_GET['type'])) {
    $productName = $_GET['name'];
    $optionType = $_GET['type'];
    
    $sql = "SELECT DISTINCT prod{$optionType} FROM products WHERE prodName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $productName);
    $stmt->execute();
    $result = $stmt->get_result();

    $options = array();
    while ($row = $result->fetch_assoc()) {
        $options[] = $row["prod{$optionType}"];
    }

    echo json_encode($options);
}

$conn->close();
?>
