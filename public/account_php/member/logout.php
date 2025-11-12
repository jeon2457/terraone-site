<?php
// logout.php - 로그아웃 처리
session_start();

// 세션 데이터 삭제
$_SESSION = array();

// 세션 쿠키 삭제
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// 세션 파괴
session_destroy();

// 로그인 페이지로 리다이렉트
echo "<script>
    alert('로그아웃 되었습니다.');
    location.href='./login_form.php';
</script>";
?>