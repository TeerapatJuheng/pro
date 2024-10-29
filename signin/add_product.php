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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debugging output: Check the contents of $_FILES
    // echo "<pre>";
    // print_r($_FILES);
    // echo "</pre>";

    if (isset($_POST['add'])) {
        $productName = $_POST['product_name'];
        $productType = $_POST['product_type'];
        $productDetails = $_POST['product_details'];
        $productPrice = $_POST['product_price'];
        $shopId = $_SESSION['shop_id'];

        if (isset($_FILES['image'])) {
            if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['image']['tmp_name'];
                $fileName = $_FILES['image']['name'];
                $fileSize = $_FILES['image']['size'];
                $fileType = $_FILES['image']['type'];

                // Read the file content into a variable
                $imageData = file_get_contents($fileTmpPath);

                // Insert data into the database
                $stmt = $conn->prepare("INSERT INTO tb_product (product_img, product_name, product_type, product_details, product_price, shop_id) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("bssssi", $imageData, $productName, $productType, $productDetails, $productPrice, $shopId);
                $stmt->send_long_data(0, $imageData);

                if ($stmt->execute()) {
                    echo "<script>alert('Product added successfully.'); window.location.href='store_shop.php';</script>";
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "<script>alert('File upload error'); window.location.href='store_shop.php';</script>";
            }
        } else {
            echo "<script>alert('No file uploaded.'); window.location.href='store_shop.php';</script>";
        }
    }
}

// Close the connection
$conn->close();
