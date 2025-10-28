<?php


session_start(); 
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Cafe Management</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

      <!-- Header Start -->
        <div class="container-fluid bg-dark px-0">
            <div class="row gx-0">
                <div class="col-lg-3 bg-dark d-none d-lg-block">
                    <a href="index.php"
                        class="navbar-brand w-100 h-100 m-0 p-0 d-flex align-items-center justify-content-center">
                        <h5 class="m-0 text-primary text-uppercase">Canvas Cafe</h5>
                    </a>
                </div>
                <div class="col-lg-9">
                    <nav class="navbar navbar-expand-lg bg-dark navbar-dark p-3 p-lg-0">
                        <a href="index.php" class="navbar-brand d-block d-lg-none">
                            <h1 class="m-0 text-primary text-uppercase">Canvas Cafe</h1>
                        </a>
                        <button type="button" class="navbar-toggler" data-bs-toggle="collapse"
                            data-bs-target="#navbarCollapse">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                            <div class="navbar-nav mr-auto py-0">
                                <a href="index.php" class="nav-item nav-link active">Home</a>
                                <a href="about.php" class="nav-item nav-link ">About</a>
                                <a href="service.php" class="nav-item nav-link">Services</a>
                                <a href="booking.php" class="nav-item nav-link link">Book Table</a>
                                <a href="items.php" class="nav-item nav-link">Our Menu</a>
                               <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle link" data-bs-toggle="dropdown">Status</a>
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
        <!-- Header End -->

        <!-- Login Form Start -->
        <div class="container-xxl py-5 mb-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase">Forgot Password</h6>
                </div>
                <div class="row g-4 mt-2">
                    <div class="col-lg-4 col-md-6 mx-auto wow fadeInUp" data-wow-delay="0.1s">
                        <div class="room-item shadow rounded overflow-hidden">
                            <div class="p-4">

                                <!-- Search Email -->
                                <div class="form my-4" id="s_email_container" style="display:block">
                                    <form name="search_email_form"
                                        class="d-flex flex-column justify-content-between gap-4">
                                        <div class="form-group">
                                            <input type="email" class="form-control" name="search_email"
                                                id="search_email" placeholder="Enter email">
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <button class="btn btn-sm mx-auto btn-primary rounded py-2 px-4"
                                                type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Password Change -->
                                <div class="form my-4" id="p_change_container" style="display:none">
                                    <form name="change_password_form"
                                        class="d-flex flex-column justify-content-between gap-4">
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="change_password"
                                                id="change_password" placeholder="Change Password">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="confirm_password"
                                                id="confirm_password" placeholder="Re-Enter Password">
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <button class="btn btn-sm mx-auto btn-primary rounded py-2 px-4"
                                                type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Start -->
<footer>
  <div class="container-fluid bg-dark text-light mt-5 py-5">
    <div class="container">
      <div class="row text-center text-md-start">

        <!-- Column 1 -->
        <div class="col-12 col-md-3 mb-4 mb-md-0">
          <h3 class="mb-3" style="color:#FEA116;">Quote</h3>
          <p style="font-size:20px; text-align:Justify;">
            Coffee & Friends <br>make the perfect <br>blend
                </p>
        </div>

        <!-- Column 2 -->
        <div class="col-12 col-md-3 mb-4 mb-md-0">
             <h3 class="mb-3" style="color:#FEA116;">Services</h3>
            <?php
            $conn = new mysqli("localhost", "root", "", "cafe_management");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql_services = "SELECT * FROM services";
            $result_items = $conn->query($sql_services);
            if ($result_items->num_rows > 0) {
                while ($row = $result_items->fetch_assoc()) {
                    $service = $row["service_Name"];
                    echo '<p style="font-size:20px;">' . $service . '</p>';
                }
            } else {
                echo "<p style='font-size:20px;'>'>No services found.</p>";
            }
            $conn->close();
            ?>
          
          
        </div>

        <!-- Column 3 -->
        <div class="col-12 col-md-3 mb-4 mb-md-0">
            <h3 class="mb-3" style="color:#FEA116;">Company</h3>
            <p style="font-size:20px;">
          <a class="d-block text-light mb-2" href="">About Us</a>
          <a class="d-block text-light mb-2" href="#">Contact Us</a>
          <a class="d-block text-light mb-2" href="#">Privacy Policy</a>
          <a class="d-block text-light" href="#">Terms & Conditions</a></p>
        </div>

        <!-- Column 4 -->
        <div class="col-12 col-md-3">
          <h3 class="mb-3" style="color:#FEA116;">Follow Us</h3>
          <p><i class="fa fa-map-marker-alt me-2"></i>123 Street, Gujarat, India</p>
          <p><i class="fa fa-phone-alt me-2"></i>+91 98765 43210</p>
          <p><i class="fa fa-envelope me-2"></i>canvascafe02@gmail.com</p>
          <a class="btn btn-outline-light btn-sm m-1" href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
          <a class="btn btn-outline-light btn-sm m-1" href="https://twitter.com/i/flow/signup"><i class="fab fa-twitter"></i></a>
          <a class="btn btn-outline-light btn-sm m-1" href="https://www.instagram.com/accounts/login/?hl=en"><i class="fab fa-instagram"></i></a>
          <a class="btn btn-outline-light btn-sm m-1" href="https://www.linkedin.com/login"><i class="fab fa-linkedin-in"></i></a>
        </div>

      </div>
    </div>
  </div>
</footer>
<!-- Footer End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    </div>

    <script>
        let search_email = '';

        document.addEventListener("DOMContentLoaded", function () {
            document.forms["search_email_form"].addEventListener("submit", function (e) {
                event.preventDefault();


                // Get references to the elements
                let s_email_container = document.getElementById('s_email_container');
                let p_change_container = document.getElementById('p_change_container');
                let email = document.getElementById('search_email').value;

                if (email !== '') {
                    const formData = new FormData(this);
                    const xhr = new XMLHttpRequest();

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            const data = JSON.parse(xhr.responseText);
                            search_email = data.email
                        }
                    };

                    xhr.open("POST", "search_email.php", true);
                    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
                    xhr.send(formData);

                    s_email_container.style.display = 'none';
                    p_change_container.style.display = 'block';

                } else {
                    s_email_container.style.display = 'block';
                    p_change_container.style.display = 'none';
                }

            })

        })
        // Change Password
        document.addEventListener("DOMContentLoaded", function () {
            // ... Your existing code ...

            document.forms["change_password_form"].addEventListener("submit", function (e) {
                e.preventDefault();

                // Get references to the password fields
                let changePasswordInput = document.getElementById('change_password');
                let confirmPasswordInput = document.getElementById('confirm_password');

                // Check if passwords match
                if (changePasswordInput.value !== confirmPasswordInput.value) {
                    // Display an error message if passwords don't match
                    alert("Passwords do not match.");
                    return;
                }

                // Get the new password
                let newPassword = changePasswordInput.value;

                // Use AJAX to send the new password to the server for updating
                console.log('email: ', search_email);
                const formData = new FormData(this);
                formData.append('search_email',search_email)
                const xhr = new XMLHttpRequest();

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            alert("Password updated successfully.");
                            window.location.href = "auth.php"
                            // Optionally, you can redirect the user or perform other actions here.
                        } else {
                            alert("Password update failed. Please try again later.");
                        }
                    }
                };

                xhr.open("POST", "update_password.php", true); // Replace with the actual URL for updating password
                xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
                xhr.send(formData);

                // Clear the password fields
                changePasswordInput.value = '';
                confirmPasswordInput.value = '';
            });
        });

    </script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>