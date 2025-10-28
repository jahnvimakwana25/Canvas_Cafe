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

// Ensure all session variables are set or have a default value
$userId = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$userName = isset($_SESSION['name']) ? $_SESSION['name'] : null;
$userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : null;
$tableName = isset($_SESSION['table_name']) ? $_SESSION['table_name'] : 'Table_17'; // Default table name

include('config.php');

// Database connection
$conn = new mysqli("localhost", "root", "", "cafe_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all items
$sql_query_items = "SELECT * FROM items";
$result_items = $conn->query($sql_query_items);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Canvas Cafe - Order Now</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/order_style.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <style>
        .item-card { transition: transform 0.3s, box-shadow 0.3s; }
        .item-card:hover { transform: scale(1.03); box-shadow: 0 6px 20px rgba(0,0,0,0.25); }
        .order-btn { background-color:#FEA116; color:#fff; border:none; }
        .order-btn:hover { background-color:#e5940f; }
        .item-image { height:200px; object-fit:cover; width:100%; border-radius:10px; }
        .cart-section { background:#f8f9fa; padding:20px; border-radius:10px; }
        .cart-item { border-bottom:1px dashed #ccc; padding:10px 0; }
        #cart_items { min-height: 50px; }
        .my-order-container {
            background-color: #f8f9fa;
            padding: 2.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 3rem;
        }
        .my-order-heading {
            color: #333;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 2rem;
            border-bottom: 3px solid #FEA116;
            display: inline-block;
            padding-bottom: 0.5rem;
        }
        .order-item-card {
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .order-item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        .item-image-container {
            width: 70px;
            height: 70px;
            overflow: hidden;
            flex-shrink: 0;
        }
        .item-image-container img {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }
        .item-name {
            font-size: 1.1rem;
            color: #495057;
            font-weight: 500;
        }
        .item-price {
            font-size: 1.1rem;
            color: #FEA116;
            font-weight: bold;
        }
        .remove-item-btn {
            width: 30px;
            height: 30px;
            font-weight: bold;
            border: none;
            background-color: #dc3545;
            color: white;
            transition: background-color 0.2s;
            font-size: 1rem;
            line-height: 1;
        }
        .remove-item-btn:hover {
            background-color: #c82333;
        }
        .order-summary {
            border-top: 2px dashed #e9ecef !important;
        }
        .total-label, .total-price {
            font-weight: 700;
            font-size: 1.3rem;
            color: #333;
        }
        .total-price {
            color: #FEA116;
        }
        .order-now-btn {
            background-color: #FEA116;
            border-color: #FEA116;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
            transition: background-color 0.2s;
        }
        .order-now-btn:hover {
            background-color: #e09214;
            border-color: #e09214;
        }
    </style>
</head>
<body>
<div class="container-fluid bg-dark px-0">
    <div class="row gx-0">
        <div class="col-lg-3 bg-dark d-none d-lg-block">
            <a href="index.php" class="navbar-brand w-100 h-100 m-0 p-0 d-flex align-items-center justify-content-center">
                <h5 class="m-0 text-primary text-uppercase">Canvas Cafe</h5>
            </a>
        </div>
        <div class="col-lg-9">
            <nav class="navbar navbar-expand-lg bg-dark navbar-dark p-3 p-lg-0">
                <a href="index.php" class="navbar-brand d-block d-lg-none">
                    <h1 class="m-0  text-uppercase">Canvas Cafe</h1>
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                    <div class="navbar-nav mr-auto py-0">
                        <a href="index.php" class="nav-item nav-link active">Home</a>
                        <a href="about.php" class="nav-item nav-link">About</a>
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
                        <?php if(isset($_SESSION['name']) && !empty($_SESSION['name'])): ?>
                            <span class="nav-item nav-link active" id="customer_name">
                                <p>Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</p>
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
<div class="container py-5">
    <h2 class="text-center mb-5">Order Your Favorite Items</h2>
    <div class="row g-4">
        <?php if ($result_items->num_rows > 0):
            while ($row = $result_items->fetch_assoc()):
                $id = $row['id'];
                $item_name = $row['item_name'];
                $item_price = $row['item_price'];
                $item_image = BASE_URL . $row['item_main_image'];
                $item_captions = $row['item_captions'];
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="card item-card p-3 h-100 text-center">
                <img src="<?php echo $item_image; ?>" class="item-image mb-3" alt="<?php echo $item_name; ?>">
                <h5><?php echo $item_name; ?></h5>
                <p><?php echo $item_captions; ?></p>
                <p class="fw-bold">Price: ₹<span class="item-price"><?php echo $item_price; ?></span></p>
                <div class="d-flex justify-content-center gap-2 input-group">
                    <input type="number" class="form-control item-qty" placeholder="Qty" min="1" value="1" style="width:80px;">
                    <input type="text" class="form-control item-notes" placeholder="Notes" style="width:140px;">
                </div>
                <button class="btn order-btn w-100 mt-2 add-to-cart" 
                    data-id="<?php echo $id; ?>" 
                    data-name="<?php echo $item_name; ?>" 
                    data-price="<?php echo $item_price; ?>"
                    data-image="<?php echo $item_image; ?>">
                    Add to Cart
                </button>
            </div>
        </div>
        <?php endwhile; else: ?>
            <p class="text-center">No items available at the moment.</p>
        <?php endif; ?>
    </div>

    <div class="container my-order-container">
        <h2 class="my-order-heading text-center">My Order</h2>
        <div class="order-items-list" id="cart_items">
            <p class="text-muted text-center" id="cart-placeholder">Your cart is empty. Add some items! 🛒</p>
        </div>
        <div class="order-summary mt-4 pt-3 border-top">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="total-label m-0">Total:</h4>
                <h4 class="total-price m-0">₹<span id="total_price">0.00</span></h4>
            </div>
            <div class="text-center mt-3">
                <button class="btn btn-primary btn-lg w-100 order-now-btn" id="place_order_btn" disabled>ORDER NOW</button>
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
                    <p style="font-size:20px; text-align:Justify;">Coffee & Friends <br>make the perfect <br>blend</p>
                </div>
                <div class="col-12 col-md-3 mb-4 mb-md-0">
                    <h3 class="mb-3" style="color:#FEA116;">Services</h3>
                    <?php
                    $conn_services = new mysqli("localhost", "root", "", "cafe_management");
                    if ($conn_services->connect_error) {
                        die("Connection to services failed: " . $conn_services->connect_error);
                    }
                    $sql_services = "SELECT * FROM services";
                    $result_services = $conn_services->query($sql_services);
                    if ($result_services->num_rows > 0) {
                        while ($row = $result_services->fetch_assoc()) {
                            echo '<p style="font-size:20px;">' . $row["service_Name"] . '</p>';
                        }
                    } else { echo "<p style='font-size:20px;'>No services found.</p>"; }
                    $conn_services->close();
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
</footer>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

<script>
    let cart = [];
    
    // Get user data from PHP variables
    const name = "<?php echo $userName; ?>";
    const email = "<?php echo $userEmail; ?>";
    const userId = "<?php echo $userId; ?>";
    const tableName = "<?php echo $tableName; ?>";

    document.addEventListener('DOMContentLoaded', () => {
        const cartItemsContainer = document.getElementById('cart_items');
        const totalPriceSpan = document.getElementById('total_price');
        const placeOrderBtn = document.getElementById('place_order_btn');

        const updateCartDisplay = () => {
            cartItemsContainer.innerHTML = '';
            let total = 0;
            if (cart.length === 0) {
                cartItemsContainer.innerHTML = `<p class="text-muted text-center" id="cart-placeholder">Your cart is empty. Add some items! 🛒</p>`;
                placeOrderBtn.disabled = true;
            } else {
                placeOrderBtn.disabled = false;
                cart.forEach(item => {
                    const itemTotal = item.price * item.qty;
                    total += itemTotal;
                    const itemDiv = document.createElement('div');
                    itemDiv.classList.add('order-item-card', 'd-flex', 'align-items-center', 'justify-content-between', 'mb-3', 'p-3', 'rounded', 'shadow-sm');
                    itemDiv.innerHTML = `
                        <div class="item-image-container me-3">
                            <img src="${item.image}" alt="${item.name}" class="img-fluid rounded">
                        </div>
                        <div class="item-details flex-grow-1">
                            <h5 class="item-name m-0">${item.name} (${item.qty})</h5>
                            ${item.notes ? `<p class="text-muted m-0">${item.notes}</p>` : ''}
                        </div>
                        <div class="item-actions d-flex align-items-center">
                            <span class="item-price me-3">₹${(itemTotal).toFixed(2)}</span>
                            <button class="btn btn-danger btn-sm rounded-circle remove-item-btn" data-id="${item.id}">X</button>
                        </div>
                    `;
                    cartItemsContainer.appendChild(itemDiv);
                });
            }
            totalPriceSpan.textContent = total.toFixed(2);
        };

        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', (event) => {
                const card = event.target.closest('.item-card');
                const id = card.querySelector('.add-to-cart').dataset.id;
                const name = card.querySelector('.add-to-cart').dataset.name;
                const price = parseFloat(card.querySelector('.add-to-cart').dataset.price);
                const image = card.querySelector('.add-to-cart').dataset.image;
                const qty = parseInt(card.querySelector('.item-qty').value, 10);
                const notes = card.querySelector('.item-notes').value;

                if (isNaN(qty) || qty < 1) {
                    alert('Please enter a valid quantity.');
                    return;
                }

                const existingItem = cart.find(item => item.id === id);
                if (existingItem) {
                    existingItem.qty += qty;
                } else {
                    cart.push({ id, name, price, qty, notes, image });
                }

                updateCartDisplay();
            });
        });

        document.getElementById('cart_items').addEventListener('click', (event) => {
            if (event.target.classList.contains('remove-item-btn')) {
                const idToRemove = event.target.dataset.id;
                cart = cart.filter(item => item.id !== idToRemove);
                updateCartDisplay();
            }
        });

        placeOrderBtn.addEventListener('click', async () => {
            if (cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }
            
            placeOrderBtn.disabled = true;
            placeOrderBtn.textContent = 'Processing...';

            try {
                // 1. First store the order data to get an order ID 
                const orderData = await _storeOrderData();
                
                if (!orderData || !orderData.success) {
                    throw new Error('Failed to create order. Please try again.');
                }
                
                // 2. Process payment with the order ID 
                const paymentData = await placeOrder(totalPriceSpan.textContent, orderData.order_id);
                
                // 3. Store payment record in database 
                await _storePaymentData({
                    order_id: orderData.order_id, 
                    total_amount: totalPriceSpan.textContent, 
                    paymentId: paymentData.paymentId, 
                    razorpay_order_id: paymentData.razorpay_order_id, 
                    razorpay_signature: paymentData.razorpay_signature 
                });
                
                // 4. Persist order items for billing page and redirect
                const orderItemsForBill = cart.map(it => ({
                    item_name: it.name,
                    quantity: it.qty,
                    item_price: it.price
                }));
                localStorage.setItem('Order Item', JSON.stringify(orderItemsForBill));

                cart = [];
                updateCartDisplay();
                alert('Payment successful!');
                window.location.href = 'billing&checkout.php';
                
            } catch (error) {
                console.error('Payment Error:', {
                    error: error,
                    message: error.message,
                    stack: error.stack
                });
                alert('Payment failed: ' + (error.message || 'Unknown error occurred. Please check console for details.'));
                placeOrderBtn.disabled = false;
                placeOrderBtn.textContent = 'ORDER NOW';
            }

            updateCartDisplay();
        });

        const createRazorpayOrder = async (amount, orderId) => {
            try {
                const response = await fetch('create_razorpay_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        amount: amount,
                        order_id: orderId,
                        currency: 'INR'
                    })
                });
                
                const data = await response.json();
                if (!data.id) {
                    throw new Error(data.error || 'Failed to create Razorpay order');
                }
                return data.id;
            } catch (error) {
                console.error('Error creating Razorpay order:', error);
                throw error;
            }
        };

        const placeOrder = (payableAmount, orderId) => {
            return new Promise(async (resolve, reject) => {
                try {
                    const paymentAmount = Math.round(Number(payableAmount) * 100);

                    if (isNaN(paymentAmount) || paymentAmount <= 0) {
                        throw new Error("Invalid payment amount");
                    }

                    if (typeof Razorpay === "undefined") {
                        throw new Error("Razorpay payment gateway is not loaded. Please try again.");
                    }

                    const razorpayOrderId = await createRazorpayOrder(paymentAmount, orderId);

                    console.log(paymentAmount.toString(), 'paymentAmount.toString()')
                    const options = {
                        key: "rzp_test_RGNKMSuRsmumYS",
                        amount: paymentAmount.toString(),
                        currency: "INR",
                        name: "Canvas Cafe",
                        description: `Order #${orderId}`,
                        order_id: razorpayOrderId,
                        handler: function (response) {
                            try {
                                if (!response.razorpay_payment_id) {
                                    throw new Error("No payment ID in response");
                                }

                                const obj = {
                                    total_amount: payableAmount,
                                    paymentId: response.razorpay_payment_id,
                                    razorpay_order_id: response.razorpay_order_id || razorpayOrderId,
                                    razorpay_signature: response.razorpay_signature || ""
                                };

                                console.log("✅ Payment successful:", obj);
                                resolve(obj);
                            } catch (err) {
                                console.error("❌ Error processing payment response:", err);
                                reject(new Error("Error processing payment: " + err.message));
                            }
                        },
                        prefill: {
                            name: name || "Guest User",
                            email: email || "guest@example.com",
                        },
                        modal: {
                            ondismiss: function () {
                                reject(new Error("Payment was closed by the user"));
                            }
                        },
                        theme: {
                            color: "#FEA116"
                        }
                    };

                    console.log("Opening Razorpay checkout with options:", {
                        key: options.key,
                        amount: options.amount,
                        order_id: options.order_id,
                        currency: options.currency
                    });

                    console.log(options, 'options')

                    const rzp1 = new Razorpay(options);

                    rzp1.on("payment.failed", function (response) {
                        console.error("❌ Payment failed:", response.error);
                        reject(new Error(response.error.description || "Payment failed"));
                    });

                    rzp1.open();

                } catch (error) {
                    console.error("❌ Payment error:", error);
                    reject(error);
                }
            });
        }

        const _storeOrderData = async () => {
            try {
                const response = await fetch('ordernow.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        items: cart,
                        customer_name: name,
                        email: email,
                        user_id: userId,
                        table_number: tableName
                    }),
                });
                
                const data = await response.json();
                if (!data.success) {
                    throw new Error(data.message || 'Failed to store order data');
                }
                return data;
            } catch (error) {
                console.error('Error storing order:', error);
                throw error;
            }
        }

        const _storePaymentData = async (paymentData) => {
            try {
                const response = await fetch('payment_db.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(paymentData)
                });
                
                const result = await response.json();
                
                if (!result.success) {
                    throw new Error(result.message || 'Failed to store payment data');
                }
                
                const paymentStatus = {
                    "User_Name": name,
                    "Email": email,
                    "Table_no": tableName,
                    "Payment_Id": paymentData.paymentId,
                    "Total_Ammount": paymentData.total_amount 
                };
                
                localStorage.setItem("Payment_Status", JSON.stringify(paymentStatus));
                return result;
                
            } catch (error) {
                console.error('Error storing payment:', error);
                throw error;
            }
        }
    });
</script>
</body>
</html>