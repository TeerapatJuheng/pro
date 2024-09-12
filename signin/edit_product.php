<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your server connection file
include('../inc/server.php');

// Check if the database connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_type = $_POST['product_type'];
    $product_details = $_POST['product_details'];
    $product_price = $_POST['product_price'];
    $shop_id = $_SESSION['shop_id'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['image']['tmp_name']);

        $stmt = $conn->prepare("UPDATE tb_product SET product_img = ?, product_name = ?, product_type = ?, product_details = ?, product_price = ?, shop_id = ? WHERE product_id = ?");
        $stmt->bind_param("bsssiii", $image, $product_name, $product_type, $product_details, $product_price, $shop_id, $product_id);
        $stmt->send_long_data(0, $image);
    } else {

        $stmt = $conn->prepare("UPDATE tb_product SET product_name = ?, product_type = ?, product_details = ?, product_price = ?, shop_id = ? WHERE product_id = ?");
        $stmt->bind_param("sssiii", $product_name, $product_type, $product_details, $product_price, $shop_id, $product_id);
    }

    // Execute the statement
    if ($stmt->execute()) {
        echo "Product updated successfully.";
    } else {
        echo "Error updating product: " . $conn->error;
    }

    // Close the statement
    $stmt->close();

    // Redirect back to the product list page
    header("Location: store_shop.php"); // Replace with your actual product list page
    exit();
}
?>