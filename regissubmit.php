<?php
require "adminconx.php";

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$password = $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$userType = 2; // Automatically set userType to 2 for customers

// Check if the email already exists
$emailCheckSql = "SELECT * FROM users WHERE email = ?";
$emailCheckStmt = $conn->prepare($emailCheckSql);
$emailCheckStmt->bind_param("s", $email);
$emailCheckStmt->execute();
$emailCheckResult = $emailCheckStmt->get_result();

if ($emailCheckResult->num_rows > 0) {
    // Email already exists
    header("Location: registerform.php?error=Email already registered. Please use a different email.");
    exit();
}

// Start transaction
$conn->begin_transaction();

try {
    $sql = "INSERT INTO users (fname, lname, email, password, userType) VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $fname, $lname, $email, $hashed_password, $userType);

    if ($stmt->execute() === TRUE) {
        // Get the ID of the newly inserted user
        $userID = $conn->insert_id;

        // Insert audit log
        $audit_sql = "INSERT INTO audit_log (userID, action) VALUES (?, 'User Registered')";
        $audit_stmt = $conn->prepare($audit_sql);
        $audit_stmt->bind_param("i", $userID);

        if ($audit_stmt->execute() === TRUE) {
            // Commit transaction
            $conn->commit();
            echo '<script>alert("Successfully Registered"); window.location.href = "loginform.php";</script>';
            exit();
        } else {
            throw new Exception("Error inserting audit log: " . $conn->error);
        }
    } else {
        throw new Exception("Error inserting user: " . $conn->error);
    }
} catch (Exception $e) {
    // Rollback transaction
    $conn->rollback();
    header("Location: registerform.php?error=" . urlencode($e->getMessage()));
    exit();
}

$conn->close();
?>
