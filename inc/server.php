<?php

$servername = "localhost";
$username = "root";
$password = "za0159753";
$dbname = "laundry";

// Create Connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed" . mysqli_connect_error());
}