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
    <title>Canvas Cafe</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">  

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
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
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


        <!-- Page Header Start -->
        <div class="container-fluid page-header mb-5 p-0" style="background-image: url(img/carousel-1.jpg);">
            <div class="container-fluid page-header-inner py-5">
                <div class="container text-center pb-5">
                    <h1 class="display-3 text-white mb-3 animated slideInDown">About Us</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center text-uppercase">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Pages</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">About</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Page Header End -->
        

       <!-- About Start -->
       <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <h6 class="section-title text-start text-primary text-uppercase">About Us</h6>
                    <h1 class="mb-4">Welcome to <span class="text-primary text-uppercase">Cafe Management</span></h1>
                    <p class="mb-4">where great taste meets cozy vibes! We serve delicious food and drinks in a relaxed, artistic atmosphere perfect for hanging out, working, or unwinding. Come in and make every moment memorable!</p>
                    <div class="row g-3 pb-4">
                        <div class="col-sm-4 wow fadeIn" data-wow-delay="0.1s">
                            <div class="border rounded p-1">
                                <div class="border rounded text-center p-4">
                                    <i class="fa fa-hotel fa-2x text-primary mb-2"></i>
                                    <h2 class="mb-1" data-toggle="counter-up">150</h2>
                                    <p class="mb-0">Our Items</p>
                                </div>
                            </div>
                        </div>                           
                        <div class="col-sm-4 wow fadeIn" data-wow-delay="0.5s">
                            <div class="border rounded p-1">
                                <div class="border rounded text-center p-4">
                                    <i class="fa fa-users fa-2x text-primary mb-2"></i>
                                    <h2 class="mb-1" data-toggle="counter-up">890</h2>
                                    <p class="mb-0">Customer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-6 text-end">
                            <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.1s" src="img/about-1.jpg" style="margin-top: 25%;">
                        </div>
                        <div class="col-6 text-start">
                            <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.3s" src="img/about-2.jpg">
                        </div>
                        <div class="col-6 text-end">
                            <img class="img-fluid rounded w-50 wow zoomIn" data-wow-delay="0.5s" src="img/about-3.jpg">
                        </div>
                        <div class="col-6 text-start">
                            <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.7s" src="img/about-4.jpg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

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

    <!-- JavaScript Libraries -->
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