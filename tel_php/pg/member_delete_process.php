<?php
// DB와 Member 클래스 포함
include '../php/db-connect.php';
include '../php/member.php';

// URL을 통해 넘어온 idx 값을 받습니다.
$idx = $_GET['idx'] ?? 0;
$idx = intval($idx); // 정수형으로 변환

$msg = '잘못된 접근입니다.';
$url = '../member/member_delete.php'; // 삭제 페이지 경로 (수정 필요시 수정)

if ($idx > 0) {
    try {
        $mem = new Member($db);
        $mem->member_del($idx); // member.php에 이미 만들어두신 member_del 함수 호출
        
        $msg = '회원 정보가 삭제되었습니다.';
        
    } catch (PDOException $e) {
        $msg = '삭제 중 오류가 발생했습니다: ' . $e->getMessage();
    }
}

// 알림창을 띄우고 이전 페이지(목록)로 리디렉션
echo "
<script>
    alert('{$msg}');
    self.location.href='{$url}';
</script>
";
exit;
?>