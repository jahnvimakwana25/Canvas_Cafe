<?php
session_start(); 
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['email'])) {
    header("Location: auth.php");
    exit;
}
$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : null;
$email = $_POST['search_email'];
$newPassword = password_hash($_POST['change_password'], PASSWORD_DEFAULT);

// Database Connection
$conn = new mysqli("localhost", "root", "", "cafe_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update the user's password
$sql = "UPDATE users_list SET password = ? WHERE email = ?";
$check_availability_stmt = $conn->prepare($sql);
if (!$check_availability_stmt) {
    die("Prepare failed: " . $conn->error);
}

$check_availability_stmt->bind_param("ss", $newPassword, $email);
$check_availability_stmt->execute();

if ($check_availability_stmt->affected_rows > 0) {
    // Password updated successfully
    echo "Password updated successfully!";

   
} else {
    // Password update failed
    echo "Password update failed. Please try again later.";
}

$check_availability_stmt->close();
$conn->close();

?>
