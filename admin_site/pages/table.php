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
  <div class="alert popup-menu alert-success" id="popup-message" style="display:none">
  </div>

  <?php
  if (isset($_SESSION['success_message'])) {
    echo '<div id="success-message" class="alert popup-menu alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
  }

  if (isset($_SESSION['alert_message'])) {
    echo '<div id="success-message" class="alert popup-menu alert-danger">' . $_SESSION['alert_message'] . '</div>';
    unset($_SESSION['alert_message']);
  }

  if (isset($_SESSION['edit_message'])) {
    echo '<div id="success-message" class="alert popup-menu alert-success">' . $_SESSION['edit_message'] . '</div>';
    unset($_SESSION['edit_message']);
  }

  ?>


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
              </span> Table Entry
            </h3>
          </div>

          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <!-- New Data -->
                  <form method="POST" action="table_action.php" id="add" style="display:block">
                    <div class="add-items d-flex gap-4">
                      <input type="text" name="table_name" class="form-control todo-list-input"
                        placeholder="Please add Table here...">
                      <input type="number" name="capacity" class="form-control w-4 todo-list-input"
                        placeholder="Please enter table capicity">
                      <button type="submit" class="add btn btn-gradient-primary font-weight-bold"
                        id="primary">Add</button>
                    </div>
                  </form>

                  <!-- Edit -->
                  <form method="POST" action="table_edit.php" id="edit" style="display:none">
                    <div class="add-items d-flex gap-4">
                      <input type="hidden" id="id" name="id" value="">
                      <input type="text" id="edit_table_name" name="table_name" class="form-control todo-list-input"
                        placeholder="Please add Table here...">
                      <input type="number" id="edit_table_capacity" name="capacity"
                        class="form-control w-4 todo-list-input" placeholder="Please enter table capacity">
                      <button type="submit" class="add btn btn-gradient-primary font-weight-bold">Edit</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Table Entry</h4>
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th> # </th>
                          <th> Table No: </th>
                          <th> Table Capicity: </th>
                          <th> Action </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $conn = new mysqli("localhost", "root", "", "cafe_management");
                        if ($conn->connect_error) {
                          die("Connection failed: " . $conn->connect_error);
                        }

                        $query = "SELECT * FROM table_entry";
                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                          $counter = 1; // Initialize counter
                          while ($row = $result->fetch_assoc()) {
                            $startId = $counter

                            ?>
                            <tr>
                              <td class="table_id">
                                <?php echo $startId; ?>
                              </td>
                              <td>
                                <?php echo $row['table_name']; ?>
                              </td>
                              <td>
                                <?php echo $row['capacity']; ?> Person
                              </td>
                              <td>
                                <div class="d-flex flex-row gap-3 icon">
                                  <i class="edit edit_btn mdi mdi-grease-pencil" onclick="editTableRow({ 
                                      id: '<?php echo $row['id']; ?>', 
                                      table_name: '<?php echo $row['table_name']; ?>', 
                                      capacity: '<?php echo $row['capacity']; ?>'
                                  })"></i>
                                  <i id="primary" class="delete mdi mdi-close-circle-outline" onclick="table_delete({ 
                                      id: '<?php echo $row['id']; ?>', 
                                      table_name: '<?php echo $row['table_name']; ?>', 
                                      capacity: '<?php echo $row['capacity']; ?>'
                                  })">
                                  </i>
                                </div>
                              </td>
                            </tr>
                            <?php
                                                            $counter++; // Increment counter for the next iteration

                          }
                        }

                        $conn->close();
                        ?>

                      </tbody>
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
            <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright © cafe management
              2024
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
  <script>
    const table_delete = (data) => {
      const formData = new FormData(); // Create a new FormData object

      // Append data to the FormData object
      formData.append("id", data.id);

      const xhr = new XMLHttpRequest();

      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          // Handle the response as needed
          if (response.success) {
            // Reload the current page to reflect the changes

            const fetchSuccesfullyMessageDivId = document.getElementById('popup-message');
            fetchSuccesfullyMessageDivId.style.display = 'block';
            fetchSuccesfullyMessageDivId.innerHTML = 'Table record delete successfully';

            setTimeout(() => {
              fetchSuccesfullyMessageDivId.style.display = 'none';
              location.reload();
            }, 2000);
          } else {
            // Handle the error case if needed
          }
        }
      };

      xhr.open("POST", "delete_table.php", true);
      xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
      xhr.send(formData);
    }

    // Edit
    function editTableRow(data) {


      // Display the edit form
      document.getElementById('edit').style.display = 'block';
      document.getElementById('add').style.display = 'none';

      const id = document.getElementById('id');
      const table_name = document.getElementById('edit_table_name');
      const capicity = document.getElementById('edit_table_capacity');

      table_name.value = data.table_name;
      capicity.value = data.capacity;
      id.value = data.id;
    }

    const editForm = () => {
      const id = document.getElementById('id').value;
      console.log('id: ', id);
      const table_name = document.getElementById('edit_table_name').value;
      console.log('table_name: ', table_name);
      
      const capicity = document.getElementById('edit_table_capacity').value;
      console.log('capicity: ', capicity);
      


      formData.append("id", id);
      formData.append("table_name", table_name);
      formData.append("capacity", capacity);

      const xhr = new XMLHttpRequest();

      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            console.log('response: ', response);
            if (response.status === "success") {
              console.log("SUCCESS");
              // Update the message and display it
              const fetchSuccesfullyMessageDivId = document.getElementById('popup-message');
              fetchSuccesfullyMessageDivId.style.display = 'block';
              fetchSuccesfullyMessageDivId.innerHTML = response.message;

              // Hide the message after a delay (e.g., 5 seconds)
              setTimeout(() => {
                fetchSuccesfullyMessageDivId.style.display = 'none';
              }, 5000);

              // Optionally, you can reload the page here if needed
              // location.reload();
            } else {
              // Handle the error case if needed
              console.error("Update failed: " + response.message);
            }
          } else {
            // Handle non-200 status codes if needed
            console.error("HTTP request failed with status: " + xhr.status);
          }
        }
      };

      xhr.open("POST", "table_edit.php", true);
      xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
      xhr.send(formData);
    }

    setTimeout(function () {
      var successMessage = document.getElementById('success-message');
      if (successMessage) {
        successMessage.style.display = 'none';
      }
    }, 5000);

  </script>
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
  <script src="../assets/js/popup.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page -->
  <script src="../assets/js/dashboard.js"></script>
  <script src="../assets/js/todolist.js"></script>
  <!-- End custom js for this page -->
         <script>
  window.addEventListener("pageshow", function(event) {
    if (event.persisted || (window.performance && performance.navigation.type === 2)) {
      window.location.reload();
    }
  });
</script>
</body>

</html>