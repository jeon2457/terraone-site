<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// 🔥[중요!] 인증(세션) 관련 코드는 반드시 HTML 출력보다 먼저 실행해야 합니다. <?php 코드는 무조건 1행에 공백없이 제일앞에 와야함!

// 로그인 여부 확인
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    echo "<script>alert('로그인이 필요합니다.'); location.href='./login.php';</script>";
    exit;
}

// 관리자 권한 체크
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] < 10) {
    echo "<script>alert('접근 권한이 없습니다. 관리자만 이용 가능합니다.'); history.back();</script>";
    exit;
}

?>