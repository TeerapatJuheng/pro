<?php
session_start();

if (isset($_GET['lang']) && $_GET['lang'] != "") {
    $_SESSION['lang'] = $_GET['lang'];
}

// ตั้งค่า default เป็นภาษาอังกฤษถ้ายังไม่มีการเลือกภาษา
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = "en";
}

// ตรวจสอบค่าภาษาและรวมไฟล์ภาษา
if ($_SESSION['lang'] == "en") {
    include "lang_en.php";
} else {
    include "lang_th.php";
}
?>
