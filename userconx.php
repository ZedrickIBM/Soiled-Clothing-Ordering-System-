<?php
$servername = "localhost";
$username = "SoiledUser";
$password = "password";
$dbname = "soiled_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>