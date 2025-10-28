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

// Database connection
$conn = new mysqli("localhost", "root", "", "cafe_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);
    $user_email = $_SESSION['email'];

    // Verify that the order belongs to the logged-in user and is not already canceled
    $sql_verify = "SELECT o.order_id 
                   FROM orders o 
                   JOIN customers c ON o.customer_id = c.customer_id
                   JOIN booking b ON c.customer_id = b.customer_id
                   WHERE o.order_id = ? AND b.user_email = ? AND o.status <> 'canceled'";
    $stmt_verify = $conn->prepare($sql_verify);
    $stmt_verify->bind_param("is", $order_id, $user_email);
    $stmt_verify->execute();
    $result_verify = $stmt_verify->get_result();

    if ($result_verify->num_rows > 0) {
        // Update the order status to 'canceled'
        $sql_update = "UPDATE orders SET status = 'canceled' WHERE order_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $order_id);

        if ($stmt_update->execute()) {
            $_SESSION['message'] = "Order " . $order_id . " has been successfully canceled.";
        } else {
            $_SESSION['error'] = "Failed to cancel order " . $order_id . ". Please try again.";
        }
        $stmt_update->close();
    } else {
        $_SESSION['error'] = "Unauthorized action or order is already processed/canceled.";
    }
    $stmt_verify->close();
} else {
    $_SESSION['error'] = "Invalid order ID.";
}

$conn->close();

// Redirect back to the view order page
header("Location: vieworder.php");
exit;
?>