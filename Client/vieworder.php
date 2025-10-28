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

// Database connection
$conn = new mysqli("localhost", "root", "", "cafe_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_email = $_SESSION['email'];   

// Corrected query to get customer_id by joining with the booking table
$sql_customer = "SELECT c.customer_id 
                 FROM customers c
                 JOIN booking b ON c.customer_id = b.customer_id
                 WHERE b.user_email = ?
                 ORDER BY b.booking_id DESC
                 LIMIT 1";
$stmt_customer = $conn->prepare($sql_customer);
if (!$stmt_customer) {
    die("SQL error: " . $conn->error);
}
$stmt_customer->bind_param("s", $user_email);
$stmt_customer->execute();
$result_customer = $stmt_customer->get_result();
$customer_row = $result_customer->fetch_assoc();
$stmt_customer->close();

$customer_id = $customer_row ? $customer_row['customer_id'] : null;

$orders = [];
if ($customer_id) {
    // Modified SQL query to only fetch orders with 'open' status
    $sql = "SELECT o.order_id, o.checkin_date, o.checkin_time, o.status,
                   oi.item_name, oi.item_price, oi.quantity
            FROM orders o
            INNER JOIN order_items oi ON o.order_id = oi.order_id
            WHERE o.customer_id = ? AND o.status != 'canceled'
            ORDER BY o.checkin_date DESC, o.order_id DESC, o.checkin_time DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Canvas Cafe - My Orders</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="img/favicon.ico" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <div class="container-fluid bg-dark px-0">
            <div class="row gx-0">
                <div class="col-lg-3 bg-dark d-none d-lg-block">
                    <a href="index.php" class="navbar-brand w-100 h-100 m-0 p-0 d-flex align-items-center justify-content-center">
                        <h5 class="m-0 text-primary text-uppercase">Canvas Cafe</h5>
                    </a>
                </div>
                <div class="col-lg-9">
                    <nav class="navbar navbar-expand-lg bg-dark navbar-dark p-3 p-lg-0">
                        <a href="index.php" class="navbar-brand d-block d-lg-none">
                            <h1 class="m-0 text-primary text-uppercase">Canvas Cafe</h1>
                        </a>
                        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                            <div class="navbar-nav mr-auto py-0">
                                <a href="index.php" class="nav-item nav-link active">Home</a>
                                <a href="about.php" class="nav-item nav-link">About</a>
                                <a href="service.php" class="nav-item nav-link">Services</a>
                                <a href="booking.php" class="nav-item nav-link">Book Table</a>
                                <a href="items.php" class="nav-item nav-link">Our Menu</a>
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Status</a>
                                    <div class="dropdown-menu rounded-0 m-0">
                                        <a href="mybooking.php" class="dropdown-item">View Booking</a>
                                        <a href="vieworder.php" class="dropdown-item">View Order</a>
                                    </div>
                                </div>
                                <a href="contact.php" class="nav-item nav-link">Feedback</a>
                            </div>
                            <div class="navbar-nav py-0">
                                <?php if(isset($_SESSION['firstname']) && !empty($_SESSION['firstname'])): ?>
                                    <span class="nav-item nav-link active" id="customer_name">
                                        <p>Welcome, <?= htmlspecialchars($_SESSION['firstname']) ?>!</p>
                                    </span>
                                    <a href="logout.php" class="nav-item nav-link" id="logOut_link" onclick="logOut()">Logout</a>
                                <?php else: ?>
                                    <a href="auth.php" class="nav-item nav-link active" id="login_details">Login</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container-fluid page-header mb-5 p-0" style="background-image: url(img/carousel-1.jpg);">
            <div class="container-fluid page-header-inner py-5">
                <div class="container text-center pb-5">
                    <h1 class="display-3 text-white mb-3 animated slideInDown">My Orders</h1>
                </div>
            </div>
        </div>
        <div class="container py-5">
            <h2 class="mb-4 text-center">My Orders</h2>
            <?php
            if (isset($_SESSION['message'])) {
                echo '<div class="alert alert-success text-center">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']);
            }
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger text-center">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }

            if (!empty($orders)) {
                echo '<div class="table-responsive">';
                echo '<table class="table table-bordered table-hover text-center">';
                echo '<thead class="table-dark">';
                echo '<tr>
                        <th>Order ID</th>
                        <th>Status</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price (per item)</th>
                        <th>Total Price</th>
                        <th>Check-in Date</th>
                        <th>Check-in Time</th>
                        <th>Action</th>
                      </tr>';
                echo '</thead><tbody>';

                $order_info = [];
                foreach ($orders as $row) {
                    if (!isset($order_info[$row['order_id']])) {
                        $order_info[$row['order_id']] = [
                            'status' => $row['status'],
                            'items' => [],
                            'total_amount' => 0,
                            'checkin_date' => $row['checkin_date'],
                            'checkin_time' => $row['checkin_time'],
                        ];
                    }
                    $order_info[$row['order_id']]['items'][] = $row;
                    $order_info[$row['order_id']]['total_amount'] += $row['quantity'] * $row['item_price'];
                }

                foreach ($order_info as $order_id => $details) {
                    $first_item = true;
                    foreach ($details['items'] as $item) {
                        echo '<tr>';
                        if ($first_item) {
                            echo '<td rowspan="' . count($details['items']) . '">' . $order_id . '</td>';
                            echo '<td rowspan="' . count($details['items']) . '">' . htmlspecialchars($details['status']) . '</td>';
                        }
                        echo '<td>' . htmlspecialchars($item['item_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($item['quantity']) . '</td>';
                        echo '<td>₹' . number_format($item['item_price'], 2) . '</td>';
                        echo '<td>₹' . number_format($item['quantity'] * $item['item_price'], 2) . '</td>';
                        if ($first_item) {
                            echo '<td rowspan="' . count($details['items']) . '">' . htmlspecialchars($details['checkin_date']) . '</td>';
                            echo '<td rowspan="' . count($details['items']) . '">' . htmlspecialchars($details['checkin_time']) . '</td>';
                        }
                        // Action button column
                        if ($first_item) {
                            echo '<td rowspan="' . count($details['items']) . '">';
                            $status_val = isset($details['status']) ? strtolower(trim($details['status'])) : '';
                            $isCancellable = ($status_val !== 'paid' && $status_val !== 'canceled' && $status_val !== 'cancelled');
                            if ($isCancellable) {
                                echo '<a href="cancel_order.php?order_id=' . $order_id . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to cancel this order?\')">Cancel</a>';
                            } else {
                                echo '<a href="cancel_order.php?order_id=' . $order_id . '" class="btn btn-danger btn-sm" style="background-color:#dc3545;border-color:#dc3545;color:#fff;opacity:.65;">Cancel</a>';
                            }
                            echo '</td>';
                        }
                        echo '</tr>';
                        $first_item = false;
                    }
                    echo '<tr class="table-info">';
                    echo '<td colspan="5" class="text-end fw-bold">Subtotal for Order ID ' . $order_id . '</td>';
                    echo '<td class="fw-bold">₹' . number_format($details['total_amount'], 2) . '</td>';
                    echo '<td colspan="3"></td>';
                    echo '</tr>';
                }

                echo '</tbody></table></div>';
            } else {
                echo '<div class="alert alert-info text-center">No open orders found.</div>';
            }
            ?>
        </div>
        </div>
</body>
</html>