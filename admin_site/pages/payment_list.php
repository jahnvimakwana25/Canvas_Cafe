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

// Create a SQL query to fetch all items directly from the payments table
$sql_query_items = "SELECT payment_date AS date, user_name, user_email, table_name, payment_id, total_amount FROM payments";

$result_items = $conn->query($sql_query_items);

// Check if the query executed successfully
if ($result_items === false) {
    die("Query failed: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin</title>
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">

    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico" />
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
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
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- Sidebar -->
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
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title">
                            <span class="page-title-icon bg-gradient-primary text-white me-2">
                                <i class="mdi mdi-home"></i>
                            </span> Payment List
                        </h3>
                    </div>



                    <!-- Table For Service Name -->
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Payment List</h4>
                                </p>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>User Name</th>
                                            <th>Email</th>
                                            <th>Table Name</th>
                                            <th>Payment Id</th>
                                            <th>Total Ammount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Check if items were found
                                        if ($result_items->num_rows > 0) {
                                            $counter = 1;
                                            while ($row = $result_items->fetch_assoc()) {
                                                $date = $row["date"];
                                                $user_name = $row["user_name"];
                                                $user_email = $row["user_email"];
                                                $table_name = $row["table_name"];
                                                $payment_id = $row["payment_id"];
                                                $total_amount = $row["total_amount"];
                                                $id = isset($row["id"]) ? $row["id"] : null;
                                                $startID = $counter;

                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $startID; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $date; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $user_name; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $user_email; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $table_name; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $payment_id; ?>
                                                    </td>
                                                    <td>
                                                        ₹<?php echo $total_amount; ?>
                                                    </td>
                                                    
                                                </tr>
                                            </tbody>
                                            <?php
                                            $counter++;
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
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="container-fluid d-flex justify-content-between">
                        <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright © cafe
                            management
                            2024</span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->

    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../assets/vendors/chart.js/Chart.min.js"></script>
    <script src="../assets/js/jquery.cookie.js" type="text/javascript"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../assets/js/off-canvas.js"></script>
    <script src="../assets/js/hoverable-collapse.js"></script>
    <script src="../assets/js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="../assets/js/dashboard.js"></script>
    <script src="../assets/js/todolist.js"></script>
      <script>
  window.addEventListener("pageshow", function(event) {
    if (event.persisted || (window.performance && performance.navigation.type === 2)) {
      window.location.reload();
    }
  });
</script>
    <!-- End custom js for this page -->
</body>

</html>