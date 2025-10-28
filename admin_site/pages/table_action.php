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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $table_name = $_POST["table_name"];
    $capacity = $_POST["capacity"];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "cafe_management");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if table_name already exists
    $check_sql = "SELECT id FROM table_entry WHERE table_name = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $table_name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Duplicate found
        $_SESSION['alert_message'] = 'Error: Table name already exists.';
        header('Location: table.php');
        exit;
    } else {
        // Insert into table_entry
        $insert_sql = "INSERT INTO table_entry (table_name, capacity, IsActive) VALUES (?, ?, 1)";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("si", $table_name, $capacity);
        if ($stmt_insert->execute()) {
            $table_id = $stmt_insert->insert_id; // Get the inserted table's ID

            // Insert into table_status
            $insert_status = "INSERT INTO table_status (table_id, status, booking_date, booking_time, booking_id) VALUES (?, 'available', NULL, NULL, NULL)";
            $stmt_status = $conn->prepare($insert_status);
            $stmt_status->bind_param("i", $table_id);
            $stmt_status->execute();

            $_SESSION['alert_message'] = 'Table added successfully.';
            header('Location: table.php');
            exit;
        } else {
            $_SESSION['alert_message'] = 'Error: Could not add table.';
            header('Location: table.php');
            exit;
        }
    }

    $stmt->close();
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
</head>
</html>
