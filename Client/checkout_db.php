<?php
// Datebase Connection
$conn = new mysqli("localhost", "root", "", "cafe_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the AJAX request
    $Email = $_POST['Email'];
    $Payment_Id = $_POST['Payment_Id'];
    $Table_no = $_POST['Table_no'];
    $Total_Ammount = $_POST['Total_Ammount'];
    $User_Name = $_POST['User_Name'];
    $Date = $_POST['Date'];

    // Insert data into the payment table
    $insertCustomerSql = "INSERT INTO payments (user_name, user_email, table_name, payment_date, order_id, total_amount, payment_status) VALUES (?, ?, ?, ?, ?, ?, 'Paid')";
    $stmt = $conn->prepare($insertCustomerSql);
    
    // Adjust the data types in bind_param and pass values in the correct order
    $stmt->bind_param("sssidsd", $User_Name, $Email, $Table_no, $Date, $Payment_Id, $Total_Ammount, $status);

    if ($stmt->execute()) {
        // Order item inserted successfully
        // Now, update the table management information
        $updateTableSql = "UPDATE table_status SET check_in_time = NULL, check_in_date = NULL, status = 'available' WHERE table_id = (SELECT id FROM table_entry WHERE table_name = ?)";
        $stmt2 = $conn->prepare($updateTableSql);
        $stmt2->bind_param("s", $Table_no);

        if ($stmt2->execute()) {
        } else {
            // Handle the case where the update fails
            echo json_encode(array("success" => false, "error" => "Error updating table information: " . $conn->error));
        }

        $response = array("success" => true);
    } else {
        // Handle the case where the order item insertion fails
        echo json_encode(array("success" => false, "error" => "Error inserting order item: " . $conn->error));
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    echo "Invalid request method.";
}
?>