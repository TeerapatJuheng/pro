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

    // Prepare the SELECT statement
    $stmt = $conn->prepare("SELECT product_id, product_name, product_type, product_details, product_price, product_img FROM tb_product WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    
    // Execute the statement
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Check if product exists
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            // Encode the image in base64 if it exists
            if ($product['product_img']) {
                $product['product_img'] = base64_encode($product['product_img']);
            }
            echo json_encode($product); // Return product details as JSON
        } else {
            echo json_encode(['error' => 'Product not found']); // Return error if no product found
        }
    } else {
        echo json_encode(['error' => 'Failed to execute query']); // Return error if query execution fails
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'No product ID provided']); // Return error if no ID is provided
}

// Close the database connection
$conn->close();
?>
