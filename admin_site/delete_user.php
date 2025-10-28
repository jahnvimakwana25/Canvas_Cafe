<?php
session_start();
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if (!isset($_SESSION['admin_email'])) {
    header("Location: ../index.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $conn = new mysqli("localhost", "root", "", "cafe_management");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the DELETE query
    $sql = "DELETE FROM services WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die('Error in preparing the DELETE statement: ' . $conn->error);
    }

    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        // Record deleted successfully
        $response = array('success' => true, 'message' => 'Record deleted successfully');
        echo json_encode($response);
    } else {
        // Error occurred while deleting the record
        $response = array('success' => false, 'message' => 'Error deleting record: ' . $conn->error);
        echo json_encode($response);
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid request
    $response = array('success' => false, 'message' => 'Invalid request');
    echo json_encode($response);
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
</head>
</html>