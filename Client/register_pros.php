<?php

// Initialize error message variable
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $c_password =  $_POST["c_password"];

    // Check if any field is empty
    if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($c_password)) {
            header("Location: register.php?error=All field is required");
        exit();
    } else {

        // Datebase Connection
        $conn = new mysqli("localhost", "root", "", "cafe_management");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert user data into the database
        $sql = "INSERT INTO users_list (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
                header("Location: auth.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
}
?>
