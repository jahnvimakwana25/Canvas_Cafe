<?php
session_start();
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if (!isset($_SESSION['admin_email'])) {
    header("Location: ../index.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "cafe_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Correct SQL query
$sql_query_items = "
    SELECT ts.status_id, ts.booking_date, ts.booking_time, ts.status,
           t.table_name, t.capacity,
           b.user_name
    FROM table_status ts
    LEFT JOIN table_entry t ON ts.table_id = t.id
    LEFT JOIN booking b ON ts.booking_id = b.booking_id
";
$result_items = $conn->query($sql_query_items);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin</title>
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="shortcut icon" href="../assets/images/favicon.ico" />
</head>

<body>
    <div class="container-scroller">
        <!-- Navbar -->
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="./dashboard.php">Cafe Management</a>
                <a class="navbar-brand brand-logo-mini" href="./dashboard.php">CM</a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="nav-profile-text">
                                <p class="mb-1 text-black">Cafe Management</p>
                            </div>
                        </a>
                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="logout.php">
                                <i class="mdi mdi-logout me-2 text-primary"></i> Signout 
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Sidebar -->
        <div class="container-fluid page-body-wrapper">
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link" href="./dashboard.php"><span class="menu-title">User List</span><i class="mdi mdi-account menu-icon"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="./table.php"><span class="menu-title">Table</span><i class="mdi mdi-glass-wine menu-icon"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="./items.php"><span class="menu-title">Items</span><i class="mdi mdi-food menu-icon"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="./table_status.php"><span class="menu-title">Table Status</span><i class="mdi mdi-qqchat menu-icon"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="./order_list.php"><span class="menu-title">Items List</span><i class="mdi mdi-food-variant menu-icon"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="./banner.php"><span class="menu-title">Service</span><i class="mdi mdi-bookmark-check menu-icon"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="./payment_list.php"><span class="menu-title">Payment</span><i class="mdi mdi-bank menu-icon"></i></a></li>
                </ul>
            </nav>

            <!-- Main Panel -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title">
                            <span class="page-title-icon bg-gradient-primary text-white me-2">
                                <i class="mdi mdi-home"></i>
                            </span> Table Status
                        </h3>
                    </div>

                    <!-- Table Display -->
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Table Status</h4>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Table Name</th>
                                            <th>Capacity</th>
                                            <th>Checkin Time</th>
                                            <th>Checkin Date</th>
                                            <th>User Name</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result_items && $result_items->num_rows > 0) {
                                            $counter = 1;
                                            while ($row = $result_items->fetch_assoc()) {
                                                $table_name    = $row["table_name"] ?? "-";
                                                $table_capacity = $row["capacity"] ?? "-";
                                                $check_in_time  = $row["booking_time"] ?? "";
                                                $check_in_date  = $row["booking_date"] ?? "";
                                                $status        = $row["status"] ?? "unknown";
                                                $username      = $row["user_name"] ?? "Not Booked";
                                                ?>
                                                <tr>
                                                    <td><?= $counter; ?></td>
                                                    <td><?= htmlspecialchars($table_name); ?></td>
                                                    <td><?= htmlspecialchars($table_capacity); ?></td>
                                                    <td><?= !empty($check_in_time) ? date("g:i A", strtotime($check_in_time)) : "-"; ?></td>
                                                    <td><?= !empty($check_in_date) ? date("l, d-M-Y", strtotime($check_in_date)) : "-"; ?></td>
                                                    <td><?= htmlspecialchars($username); ?></td>
                                                    <td>
                                                        <?php
                                                        if (strtolower($status) === "available") {
                                                            echo '<label class="badge badge-success">Available</label>';
                                                        } elseif (strtolower($status) === "booked" || strtolower($status) === "confirmed") {
                                                            echo '<label class="badge badge-danger">Booked</label>';
                                                        } else {
                                                            echo '<label class="badge badge-warning">Unknown</label>';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $counter++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='7'>No items found in the database.</td></tr>";
                                        }
                                        $conn->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Footer -->
                <footer class="footer">
                    <div class="container-fluid d-flex justify-content-between">
                        <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">
                            Copyright © cafe management 2024
                        </span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../assets/vendors/chart.js/Chart.min.js"></script>
    <script src="../assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="../assets/js/off-canvas.js"></script>
    <script src="../assets/js/hoverable-collapse.js"></script>
    <script src="../assets/js/misc.js"></script>
    <script src="../assets/js/dashboard.js"></script>
    <script src="../assets/js/todolist.js"></script>
          <script>
  window.addEventListener("pageshow", function(event) {
    if (event.persisted || (window.performance && performance.navigation.type === 2)) {
      window.location.reload();
    }
  });
</script>
</body>
</html>

