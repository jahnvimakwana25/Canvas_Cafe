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
// Database connection
$conn = new mysqli("localhost", "root", "", "cafe_management");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $itemId = $_POST['item_id'];

    // Prepare and execute a DELETE SQL query for the 'items' table
    $deleteItemQuery = "DELETE FROM items WHERE id = ?";
    $stmtItem = $conn->prepare($deleteItemQuery);
    $stmtItem->bind_param("i", $itemId);
   

    // Perform both DELETE operations in a transaction
    $conn->autocommit(FALSE);

    $success = true;

    if (!$stmtItem->execute()) {
        $success = false;
    }

    if ($success) {
        $conn->commit();
        echo "success"; // Both items and related gallery images deleted successfully
    } else {
        $conn->rollback();
        echo "error"; // Error occurred while deleting
    }

    // Close the database connection
    $stmtItem->close();
} else {
    echo "error"; // Invalid request or missing 'item_id'
}

// Close the database connection
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