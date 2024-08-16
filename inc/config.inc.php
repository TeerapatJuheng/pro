<?php

$menu1 = 'Management';
$menu2 = 'Log';
$ip_scanner = '192.168.1.101';

$mail_from = 'no_reply@arm-smartlocker.com';
$mail_host = 'mail.arm-smartlocker.com';
$mail_username = 'no_reply@arm-smartlocker.com';
$mail_password = 'kercol159852';

if ($_SESSION['role'] == 'Admin') {
    $delete_custome_button = '<center><a href="../management/customer_edit_delete.php?customer_id=<?= $row[' . 'customer_id' . '] ?>"
class="btn btn-danger btn-sm" onclick="return confirm(' . 'ยืนยันการลบข้อมูล !!' . ');">DELETE</a></center>';
    $delete_header = 'Delete';
} else {
    $delete_custome_button = '';
    $delete_header = '';
}
