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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_name = $_POST["service_name"];
    $service_desc = $_POST["service_desc"];
    // Database Connection
    $conn = new mysqli("localhost", "root", "", "cafe_management");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the service_name already exists
    $check_sql = "SELECT service_name FROM services WHERE service_Name = '$service_name'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Duplicate service_name found, show an error
        session_start();
        $_SESSION['alert_message'] = 'Error: Service are already exists.';
        header('Location:banner.php');
    } else {
        // Insert user data into the database
        $insert_sql = "INSERT INTO services (service_Name, service_des) VALUES ('$service_name', '$service_desc')";

        if ($conn->query($insert_sql) === TRUE) {
            session_start();
            $_SESSION['success_message'] = 'Service added successfully.';
            header('Location:banner.php');
        } else {
            echo "Error: " . $insert_sql . "<br>" . $conn->error;
        }

    }

    $conn->close();
}
?>
<html>
    <head>
        <script>
  window.addEventListener("pageshow", function(event) {
    if (event.persisted || (window.performance && performance.navigation.type === 2)) {
      window.location.reload();
    }
  });
</script>

</head></html>