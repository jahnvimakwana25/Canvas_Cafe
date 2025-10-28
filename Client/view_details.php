<?php
session_start(); 
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['email'])) {
    header("Location: auth.php");
    exit;
}
$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : null;
// Database connection
$conn = new mysqli("localhost", "root", "", "cafe_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

include('config.php'); // Assuming config.php has BASE_URL defined

$item = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $item_id = intval($_GET['id']);

    // Create a SQL query to fetch the specific item
    $stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
    }
    $stmt->close();
}

// If item not found, you might want to redirect or show an error
if (!$item) {
    // Redirect to items page or show a "Not Found" message
    header("Location: items.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Canvas Cafe - <?php echo htmlspecialchars($item['item_name']); ?> Details</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="keywords" />
    <meta content="" name="description" />

    <link href="img/favicon.ico" rel="icon" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

    <link href="lib/animate/animate.min.css" rel="stylesheet" />
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet" />
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <link href="css/bootstrap.min.css" rel="stylesheet" />

    <link href="css/style.css" rel="stylesheet" />

    <style>
        /* Custom CSS for the main content of view_detail.php */
        .item-detail-container {
            padding: 60px 0;
            background: #f8f9fa; /* Light background for the section */
        }

        .item-detail-card {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .item-detail-img {
            width: 100%;
            height: auto;
            max-height: 400px; /* Limit image height */
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }

        .item-detail-content {
            padding: 30px;
        }

        .item-detail-content h2 {
            font-size: 2.5rem;
            color: #0F172B; /* Dark blue from your theme */
            margin-bottom: 15px;
        }

        .item-detail-content .price {
            font-size: 2rem;
            color: #FEA116; /* Primary color from your theme */
            font-weight: bold;
            margin-bottom: 20px;
        }

        .item-detail-content .description {
            font-size: 1.1rem;
            color: #6C757D; /* Gray text */
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .item-detail-content .features ul {
            list-style: none;
            padding: 0;
            margin-bottom: 30px;
        }

        .item-detail-content .features ul li {
            margin-bottom: 10px;
            font-size: 1rem;
            color: #343A40;
        }

        .item-detail-content .features ul li i {
            color: #FEA116; /* Primary color */
            margin-right: 10px;
        }

        .item-detail-actions .btn {
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 5px;
        }

        .item-detail-actions .btn-primary {
            background-color: #FEA116;
            border-color: #FEA116;
        }

        .item-detail-actions .btn-primary:hover {
            background-color: #e09010;
            border-color: #e09010;
        }

        .item-detail-actions .btn-dark {
            background-color: #0F172B;
            border-color: #0F172B;
        }

        .item-detail-actions .btn-dark:hover {
            background-color: #0a0e1a;
            border-color: #0a0e1a;
        }
    </style>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
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
        <div class="container-fluid page-header mb-5 p-0" style="background-image: url(img/carousel-1.jpg)">
            <div class="container-fluid page-header-inner py-5">
                <div class="container text-center pb-5">
                    <h1 class="display-3 text-white mb-3 animated slideInDown">
                        <?php echo htmlspecialchars($item['item_name']); ?>
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center text-uppercase">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="items.php">Our Menu</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">
                                <?php echo htmlspecialchars($item['item_name']); ?>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container-xxl py-5 item-detail-container">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-8 offset-lg-2 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="item-detail-card">
                            <img class="img-fluid item-detail-img" src="<?php echo BASE_URL . htmlspecialchars($item['item_main_image']); ?>"
                                alt="<?php echo htmlspecialchars($item['item_name']); ?>" />
                            <div class="item-detail-content">
                                <h2><?php echo htmlspecialchars($item['item_name']); ?></h2>
                                <p class="price">₹ <?php echo htmlspecialchars($item['item_price']); ?></p>
                                <p class="captions"><?php echo nl2br(htmlspecialchars($item['item_captions'])); ?></p>

                                <div class="features mb-4">
                                    <ul>
                                        <li><i class="fa fa-utensils"></i> Captions: <?php echo htmlspecialchars($item['item_captions']); ?></li>
                                        <li><i class="fa fa-star"></i> Rating: 5/5 (Example)</li>
                                    </ul>
                                </div>

                                <div class="d-flex justify-content-start item-detail-actions">
                                    <a class="btn btn-primary rounded py-2 px-4 me-3" href="booking_order.php?item_id=<?php echo htmlspecialchars($item['id']); ?>">order Now</a>
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

        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

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

    <script src="js/main.js"></script>
</body>

</html>