<?php
session_start();

require './db-connect-pdo.php';

// 입력 수집
$id = $_POST['id'] ?? '';
$password_input = $_POST['password'] ?? '';

if (!$id || !$password_input) {
    echo "<script>alert('아이디와 비밀번호를 모두 입력해주세요.'); history.back();</script>";
    exit;
}

// 사용자 조회
try {
    $stmt = $pdo->prepare("SELECT * FROM tel WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password_input, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_level'] = $user['user_level'];   // ✔ 변경됨
        $_SESSION['user_idx'] = $user['idx'];

        // 원래 가려던 페이지로 이동
        if (isset($_SESSION['redirect_url'])) {
            $go = $_SESSION['redirect_url'];
            unset($_SESSION['redirect_url']);
            echo "<script>location.href='{$go}';</script>";
            exit;
        }

        // 기본 페이지
        echo "<script>location.href='../select.php';</script>";
        exit;

    } else {
        echo "<script>alert('아이디 또는 비밀번호가 일치하지 않습니다.'); history.back();</script>";
        exit;
    }

} catch (PDOException $e) {
    die("로그인 처리 중 오류 발생: " . $e->getMessage());
}
?>