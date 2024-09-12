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

// Check if product_id is set in the query string
if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']); // Ensure the ID is an integer

    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM tb_product WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Product deleted successfully.";
    } else {
        echo "Error deleting product: " . $conn->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "No product ID specified.";
}

// Close the database connection
$conn->close();

// Redirect back to the product list page (adjust the URL as necessary)
header("Location: store_shop.php"); // Replace with your actual product list page
exit();
?>