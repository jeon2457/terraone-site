<?php
session_start();
require './php/db-connect-pdo.php'; // PDO 연결

// 관리자 검증
if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 10) {
    echo "<script>alert('관리자만 접근할 수 있습니다.'); location.href='login.php';</script>";
    exit;
}

// 삭제할 ID 배열 존재 여부 확인
if (!isset($_POST['delete_ids']) || !is_array($_POST['delete_ids'])) {
    echo "<script>alert('삭제할 회원을 선택하세요.'); history.back();</script>";
    exit;
}

$deleteIds = $_POST['delete_ids'];

// IN 절 생성
$placeholders = implode(',', array_fill(0, count($deleteIds), '?'));
$sql = "DELETE FROM tel WHERE idx IN ($placeholders)";
$stmt = $pdo->prepare($sql);

if ($stmt->execute($deleteIds)) {
    echo "<script>alert('선택한 회원이 삭제되었습니다.'); location.href='tel_member.php';</script>";
} else {
    echo "<script>alert('삭제 중 오류가 발생했습니다.'); history.back();</script>";
}
?>
