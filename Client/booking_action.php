<?php
session_start();
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['email'])) {
    header("Location:auth.php");
    exit;
}
$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : null;
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "cafe_management");
if ($conn->connect_error) {
    echo json_encode(["error" => "DB Connection failed"]);
    exit();
}

$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
$members = intval($_POST['member'] ?? 0);

if (!$date || !$time || $members <= 0) {
    echo json_encode([]);
    exit();
}

// The core change is in the WHERE clause below
$sql = "SELECT te.id, te.table_name, te.capacity
        FROM table_entry te
        WHERE te.capacity = ? AND te.IsActive = 1
        AND te.id NOT IN (
            SELECT ts.table_id
            FROM table_status ts
            WHERE ts.booking_date = ? AND ts.booking_time = ?
        )
        ORDER BY te.capacity ASC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => $conn->error]);
    exit();
}

$stmt->bind_param("iss", $members, $date, $time);
$stmt->execute();
$result = $stmt->get_result();

$tables = [];
while ($row = $result->fetch_assoc()) {
    $tables[] = [
        'id' => $row['id'],
        'name' => $row['table_name'],
        'capacity' => $row['capacity']
    ];
}

$stmt->close();
$conn->close();

echo json_encode($tables);
?>