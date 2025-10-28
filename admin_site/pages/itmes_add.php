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
    <div class="alert popup-menu alert-success fade" id="alert-primary" role="alert">
       Table entry Successfully delete !
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
                    <a href="./items.php" class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-keyboard-return"></i>
                    </a> Back
                </h3>                
            </div>           
            <div class="row">            
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title text-center">Add Items</h4>
                    <form class="forms-sample" action="./items_action.php" method="POST" enctype="multipart/form-data">
                      <div class="form-group">
                        <label for="item_name">Item Name</label>
                        <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Enter Item Name">
                      </div>   
                      <div class="form-group row">
                        <div class="col-sm-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="item_category" selected id="Dinner" value="Dinner" checked> Dinner </label>
                                </div>
                        </div>
                        <div class="col-sm-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="item_category"  id="Starter" value="Starter" checked> Starter </label>
                                </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-check">
                                <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="item_category" id="Lunch" value="Lunch"> Lunch </label>
                            </div>
                        </div>
                      </div>  
                      <div class="form-group">
                        <label>Main Item Image</label>
                        <div class="input-group col-xs-12">
                        <input type="file" class="form-control file-upload-info" id="item_main_image" name="item_main_image">                        </div>
                      </div>
                    <!-- <div class="form-group">
                      <label>Item Gallery</label>
                      <div class="input-group col-xs-12">
                      <input type="file" multiple="true" id="item_gallery_image" name="item_gallery_image[]" class="form-control file-upload-info">                        </div>
                    </div> -->
                      <div class="form-group">
                        <label for="item_price">Price</label>
                        <input type="number" class="form-control" id="item_price" name="item_price" placeholder="Price">                      </div>
                      <div class="form-group">
                        <label for="item_captions">Textarea</label>
                        <textarea class="form-control" name="item_captions" id="item_captions" rows="4"></textarea>                      </div>
                      <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                      <button class="btn btn-light">Cancel</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:../../partials/_footer.html -->
          <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
              <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright © cafe management 2024</span>
            </div>
          </footer>
          <!-- partial -->
        </div>
    </div>
   <script>
    
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

