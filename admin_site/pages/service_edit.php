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
// Retrieve data from the POST request
$edit_ID = $_POST["edit_ID"];
$edit_name = $_POST["edit_name"];
$edit_desc = $_POST["edit_desc"];

$conn = new mysqli("localhost", "root", "", "cafe_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL update statement
$sql = "UPDATE services SET service_Name = ?, service_des = ? WHERE ID  = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $edit_name, $edit_desc, $edit_ID);

if ($stmt->execute()) { 
    session_start();
    $_SESSION['success_message_edit'] = 'Record updated successfully';

   // Add a unique query parameter to the redirect URL
   $redirect_url = 'banner.php?'.uniqid();
   header('Location: ' . $redirect_url);
   exit();
} else {
    // The update failed
    $response = array("status" => "error", "message" => "Error updating record: " . $stmt->error);
    echo json_encode($response);
}

// Close the database connection
$stmt->close();
$conn->close();

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