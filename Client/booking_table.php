<?php
session_start();
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['email'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "cafe_management");
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

// Get POST values
$c_name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$table_no = intval($_POST['table_no'] ?? 0);
$date = $_POST['booked_date'] ?? '';
$time = $_POST['booked_time'] ?? '';

$adult = intval($_POST['adult'] ?? 0);
$child = intval($_POST['child'] ?? 0);
$total_members = $adult + $child;
$status = "Booked";

if (!$c_name || !$email || $table_no <= 0 || !$date || !$time || $total_members <= 0) {
    echo json_encode(["error" => "Missing or invalid required fields"]);
    exit();
}

$conn->begin_transaction();

try {
    // ✅ Step 1: Check table capacity
    $stmt_capacity = $conn->prepare("SELECT capacity FROM table_entry WHERE id = ? AND IsActive = 1");
    $stmt_capacity->bind_param("i", $table_no);
    $stmt_capacity->execute();
    $result_capacity = $stmt_capacity->get_result();
    $table_data = $result_capacity->fetch_assoc();
    $stmt_capacity->close();

    if (!$table_data) {
        throw new Exception("Selected table not found or inactive.");
    }

    $capacity = intval($table_data['capacity']);
    if ($total_members > $capacity) {
        throw new Exception("Selected table cannot accommodate $total_members people (capacity: $capacity).");
    }

    // ✅ Step 2: Insert or get customer
    $table_name_for_customer = "Table_" . $table_no;
    $stmt_check = $conn->prepare("SELECT customer_id FROM customers WHERE name = ? AND table_name = ?");
    $stmt_check->bind_param("ss", $c_name, $table_name_for_customer);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $customer_row = $result_check->fetch_assoc();
        $customer_id = $customer_row['customer_id'];
    } else {
        $stmt_customer = $conn->prepare("INSERT INTO customers (name, table_name, adult, child) VALUES (?, ?, ?, ?)");
        $stmt_customer->bind_param("ssii", $c_name, $table_name_for_customer, $adult, $child);
        $stmt_customer->execute();
        $customer_id = $stmt_customer->insert_id;
        $stmt_customer->close();
    }
    $stmt_check->close();

    // ✅ Step 3: Insert booking
    $stmt_booking = $conn->prepare("INSERT INTO booking (user_name, user_email, booking_date, booking_time, no_of_persons, table_id, status, customer_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_booking->bind_param("sssiiisi", $c_name, $email, $date, $time, $total_members, $table_no, $status, $customer_id);
    $stmt_booking->execute();
    $booking_id = $stmt_booking->insert_id;
    $stmt_booking->close();

    // ✅ Step 4: Insert table status
    $stmt_status = $conn->prepare("INSERT INTO table_status (table_id, booking_id, booking_date, booking_time, status) VALUES (?, ?, ?, ?, ?)");
    $stmt_status->bind_param("iisss", $table_no, $booking_id, $date, $time, $status);
    $stmt_status->execute();
    $stmt_status->close();

    $conn->commit();
    echo json_encode(["message" => "Table booked successfully"]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["error" => "Booking failed: " . $e->getMessage()]);
}

$conn->close();
?>
