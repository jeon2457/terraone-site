<?php
session_start();
require './php/db-connect-pdo.php';

if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 10) {
    echo "<script>alert('관리자만 접근할 수 있습니다.'); location.href='login.php';</script>";
    exit;
}

$idx = $_POST['idx'];
$name = trim($_POST['name']);
$tel = trim($_POST['tel']);
$addr = trim($_POST['addr']);
$remark = trim($_POST['remark']);
$sms = trim($_POST['sms']);
$sms_2 = trim($_POST['sms_2']);
$level = trim($_POST['level']);

$stmt = $pdo->prepare("UPDATE tel SET name=?, tel=?, addr=?, remark=?, sms=?, sms_2=?, level=?  WHERE idx=?");
$stmt->execute([$name, $tel, $addr, $remark, $sms, $sms_2, $level, $idx]);

echo "<script>
        alert('회원정보가 수정되었습니다.');
        location.href='tel_edit.php';
      </script>";
?>
