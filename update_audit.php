<?php
require 'userconx.php';

session_start();

$userID = $_SESSION['userID'];

// Prepare SQL statement
$sql = "INSERT INTO audit_log (userID, action) VALUES (?, 'Update')";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Bind parameters and execute the statement
    $stmt->bind_param("i", $userID); // "i" indicates integer type for userID
    if ($stmt->execute()) {
        // Redirect to login page after logging out and recording the action
        header("Location: loginform.php");
        exit;
    } else {
        echo "Error executing SQL statement: " . $stmt->error;
    }
} else {
    echo "Error preparing SQL statement: " . $conn->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
