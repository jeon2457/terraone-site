<?php
session_start();
require './php/db-connect-pdo.php';

if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 10) {
    echo "<script>alert('관리자만 접근할 수 있습니다.'); location.href='login.php';</script>";
    exit;
}

if (!isset($_POST['edit_id'])) {
    echo "<script>alert('삭제할 회원을 선택하세요.'); history.back();</script>";
    exit;
}

$idx = $_POST['edit_id'];
$stmt = $pdo->prepare("DELETE FROM tel WHERE idx = ?");
$stmt->execute([$idx]);

echo "<script>
        alert('회원이 삭제되었습니다.');
        location.href='tel_edit.php';
      </script>";
?>
