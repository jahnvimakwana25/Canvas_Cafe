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
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
define('BASE_URL', 'http://localhost/Canvas_Cafe/');

include('config.php');

// Create a SQL query to fetch all items
$sql_query_items = "SELECT * FROM items";
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

  <!-- Popup -->
  <div class="alert popup-menu alert-success" id="popup-message" style="display:none">
  </div>



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
              </span> Items
            </h3>
            <nav aria-label="breadcrumb">
              <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                  <a class="add btn btn-gradient-primary font-weight-bold" href="./itmes_add.php">Add Items</a>
                </li>
              </ul>
            </nav>
          </div>
          <div class="row">
<?php
// Check if items were found
if ($result_items->num_rows > 0) {
  while ($row = $result_items->fetch_assoc()) {
    $item_name = $row["item_name"];
    $item_price = $row["item_price"];
    $item_main_image = "uploads/" . $row["item_main_image"]; // fixed path
    $item_captions = $row["item_captions"];
    $id = $row["id"];
    ?>
    <div class="col-md-4 stretch-card grid-margin">
      <a class="card">
        <img src="<?php echo $item_main_image; ?>" 
             class="card-img-top w-100" 
             style="height:200px; object-fit:cover;" 
             alt="Item Image"
             onerror="this.src='../assets/images/default.jpg';" />
             
        <i class="mdi mdi-delete menu-icon delete-img-item" 
           onclick="deleteItem(<?php echo $id; ?>);return false;"></i>
        <div class="body-img-text">
          <h4 class="font-weight-normal item-name mb-3 position-relative">
            <?php echo $item_name; ?>
          </h4>
          <h2 class="mb-3 item-price position-relative">₹<?php echo $item_price; ?></h2>
          <h6 class="card-text text-area-item">
            ❝ <?php echo $item_captions; ?> ❞
          </h6>
        </div>
      </a>
    </div>
    <?php
  }
} else {
  echo "No items found in the database.";
}

// Close the database connection
$conn->close();
?>
</div>

                 
        </div>
        
      


        <!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <div class="d-flex align-items-center text-muted font-weight-light">
                  <span>₹999</span>
                </div>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <div class="row ">
                          <div class="col-6 pe-1">
                            <img
                              src="https://images.unsplash.com/photo-1695664225177-32c168130e30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1586&q=80"
                              class="item-gallery-img mb-2 mw-100 w-100 rounded" alt="image">
                            <img
                              src="https://images.unsplash.com/photo-1695515115475-dc5495581ea8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDJ8fHxlbnwwfHx8fHw%3D&auto=format&fit=crop&w=500&q=60"
                              class="item-gallery-img mw-100 w-100 rounded" alt="image">
                          </div>
                          <div class="col-6 ps-1">
                            <img
                              src="https://images.unsplash.com/photo-1695133389296-fdd6c49d422c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDR8fHxlbnwwfHx8fHw%3D&auto=format&fit=crop&w=500&q=60"
                              class="item-gallery-img mb-2 mw-100 w-100 rounded" alt="image">
                            <img
                              src="https://images.unsplash.com/photo-1695051671250-2f2975972e9a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDd8fHxlbnwwfHx8fHw%3D&auto=format&fit=crop&w=500&q=60"
                              class="item-gallery-img mw-100 w-100 rounded" alt="image">
                          </div>
                        </div>
                        <div class="d-flex mt-2 align-items-top">
                          <div class="mb-0 flex-grow">
                            <h5 class="me-2">School Website - Authentication Module.</h5>
                          </div>
                          <div class="ms-auto">
                            <i class="mdi mdi-heart-outline text-muted"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> -->
      </div>
    </div>
  </div>




  <!-- Bootstrap 4 modal -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>

  <script>
    function deleteItem(itemId) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "delete_items_gallery.php", true); // Replace with the actual PHP script URL
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          if (xhr.responseText === "success") {
            const fetchSuccesfullyMessageDivId = document.getElementById('popup-message');
              fetchSuccesfullyMessageDivId.style.display = 'block';
              fetchSuccesfullyMessageDivId.innerHTML = 'Item entry successfull delete';

              // Hide the message after a delay (e.g., 5 seconds)
              setTimeout(() => {
                fetchSuccesfullyMessageDivId.style.display = 'none';
              }, 5000);
            // Reload or update the page to reflect the deleted item
            location.reload();
          } else {
            alert("Failed to delete the item. Please try again later.");
          }
        }
      };
      xhr.send("item_id=" + itemId);

    }
   
  </script>
  <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="../assets/vendors/chart.js/Chart.min.js"></script>
  <script src="../assets/js/jquery.cookie.js" type="text/javascript"></script>
  <script src="../assets/js/off-canvas.js"></script>
  <script src="../assets/js/hoverable-collapse.js"></script>
  <script src="../assets/js/misc.js"></script>
  <script src="../assets/js/popup.js"></script>
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