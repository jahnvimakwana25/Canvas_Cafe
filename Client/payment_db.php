<?php
session_start();
header('Content-Type: application/json');

// Database Connection
$conn = new mysqli("localhost", "root", "", "cafe_management");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction();

    try {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        // Required data from client
        $orderId = isset($data['order_id']) && $data['order_id'] !== '' && is_numeric($data['order_id']) ? intval($data['order_id']) : null;
        $amount = isset($data['total_amount']) && $data['total_amount'] !== '' && is_numeric($data['total_amount']) ? floatval($data['total_amount']) : 0.0;
        
        // Required payment gateway data
        $paymentId = $data['paymentId'] ?? null;
        $razorpayOrderId = $data['razorpay_order_id'] ?? null;
        $razorpaySignature = $data['razorpay_signature'] ?? '';

        // Derive user information from DB using order_id (do not rely on session)
        $userId = null;       // customers.customer_id
        $userName = null;     // booking.user_name or customers.name
        $userEmail = null;    // booking.user_email
        $tableName = null;    // customers.table_name

        if ($orderId !== null) {
            // Find the related customer_id from orders
            $sqlOrder = "SELECT customer_id FROM orders WHERE order_id = ?";
            $stmtOrder = $conn->prepare($sqlOrder);
            if (!$stmtOrder) {
                throw new Exception("Error preparing order lookup: " . $conn->error);
            }
            $stmtOrder->bind_param("i", $orderId);
            $stmtOrder->execute();
            $resOrder = $stmtOrder->get_result();
            $rowOrder = $resOrder->fetch_assoc();
            $stmtOrder->close();

            if ($rowOrder && isset($rowOrder['customer_id'])) {
                $userId = (int)$rowOrder['customer_id'];

                // Fetch customer record
                $sqlCustomer = "SELECT name, table_name FROM customers WHERE customer_id = ?";
                $stmtCustomer = $conn->prepare($sqlCustomer);
                if (!$stmtCustomer) {
                    throw new Exception("Error preparing customer lookup: " . $conn->error);
                }
                $stmtCustomer->bind_param("i", $userId);
                $stmtCustomer->execute();
                $resCustomer = $stmtCustomer->get_result();
                $rowCustomer = $resCustomer->fetch_assoc();
                $stmtCustomer->close();

                $customerName = $rowCustomer['name'] ?? null;
                $tableName = $rowCustomer['table_name'] ?? null;

                // Get latest booking row for email and possibly better name
                $sqlBooking = "SELECT user_email, user_name FROM booking WHERE customer_id = ? ORDER BY booking_id DESC LIMIT 1";
                $stmtBooking = $conn->prepare($sqlBooking);
                if ($stmtBooking) {
                    $stmtBooking->bind_param("i", $userId);
                    $stmtBooking->execute();
                    $resBooking = $stmtBooking->get_result();
                    $rowBooking = $resBooking->fetch_assoc();
                    $stmtBooking->close();

                    if ($rowBooking) {
                        $userEmail = $rowBooking['user_email'] ?? null;
                        $userName = $rowBooking['user_name'] ?? null;
                    }
                }

                if ($userName === null) {
                    $userName = $customerName;
                }

                // Map email to users_list.id to satisfy payments.user_id FK
                if ($userEmail !== null) {
                    $sqlUser = "SELECT id, firstname, lastname FROM users_list WHERE email = ? LIMIT 1";
                    $stmtUser = $conn->prepare($sqlUser);
                    if ($stmtUser) {
                        $stmtUser->bind_param("s", $userEmail);
                        $stmtUser->execute();
                        $resUser = $stmtUser->get_result();
                        $rowUser = $resUser->fetch_assoc();
                        $stmtUser->close();

                        if ($rowUser && isset($rowUser['id'])) {
                            $userId = (int)$rowUser['id'];
                            if ($userName === null) {
                                $first = $rowUser['firstname'] ?? '';
                                $last = $rowUser['lastname'] ?? '';
                                $combined = trim($first . ' ' . $last);
                                $userName = $combined !== '' ? $combined : null;
                            }
                        } else {
                            // Fallback to session user id if available (user is logged in)
                            if (isset($_SESSION['id']) && is_numeric($_SESSION['id'])) {
                                $userId = (int)$_SESSION['id'];
                            }
                            if ($userName === null) {
                                if (isset($_SESSION['firstname']) || isset($_SESSION['lastname'])) {
                                    $first = $_SESSION['firstname'] ?? '';
                                    $last = $_SESSION['lastname'] ?? '';
                                    $combined = trim($first . ' ' . $last);
                                    if ($combined !== '') { $userName = $combined; }
                                } elseif (isset($_SESSION['name'])) {
                                    $userName = $_SESSION['name'];
                                }
                            }
                        }
                    }
                }
            }
        }

        // Build granular missing-fields diagnostics to aid debugging
        $missing = [];
        if ($orderId === null) { $missing[] = 'order_id'; }
        if ($amount <= 0) { $missing[] = 'total_amount'; }
        if ($userId === null) { $missing[] = 'derived.customer_id_from_order'; }
        if ($userName === null) { $missing[] = 'derived.user_name'; }
        if ($userEmail === null) { $missing[] = 'derived.user_email'; }
        if ($paymentId === null || $paymentId === '') { $missing[] = 'paymentId'; }
        if ($razorpayOrderId === null || $razorpayOrderId === '') { $missing[] = 'razorpay_order_id'; }

        // Ensure optional strings are not null to avoid insert issues
        if ($tableName === null) { $tableName = ''; }

        if (!empty($missing)) {
            throw new Exception("Error: Missing fields: " . implode(", ", $missing));
        }

        // Step 1: Insert data into the payments table
        $insertPaymentSql = "INSERT INTO `payments` 
                             (`user_id`, `order_id`, `user_name`, `user_email`, `table_name`, `total_amount`, `payment_id_from_gateway`, `razorpay_order_id`, `razorpay_signature`, `payment_status`) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'completed')";
        $stmt_payment = $conn->prepare($insertPaymentSql);
        if (!$stmt_payment) {
            throw new Exception("Error preparing payment statement: " . $conn->error);
        }

        // Bind parameters: 'i' for int, 's' for string, 'd' for double
        // Expected order: user_id(i), order_id(i), user_name(s), user_email(s), table_name(s), total_amount(d), payment_id_from_gateway(s), razorpay_order_id(s), razorpay_signature(s)
        $stmt_payment->bind_param("iisssdsss", $userId, $orderId, $userName, $userEmail, $tableName, $amount, $paymentId, $razorpayOrderId, $razorpaySignature);
        $stmt_payment->execute();
        $stmt_payment->close();

        // Step 2: Update the order status to 'paid'
        $updateOrderSql = "UPDATE `orders` SET `status` = 'paid' WHERE `order_id` = ?";
        $stmt_update_order = $conn->prepare($updateOrderSql);
        if (!$stmt_update_order) {
            throw new Exception("Error preparing order update statement: " . $conn->error);
        }
        $stmt_update_order->bind_param("i", $orderId);
        $stmt_update_order->execute();
        $stmt_update_order->close();

        // Commit the transaction
        $conn->commit();

        echo json_encode([
            "success" => true,
            "message" => "Payment successfully processed.",
            "order_id" => $orderId
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        $errorCode = "PAYMENT_PROCESSING_ERROR";
        echo json_encode(["success" => false, "message" => $e->getMessage(), "error_code" => $errorCode]);
    } finally {
        $conn->close();
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>