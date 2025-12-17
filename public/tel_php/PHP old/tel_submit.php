<?php
// tel_submit.php

// ✅ 관리자 인증
require './php/auth_check.php';

// ✅ PDO DB 연결
require './php/db-connect-pdo.php';

// POST 데이터 받기
$id = trim($_POST['id'] ?? '');
$password = trim($_POST['password'] ?? '');
$password2 = trim($_POST['password2'] ?? '');
$name = trim($_POST['name'] ?? '');
$tel = trim($_POST['tel'] ?? '');
$addr = trim($_POST['addr'] ?? '');
$remark = trim($_POST['remark'] ?? '');
$sms = trim($_POST['sms'] ?? '');
$sms_2 = trim($_POST['sms_2'] ?? '');
$user_level = intval($_POST['user_level'] ?? 1);

// 입력값 검증
if (empty($id) || empty($password) || empty($name) || empty($tel)) {
    echo "<script>alert('필수 입력값이 누락되었습니다.'); history.back();</script>";
    exit;
}

if ($password !== $password2) {
    echo "<script>alert('비밀번호가 일치하지 않습니다.'); history.back();</script>";
    exit;
}

// 아이디 중복 확인
$sql = "SELECT COUNT(*) AS cnt FROM tel WHERE id = ?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($row['cnt'] > 0) {
    echo "<script>alert('이미 사용 중인 아이디입니다.'); history.back();</script>";
    exit;
}
mysqli_stmt_close($stmt);

// 비밀번호 암호화
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 새 회원 등록
$sql = "INSERT INTO tel (id, password, name, tel, addr, remark, sms, sms_2, user_level)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "ssssssssi", $id, $hashed_password, $name, $tel, $addr, $remark, $sms, $sms_2, $user_level);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>
            alert('회원 정보가 성공적으로 등록되었습니다!');
            location.href='tel_view.php';
          </script>";
} else {
    echo "데이터 저장 오류: " . mysqli_error($connect);
}

mysqli_stmt_close($stmt);
mysqli_close($connect);
?>
