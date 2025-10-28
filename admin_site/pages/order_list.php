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

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create a SQL query to fetch all items
$sql_query_items = "SELECT
                     c.customer_id AS id,
                     c.name AS name,
                     o.checkin_time AS checkin_time,
                     o.checkin_date AS checkin_date,
                     o.amount AS amount,
                     GROUP_CONCAT(CONCAT(oi.item_name, ' (', oi.quantity, 'x)')) AS order_items,
                     c.child AS child,
                     c.adult AS adult,
                     c.table_name AS table_name
                     FROM customers c
                     LEFT JOIN orders o ON c.customer_id = o.customer_id
                     LEFT JOIN order_items oi ON o.order_id = oi.order_id
                     GROUP BY c.customer_id, c.name, o.checkin_time, o.checkin_date, o.amount, c.child, c.adult, c.table_name";

$result_items = $conn->query($sql_query_items);

// Check if the query executed successfully
if ($result_items === false) {
    die("Query failed: " . $conn->error);
}
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
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="./dashboard.php">Cafe Management</a>
                <a class="navbar-brand brand-logo-mini" href="./dashboard.php">CM</a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown"
                            aria-expanded="false">

                            <div class="nav-profile-text">
                                <p class="mb-1 text-black">Cafe Management</p>
                            </div>
                        </a>
                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="logout.php">
                                <i class="mdi mdi-logout me-2 text-primary"></i> Signout </a>
                        </div>
                    </li>
                    <li class="nav-item d-none d-lg-block full-screen-link">
                        <a class="nav-link">
                            <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
                        </a>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        <div class="container-fluid page-body-wrapper">
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="./dashboard.php">
                            <span class="menu-title">User List</span>
                            <i class="mdi mdi-account menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./table.php">
                            <span class="menu-title">Table</span>
                            <i class="mdi mdi-glass-wine menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./items.php">
                            <span class="menu-title">Items</span>
                            <i class="mdi mdi-food menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./table_status.php">
                            <span class="menu-title">Table Status</span>
                            <i class="mdi mdi-qqchat menu-icon"></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="./order_list.php">
                            <span class="menu-title">Items List</span>
                            <i class="mdi mdi-food-variant menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./banner.php">
                            <span class="menu-title">Service</span>
                            <i class="mdi mdi-bookmark-check menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./payment_list.php">
                            <span class="menu-title">Payment</span>
                            <i class="mdi mdi-bank menu-icon"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title">
                            <span class="page-title-icon bg-gradient-primary text-white me-2">
                                <i class="mdi mdi-home"></i>
                            </span> Table Status
                        </h3>
                    </div>



                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Items List</h4>
                                </p>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Table No.</th>
                                            <th>Member</th>
                                            <th>Checkin-Time</th>
                                            <th>Checkin-Date</th>
                                            <th>Orderlist</th>
                                            <th>Ammount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Check if items were found
                                        if ($result_items && $result_items->num_rows > 0) {
                                            $counter = 1;
                                            while ($row = $result_items->fetch_assoc()) {
                                                $id = $row["id"];
                                                $name = $row["name"];
                                                $checkin_time = $row["checkin_time"];
                                                $checkin_date = $row["checkin_date"];
                                                $amount = $row["amount"];
                                                $order_items = $row["order_items"];
                                                $child = $row["child"];
                                                $adult = $row["adult"];
                                                $table_name = $row["table_name"];
                                                $startID = $counter 
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $startID; ?>
                                            </td>
                                            <td>
                                                <?php echo $name; ?>
                                            </td>
                                            <td>
                                                <?php echo $table_name; ?>
                                            </td>
                                            <td>
                                                <?php echo $child?>(C) + <?php echo $adult; ?>(A)
                                            </td>
                                            <td>
                                                <?php
                                                     if($checkin_time){
                                                         // Assuming $check_in_time contains a Unix timestamp or a datetime string
                                                         $checkin_time = strtotime($checkin_time); // Convert to timestamp if not already
                                                     
                                                         // Format the timestamp as "7:00 PM"
                                                         $formatted_time = date("g:i A", $checkin_time);
                                        
                                                         echo $formatted_time;
                                                     }
                                                     else{
                                                         echo "-";
                                                     }
                                                     ?>
                                            </td>
                                            <td>
                                                <?php
                                                     if($checkin_date){
                                                     $check_in_date = $checkin_date; // Replace with your date variable
                                                     
                                                     // Convert the date to a timestamp
                                                     $timestamp = strtotime($checkin_date);

                                                     // Format the timestamp as "l, d-M-Y"
                                                     $formatted_date = date("l, d-M-Y", $timestamp);

                                                     echo $formatted_date;
                                                     }
                                                     else{
                                                         echo "-";
                                                     } // Output: Friday, 15-Sep-2023
                                                     ?>
                                            </td>
                                            <td>
                                                <?php echo $order_items; ?>
                                            </td>
                                            <td>
                                                ₹<?php echo $amount; ?>
                                            </td>
                                            
                                        </tr>
                                    </tbody>
                                        <?php
                                         $counter++; // Increment counter for the next iteration

                                        }
                                        } else {
                                            echo "No items found in the database.";
                                        }

                                        // Close the database connection
                                        $conn->close();
                                        ?>
                                </table>
                            </div>
                        </div>
                    </div>



                </div>
                <footer class="footer">
                    <div class="container-fluid d-flex justify-content-between">
                        <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright © cafe
                            management
                            2024</span>
                    </div>
                </footer>
                </div>
            </div>
        </div>
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