<?php
require 'userconx.php';
session_start();

function redirect_with_error($error_msg) {
    header("Location: editaccounterror.php?error=" . urlencode($error_msg));
    exit();
}

if (isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['email'])) {
    $userID = $_SESSION['userID'];
    $new_fname = $_POST['fname'];
    $new_lname = $_POST['lname'];
    $new_email = $_POST['email'];

    // Update the user's first name, last name, and email in the database
    $sql = "UPDATE users SET fname=?, lname=?, email=? WHERE userID=?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters and execute the statement
        $stmt->bind_param("sssi", $new_fname, $new_lname, $new_email, $userID);
        if ($stmt->execute()) {
            // Prepare SQL statement for updating the audit log
            $audit_sql = "INSERT INTO audit_log (userID, action) VALUES (?, 'Update Credentials')";
            $audit_stmt = $conn->prepare($audit_sql);

            if ($audit_stmt) {
                // Bind parameters and execute the statement for audit log update
                $audit_stmt->bind_param("i", $userID);
                $audit_stmt->execute();
            }

            echo "<script>alert('User credentials updated successfully'); window.location.href = 'accountpage.php';</script>";
            exit();
        } else {
            redirect_with_error('Error updating credentials: ' . $stmt->error);
        }
    } else {
        redirect_with_error('Error preparing SQL statement: ' . $conn->error);
    }
} else {
    redirect_with_error('Invalid request');
}

$conn->close();
?>
