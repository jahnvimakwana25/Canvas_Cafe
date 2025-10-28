<?php

session_start();
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : null;

// Database connection
$conn = new mysqli("localhost", "root", "", "cafe_management");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create a SQL query to fetch all items
$sql_query_items = "SELECT * FROM items";
$result_items = $conn->query($sql_query_items);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Canvas Cafe</title>
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
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
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
                                <a href="index.php" class="nav-item nav-link">Home</a>
                                <a href="about.php" class="nav-item nav-link">About</a>
                                <a href="service.php" class="nav-item nav-link">Services</a>
                                <a href="booking.php" class="nav-item nav-link active">Book Table</a>
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
                                <a href="logout.php" class="nav-item nav-link" id="logOut_link">Logout</a>
                                <?php else: ?>
                                <a href="auth.php" class="nav-item nav-link active" id="login_details">Login</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container-fluid page-header mb-5 p-0" style="background-image: url(img/carousel-1.jpg)">
            <div class="container-fluid page-header-inner py-5">
                <div class="container text-center pb-5">
                    <h1 class="display-3 text-white mb-3 animated slideInDown">
                        Billing
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center text-uppercase">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Pages</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">
                                Billing
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="main-container wow fadeIn" data-wow-delay="0.1s">
            <div class="sub-container mx-auto">
                <img class="img-fluid_img" src="img/carousel-2.jpg" alt="">
                <div class="billing-details">

                    <h4 class="text-white text-center my-3">Order List</h4>

                    <div class="col">
                        <div class="d-flex billing-header mt-5 mb-3 flex-row justify-content-between">
                            <span>#</span>
                            <span>Item Name</span>
                            <span>Price</span>
                        </div>
                        <div id="billing-detail">
                        </div>
                    </div>

                </div>
                <div class="d-flex mt-4 flex-row justify-content-between btn-action">
                    <button class="btn btn-sm mx-4 btn-dark rounded py-2 px-4" onclick="downloadBill()">Download
                        Bill</button>
                    <button class="btn btn-sm mx-4 btn-primary rounded py-2 px-4" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">Check Out</button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Checkout</h5>
                    </div>
                    <div class="modal-body">
                        <h6>Are you want sure checkout?</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                            onclick="confirmCheckout()">Yes</button>
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <div class="container-fluid bg-dark text-light mt-5 py-5">
                <div class="container">
                    <div class="row text-center text-md-start">

                        <div class="col-12 col-md-3 mb-4 mb-md-0">
                            <h3 class="mb-3" style="color:#FEA116;">Quote</h3>
                            <p style="font-size:20px; text-align:Justify;">
                                Coffee & Friends <br>make the perfect <br>blend
                            </p>
                        </div>

                        <div class="col-12 col-md-3 mb-4 mb-md-0">
                            <h3 class="mb-3" style="color:#FEA116;">Services</h3>
                            <?php
                                $conn_footer = new mysqli("localhost", "root", "", "cafe_management");
                                if ($conn_footer->connect_error) {
                                    die("Connection failed: " . $conn_footer->connect_error);
                                }
                                $sql_services = "SELECT * FROM services";
                                $result_services = $conn_footer->query($sql_services);
                                if ($result_services->num_rows > 0) {
                                    while ($row = $result_services->fetch_assoc()) {
                                        $service = $row["service_Name"];
                                        echo '<p style="font-size:20px;">' . $service . '</p>';
                                    }
                                } else {
                                    echo "<p style='font-size:20px;'>No services found.</p>";
                                }
                                $conn_footer->close();
                                ?>
                        </div>

                        <div class="col-12 col-md-3 mb-4 mb-md-0">
                            <h3 class="mb-3" style="color:#FEA116;">Company</h3>
                            <p style="font-size:20px;">
                                <a class="d-block text-light mb-2" href="">About Us</a>
                                <a class="d-block text-light mb-2" href="#">Contact Us</a>
                                <a class="d-block text-light mb-2" href="#">Privacy Policy</a>
                                <a class="d-block text-light" href="#">Terms & Conditions</a>
                            </p>
                        </div>

                        <div class="col-12 col-md-3">
                            <h3 class="mb-3" style="color:#FEA116;">Contact</h3>
                            <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, Gujarat, India</p>
                            <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+91 945 67890</p>
                            <p class="mb-2"><i class="fa fa-envelope me-3"></i>cafe@gmail.com</p>
                            <div class="d-flex pt-2">
                                <a class="btn btn-outline-light btn-social" href="https://www.twitter.com"><i
                                        class="fab fa-twitter"></i></a>
                                <a class="btn btn-outline-light btn-social" href="https://www.facebook.com"><i
                                        class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-outline-light btn-social" href="https://www.youtube.com"><i
                                        class="fab fa-youtube"></i></a>
                                <a class="btn btn-outline-light btn-social" href="https://www.linkedin.com"><i
                                        class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
        </footer>
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

        <script>
            const orderItem = JSON.parse(localStorage.getItem("Order Item"));
            const payment_status = JSON.parse(localStorage.getItem("Payment_Status"));
            let id = 1;
            const cartItem = document.getElementById('billing-detail');
            cartItem.innerHTML = '';
            orderItem.forEach((item) => {
                cartItem.innerHTML += `
                            <div class="d-flex billing-body mb-3 flex-row justify-content-between">
                                        <span>${id++}</span>
                                        <span>${item.item_name}(${item.quantity}x)</span>
                                        <span>₹${item.item_price}</span>
                            </div>
                        `
            })


            const confirmCheckout = () => {
                const formData = new FormData();
                formData.append('Email', payment_status.Email);
                formData.append('Payment_Id', payment_status.Payment_Id);
                formData.append('Table_no', payment_status.Table_no);
                formData.append('Total_Ammount', payment_status.Total_Ammount);
                formData.append('User_Name', payment_status.User_Name);
                let todatDate = new Date()
                let date = todatDate.toISOString().split('T')[0]
                formData.append('Date', date);

                fetch('checkout_db.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        // Proceed to redirect regardless of minor backend response issues
                        localStorage.removeItem('Order Item');
                        localStorage.removeItem('Paymanet Id');
                        localStorage.removeItem('Table_Entry');
                        localStorage.removeItem('rzp_checkout_anon_id');
                        localStorage.removeItem('rzp_device_id');
                        localStorage.removeItem('Payment_Status');
                        window.location.href = 'vieworder.php';
                    })
                    .catch(() => {
                        // Even if network/json parsing fails, send user to orders page
                        window.location.href = 'vieworder.php';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            function formatDate(date) {
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

                const day = days[date.getDay()];
                const dayOfMonth = date.getDate();
                const month = months[date.getMonth()];
                const year = date.getFullYear() % 100; // Get the last two digits of the year

                return `${day} ${dayOfMonth} ${month}'${year}`;
            }

            function downloadBill() {
                const orderItem = JSON.parse(localStorage.getItem("Order Item"));
                const payment_status = JSON.parse(localStorage.getItem("Payment_Status"));

                let todatDate = new Date();
                const formattedDate = formatDate(todatDate);

                const dataToSend = {
                    OrderItems: orderItem,
                    TotalAmount: payment_status.Total_Ammount,
                    CustomerName: payment_status.User_Name,
                    Email: payment_status.Email,
                    Date: formattedDate,
                };

                // Create a form and submit to generate_pdf.php so the browser navigates and downloads
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'generate_pdf.php';
                form.style.display = 'none';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'jsonData';
                input.value = JSON.stringify(dataToSend);
                form.appendChild(input);

                document.body.appendChild(form);
                form.submit();
            }

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

        <script src="js/main.js"></script>
</body>

</html>