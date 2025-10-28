<?php
session_start(); 

// Prevent browser caching
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : null;

// Database connection
$conn = new mysqli("localhost", "root", "", "cafe_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch services
$sql_services = "SELECT * FROM services";
$result_services = $conn->query($sql_services);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Canvas Cafe - Services</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container-xxl bg-white p-0">

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
                <h1 class="display-3 text-white mb-3 animated slideInDown">Services</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Services</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Services Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h6 class="section-title text-center text-primary text-uppercase">Our Services</h6>
                <h1>Explore Our <span class="text-primary text-uppercase">Services</span></h1>
            </div>
            <div class="row g-4">
                <?php if($result_services->num_rows > 0): ?>
                    <?php while($row = $result_services->fetch_assoc()): ?>
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.<?= $row['ID']; ?>s">
                            <div class="service-item rounded">
                                <div class="service-icon bg-transparent border rounded p-1">
                                    <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                        <i class="fa fa-hotel fa-2x text-primary"></i>
                                    </div>
                                </div>
                                <h5 class="mb-3"><?= htmlspecialchars($row['service_Name']) ?></h5>
                                <p class="text-body mb-0"><?= htmlspecialchars($row['service_des']) ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No services found in the database.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Services End -->

     <!-- Footer Start -->
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
                        $sql_services = "SELECT * FROM services";
                        $result_items = $conn->query($sql_services);
                        if ($result_items->num_rows > 0) {
                            while ($row = $result_items->fetch_assoc()) {
                                echo '<p style="font-size:20px;">' . $row["service_Name"] . '</p>';
                            }
                        } else {
                            echo "<p style='font-size:20px;'>No services found.</p>";
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
                            <a class="d-block text-light" href="#">Terms & Conditions</a>
                        </p>
                    </div>
                    <!-- Column 4 -->
                    <div class="col-12 col-md-3">
                        <h3 class="mb-3" style="color:#FEA116;">Follow Us</h3>
                        <p><i class="fa fa-map-marker-alt me-2"></i>123 Street, Gujarat, India</p>
                        <p><i class="fa fa-phone-alt me-2"></i>+91 98765 43210</p>
                        <p><i class="fa fa-envelope me-2"></i>canvascafe02@gmail.com</p>
                        <a class="btn btn-outline-light btn-sm m-1" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-sm m-1" href="#"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-sm m-1" href="#"><i class="fab fa-instagram"></i></a>
                        <a class="btn btn-outline-light btn-sm m-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
