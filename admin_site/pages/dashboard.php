<?php
session_start();
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$conn = new mysqli("localhost", "root", "", "cafe_management");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['admin_email'])) {
    header("Location: ../index.php");
    exit;
}

// Create a SQL query to fetch all items
$sql_query_items = "SELECT * FROM users_list";
$result_items = $conn->query($sql_query_items);

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
              <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
               
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
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
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
                </span> User Lists
              </h3>             
            </div>  
            
            <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <!-- New Data -->
                  <form method="POST" action="" id="add" style="display:block">
                    <div class="add-items d-flex gap-4">
                      <input type="text" name="table_name" class="form-control todo-list-input"
                        placeholder="Please Search Here...">                      
                      <button type="submit" class="add btn btn-gradient-primary font-weight-bold"
                        id="primary">Search</button>
                    </div>
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                      $search_query = $_POST["table_name"];
                  
                      // Create a SQL query to fetch items based on the search
                      $sql_query_items = "SELECT * FROM users_list WHERE firstname LIKE '%$search_query%' OR lastname LIKE '%$search_query%' OR email LIKE '%$search_query%'";
                      $result_items = $conn->query($sql_query_items);
                  } else {
                      // If not submitted, fetch all items
                      $sql_query_items = "SELECT * FROM users_list";
                      $result_items = $conn->query($sql_query_items);
                  }
                  
                    ?>
                  </form>                  
                </div>
              </div>
            </div>
          </div>
          
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">User Lists</h4>
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th> # </th>
                            <th> First Name </th>
                            <th> Last Name </th>
                            <th> email </th>   
                            <!-- <th> Action </th>                          -->
                          </tr>
                        </thead>
                        <tbody>
                        <?php
                          // Check if items were found
                          if ($result_items->num_rows > 0) {
                            $counter = 1; // Initialize counter
                            while ($row = $result_items->fetch_assoc()) {
                                $firstname = $row["firstname"];
                                $lastname = $row["lastname"];
                                $email =  $row["email"];
                                $id = $counter; // Assign counter value as ID
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $id; ?>
                                    </td>
                                    <td> <?php echo $firstname; ?> </td>
                                    <td>
                                        <?php echo $lastname; ?>
                                    </td>
                                    <td> <?php echo $email; ?> </td>
                                    <td>
                                <!-- <div class="d-flex flex-row gap-3 icon">                                 
                                  <i id="primary" class="delete mdi mdi-close-circle-outline">
                                  </i>
                                </div> -->
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
            </div>           
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
              <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright © cafe management 2024
                
              </span>
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
    <script src="/../assets/js/hoverable-collapse.js"></script>
    <script src="../assets/js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="../assets/js/dashboard.js"></script>
    <script src="../assets/js/todolist.js"></script>
    <!-- End custom js for this page -->
  </body>
</html>