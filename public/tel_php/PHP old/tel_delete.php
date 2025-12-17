<?php
session_start();
require './php/auth_check.php';
require './php/db-connect-pdo.php';

// ✅ SMS_2 자동 업데이트 함수
require './php/update_leaders_sms2.php';

if (!isset($_POST['edit_id']) || empty($_POST['edit_id'])) {
    echo "<script>
            alert('삭제할 회원을 선택하세요.');
            history.back();
          </script>";
    exit;
}

$idx = $_POST['edit_id'];

try {
    // 회원 삭제
    $stmt = $pdo->prepare("DELETE FROM tel WHERE idx = ?");
    $stmt->execute([$idx]);

    // ✅ 모든 회장/총무 SMS_2 자동 업데이트
    updateLeadersSms2($pdo);

    echo "<script>
            alert('회원이 삭제되었습니다.');
            location.href='tel_edit.php';
          </script>";
    exit;

} catch (PDOException $e) {
    echo "<script>
            alert('삭제 중 오류가 발생했습니다: " . addslashes($e->getMessage()) . "');
            history.back();
          </script>";
    exit;
}
?>