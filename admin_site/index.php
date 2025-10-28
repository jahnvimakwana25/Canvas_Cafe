<?php
session_start();
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


$email = "admin@gmail.com";  // Email
$password = "admin123";      // Password
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_email = $_POST["email"];
    $input_password = $_POST["password"];

    if ($input_email === $email && $input_password === $password) {
        // Store session
        $_SESSION['admin_email'] = $email;

        // Redirect to dashboard
        header("Location: pages/dashboard.php");
        exit;
    } else {
        $error_message = 'Invalid email or password. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin</title>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5">

                <?php if (!empty($error_message)): ?>
                  <h6 class="font-weight-bold alert-danger p-3"><?php echo $error_message; ?></h6>
                <?php endif; ?>

                <div class="brand-logo">
                  <h2 class="link-primary">Cafe Management</h2>
                </div>
                <h6>Admin Site</h6>
                <form class="pt-3" method="POST">
                  <div class="form-group">
                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" required>
                  </div>
                  <div class="form-group">
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
                  </div>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                  </div>                                
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
          <script>
  window.addEventListener("pageshow", function(event) {
    if (event.persisted || (window.performance && performance.navigation.type === 2)) {
      window.location.reload();
    }
  });
</script>
  </body>
</html>
