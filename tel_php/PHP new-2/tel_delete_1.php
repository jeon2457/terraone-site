<?php
session_start();
require './php/db-connect-pdo.php';

// 관리자 검증
if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 10) {
    echo "<script>alert('관리자만 접근할 수 있습니다.'); location.href='login.php';</script>";
    exit;
}

// 라디오 버튼은 edit_id 로 넘어옴
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

// [알림] tel_select.php에서 선택을 회원편집과 NEW관리방에서 각각 해당항목을 선택해서 삭제하는 코드가 다른데 tel_delete.php코드 한곳에서는 충돌로인해 한쪽에서는 삭제가 되지않는다. 그래서 코드분리를 해놓았다.
// tel_member.php ==> tel_delete.php
// tel_edit.php ==> tel_delete_1.php 




?>
