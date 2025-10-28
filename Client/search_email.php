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

// Database Connection
$conn = new mysqli("localhost", "root", "", "cafe_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verify the user credentials
$sql = "SELECT id, email FROM users_list WHERE email=?";
$check_availability_stmt = $conn->prepare($sql);
if (!$check_availability_stmt) {
    die("Prepare failed: " . $conn->error);
}

$check_availability_stmt->bind_param("s", $email);
$check_availability_stmt->execute();
$check_availability_stmt->store_result();

if ($check_availability_stmt->num_rows > 0) {
    // User exists
    $check_availability_stmt->bind_result($userId, $userEmail);
    $check_availability_stmt->fetch();
    
    $user_data = array(
        "id" => $userId,
        "email" => $userEmail
    );
    echo json_encode($user_data);
} else {
    // User doesn't exist
    echo json_encode(array("error" => "User not found"));
}

$check_availability_stmt->close();
$conn->close();
?>
