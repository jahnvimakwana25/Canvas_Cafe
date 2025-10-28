<?php
session_start();

// Prevent browser caching
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Check admin session
if (!isset($_SESSION['admin_email'])) {
    header("Location: ../index.php");
    exit;
}

// Retrieve POST data
$id = $_POST["id"];
$table_name = $_POST["table_name"];
$capacity = $_POST["capacity"]; // keep consistent with your form input

// Database connection
$conn = new mysqli("localhost", "root", "", "cafe_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Check for duplicate table_name (except current id)
$check_sql = "SELECT id FROM table_entry WHERE table_name = ? AND id != ?";
$stmt_check = $conn->prepare($check_sql);
$stmt_check->bind_param("si", $table_name, $id);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    $_SESSION['edit_message'] = "Error: Table name already exists.";
    header("Location: table.php");
    exit;
}

// Update the table_entry
$sql = "UPDATE table_entry SET table_name = ?, capacity = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $table_name, $capacity, $id);

if ($stmt->execute()) {
    $_SESSION['edit_message'] = "Record updated successfully.";
} else {
    $_SESSION['edit_message'] = "Error updating record: " . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: table.php");
exit;
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