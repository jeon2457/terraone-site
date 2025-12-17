<?php
// tel_submit.php (PDO)
session_start();
require './php/auth_check.php';
require './php/db-connect-pdo.php'; // PDO 연결

// 관리자 확인
// if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level']) || intval($_SESSION['user_level']) < 10) {
//     echo "<script>alert('관리자만 접근할 수 있습니다.'); location.href='./login.php';</script>";
//     exit;
// }

// POST 방식 체크
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// 입력값 수집
$id        = trim($_POST['id'] ?? '');
$password  = trim($_POST['password'] ?? '');
$password2 = trim($_POST['password2'] ?? '');
$name      = trim($_POST['name'] ?? '');
$tel       = trim($_POST['tel'] ?? '');
$addr      = trim($_POST['addr'] ?? '');
$remark    = trim($_POST['remark'] ?? '');
$sms       = trim($_POST['sms'] ?? '');
$sms_2     = trim($_POST['sms_2'] ?? '');
$user_level = intval($_POST['user_level'] ?? 1);   // ★ 수정됨!

// 필수 체크
if ($id === '' || $password === '' || $password2 === '' || $name === '' || $tel === '' || $sms === '') {
    echo "<script>alert('필수 항목을 모두 입력해주세요.'); history.back();</script>";
    exit;
}
if ($password !== $password2) {
    echo "<script>alert('비밀번호가 일치하지 않습니다.'); history.back();</script>";
    exit;
}

// 아이디 중복 확인 (로그인용 id가 tel 테이블에 존재하는지 검사)
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tel WHERE id = ?");
    $stmt->execute([$id]);
    $cnt = (int)$stmt->fetchColumn();
    if ($cnt > 0) {
        echo "<script>alert('이미 사용중인 아이디입니다. 다른 아이디를 선택하세요.'); history.back();</script>";
        exit;
    }
} catch (Exception $e) {
    echo "DB 오류: " . htmlspecialchars($e->getMessage());
    exit;
}

// 비밀번호 해시
$pw_hash = password_hash($password, PASSWORD_DEFAULT);

// INSERT 실행
try {
    $sql = "INSERT INTO tel (id, password, name, tel, addr, remark, sms, sms_2, user_level)
            VALUES (:id, :password, :name, :tel, :addr, :remark, :sms, :sms_2, :user_level)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id'         => $id,
        ':password'   => $pw_hash,
        ':name'       => $name,
        ':tel'        => $tel,
        ':addr'       => $addr,
        ':remark'     => $remark,
        ':sms'        => $sms,
        ':sms_2'      => $sms_2,
        ':user_level' => $user_level   // ★ 수정됨!
    ]);

    echo "<script>alert('데이터가 저장되었습니다.'); location.href='tel_view.php';</script>";
    exit;

} catch (Exception $e) {
    echo "DB 저장 오류: " . htmlspecialchars($e->getMessage());
    exit;
}
?>
