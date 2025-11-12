<?php

// auth_check.php - 관리자 인증 체크 공통 파일

// 세션 시작 (이미 시작되었는지 확인)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 로그인 여부 확인
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // 로그인하지 않은 경우
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI']; // 현재 페이지 URL 저장
    
    echo "<script>
        alert('로그인이 필요합니다.');
        location.href='../member/login_form.php';
    </script>";
    exit;
}

// 관리자 권한 확인 (level 10 이상)
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] < 10) {
    echo "<script>
        alert('접근 권한이 없습니다. 관리자만 이용 가능합니다.');
        history.back();
    </script>";
    exit;
}

// 디버깅용 (테스트 후 삭제 가능)
// echo "<!-- 인증 통과: " . $_SESSION['user_name'] . " (Level: " . $_SESSION['user_level'] . ") -->";
?>