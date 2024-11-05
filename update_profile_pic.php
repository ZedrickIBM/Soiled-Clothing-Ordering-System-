<?php
require 'userconx.php';
session_start();

$userID = $_SESSION['userID'];

if(isset($_FILES['profile_pic'])) {
    $img_name = $_FILES['profile_pic']['name'];
    $img_size = $_FILES['profile_pic']['size'];
    $tmp_name = $_FILES['profile_pic']['tmp_name'];
    $error = $_FILES['profile_pic']['error'];

    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
    $img_ex_lc = strtolower($img_ex);
    $allowed_exs = array("jpg", "jpeg", "png"); 

    $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
    $img_upload_path = 'Images/'.$new_img_name;

    if (!move_uploaded_file($tmp_name, $img_upload_path)) {
        $_SESSION['error_message'] = "Failed to move uploaded file";
        header("Location: editaccount.php");
        exit;
    }

    $sql = "UPDATE users SET profile_pic='$new_img_name' WHERE userID = '$userID'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['profile_pic_updated'] = true;
        header("Location: accountpage.php"); // Redirect to user profile page after successful upload
        exit;
    } else {
        $_SESSION['error_message'] = "Error: " . $sql . "<br>" . $conn->error;
        header("Location: editaccount.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = "No file uploaded";
    header("Location: editaccount.php");
    exit;
}

$conn->close();
?>
