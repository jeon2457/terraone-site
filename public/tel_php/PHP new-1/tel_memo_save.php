<?php
session_start();
require './php/auth_check.php';
require './php/db-connect-pdo.php';

$memo = $_POST['memo'] ?? '';
if (empty($memo)) {
    echo "<script>alert('메모를 입력해주세요'); history.back();</script>";
    exit;
}

// ✅ 전 회원에게 동일한 메모 업데이트
$stmt = $pdo->prepare("UPDATE tel SET memo = ?");
$stmt->execute([$memo]);

echo "<script>alert('전 회원에게 메모가 적용되었습니다.'); location.href='tel_edit.php';</script>";
