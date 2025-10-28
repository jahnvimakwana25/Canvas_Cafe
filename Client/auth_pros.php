<?php
session_start();
header("Content-Type: application/json");

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$conn = new mysqli("localhost", "root", "", "cafe_management");
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$sql = "SELECT firstname, lastname, email, password FROM users_list WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows === 1){
    $stmt->bind_result($firstName, $lastName, $userEmail, $hashedPassword);
    $stmt->fetch();

    if(password_verify($password, $hashedPassword)){
        $_SESSION['email'] = $userEmail;
        $_SESSION['firstname'] = $firstName;
        echo json_encode([
            "first_name" => $firstName,
            "last_name" => $lastName,
            "email" => $userEmail
        ]);
    } else {
        echo json_encode(["error"=>"Invalid credentials"]);
    }
} else {
    echo json_encode(["error"=>"Invalid credentials"]);
}

$stmt->close();
$conn->close();
?>
