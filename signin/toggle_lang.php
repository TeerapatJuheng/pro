<?php
session_start();

// ตรวจสอบภาษาปัจจุบัน
if (!isset($_SESSION['lang']) || $_SESSION['lang'] == 'en') {
    $_SESSION['lang'] = 'th';
} else {
    $_SESSION['lang'] = 'en';
}

// กลับไปยังหน้าเดิม
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>
