<?php
session_start();
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$user_email = $_SESSION['email'];
$conn = new mysqli("localhost", "root", "", "cafe_management");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if (empty($data['items'])) {
    echo json_encode(['success' => false, 'message' => 'No items provided in the request.']);
    $conn->close();
    exit;
}

$conn->begin_transaction();

try {
    // 1. Find customer_id (via booking + email)
    $sql_customer = "SELECT c.customer_id 
                     FROM customers c 
                     JOIN booking b ON b.customer_id = c.customer_id 
                     WHERE b.user_email = ? 
                     ORDER BY b.booking_id DESC LIMIT 1";
    $stmt_customer = $conn->prepare($sql_customer);
    $stmt_customer->bind_param("s", $user_email);
    $stmt_customer->execute();
    $result_customer = $stmt_customer->get_result();
    $customer = $result_customer->fetch_assoc();
    $stmt_customer->close();

    $customer_id = null;
    if ($customer) {
        $customer_id = $customer['customer_id'];
    } else {
        // Create new customer
        $sql_create_customer = "INSERT INTO customers (name, table_name, child, adult) VALUES (?, '', 0, 0)";
        $stmt_create_customer = $conn->prepare($sql_create_customer);
        $stmt_create_customer->bind_param("s", $user_email);
        $stmt_create_customer->execute();
        $customer_id = $conn->insert_id;
        $stmt_create_customer->close();

        // Also create a booking linked to this customer
        $sql_create_booking = "INSERT INTO booking (user_name, user_email, booking_date, booking_time, no_of_persons, table_id, status, customer_id) 
                               VALUES (?, ?, CURDATE(), CURTIME(), 1, 1, 'Confirmed', ?)";
        $stmt_create_booking = $conn->prepare($sql_create_booking);
        $stmt_create_booking->bind_param("ssi", $user_email, $user_email, $customer_id);
        $stmt_create_booking->execute();
        $stmt_create_booking->close();
    }

    // 2. Create new order
    $sql_create_order = "INSERT INTO orders (customer_id, amount, checkin_date, checkin_time) 
                         VALUES (?, 0, CURDATE(), CURTIME())";
    $stmt_new_order = $conn->prepare($sql_create_order);
    $stmt_new_order->bind_param("i", $customer_id);
    $stmt_new_order->execute();
    $order_id = $conn->insert_id;
    $stmt_new_order->close();

    $total_to_add = 0;

    // 3. Insert order items
    $sql_item = "INSERT INTO order_items (order_id, item_id, item_name, item_price, quantity) 
                 VALUES (?, ?, ?, ?, ?)";
    $stmt_item = $conn->prepare($sql_item);

    foreach ($data['items'] as $item) {
        $itemId = isset($item['id']) ? intval($item['id']) : 0;
        $name = $item['name'] ?? '';
        $price = isset($item['price']) ? floatval($item['price']) : 0;
        $qty = isset($item['qty']) ? intval($item['qty']) : 1;

        if ($itemId <= 0 || empty($name) || $price <= 0 || $qty <= 0) continue;

        $stmt_item->bind_param("iisdi", $order_id, $itemId, $name, $price, $qty);
        $stmt_item->execute();
        $total_to_add += $price * $qty;
    }
    $stmt_item->close();

    // 4. Update the order's total
    $sql_update = "UPDATE orders SET amount = ? WHERE order_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("di", $total_to_add, $order_id);
    $stmt_update->execute();
    $stmt_update->close();

    $conn->commit();

    echo json_encode([
        'success' => true,
        'order_id' => $order_id,
        'total_amount' => $total_to_add,
        'message' => 'Order placed successfully. Total: ₹' . number_format($total_to_add, 2)
    ]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
