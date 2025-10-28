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


$item_name = $_POST["item_name"];
$item_price = $_POST["item_price"];
$item_main_image = $_FILES["item_main_image"]["name"];
$item_captions = $_POST["item_captions"];
$item_category = $_POST["item_category"];

$target_dir = "uploads/";

// Check if the directory exists, and if not, create it
if (!file_exists($target_dir)) {
    if (!mkdir($target_dir, 0777, true)) {
        die('Failed to create directory: ' . $target_dir);
    }
}

$target_file = $target_dir . basename($_FILES["item_main_image"]["name"]);

if (move_uploaded_file($_FILES["item_main_image"]["tmp_name"], $target_file)) {
    // Insert data into the items table
    $sql = "INSERT INTO items (item_name, item_price, item_main_image, item_captions, item_category) 
            VALUES ('$item_name', $item_price, '$item_main_image', '$item_captions', '$item_category')";

    if ($conn->query($sql) === TRUE) {
        $item_id = $conn->insert_id; // Get the auto-generated item_id

        // Handle multiple gallery images
        // $gallery_image_names = array();
        // $item_gallery_images = $_FILES["item_gallery_image"];
        // $num_files = count($item_gallery_images['name']);

        // for ($i = 0; $i < $num_files; $i++) {
        //     $gallery_image_name = basename($item_gallery_images["name"][$i]);
        //     $target_gallery_file = $target_dir . $gallery_image_name;

        //     if (move_uploaded_file($item_gallery_images["tmp_name"][$i], $target_gallery_file)) {
        //         $gallery_image_names[] = $gallery_image_name;

        //         // Insert gallery image names into the item_gallery table
        //         $sql_gallery = "INSERT INTO item_gallery (item_id, gallery_image_name) 
        //                         VALUES ($item_id, '$gallery_image_name')";
        //         if (!$conn->query($sql_gallery)) {
        //             echo "Error inserting gallery image: " . $conn->error;
        //         }
        //     } else {
        //         echo "Error uploading gallery image " . ($i + 1) . ".";
        //     }
        // }

        echo "Item added successfully.";
        header('location:items.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Error uploading main image.";
}

// Close the database connection
$conn->close();s
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