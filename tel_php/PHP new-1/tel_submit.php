<?php
// tel_submit.php (PDO)
session_start();
require './php/auth_check.php';
require './php/db-connect-pdo.php';

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
$sms_2     = trim($_POST['sms_2'] ?? '');   // 실시간 값도 받아옴
$user_level = intval($_POST['user_level'] ?? 1);

// 필수 체크
if ($id === '' || $password === '' || $password2 === '' || $name === '' || $tel === '' || $sms === '') {
    echo "<script>alert('필수 항목을 모두 입력해주세요.'); history.back();</script>";
    exit;
}
if ($password !== $password2) {
    echo "<script>alert('비밀번호가 일치하지 않습니다.'); history.back();</script>";
    exit;
}

// 아이디 중복 확인
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tel WHERE id = ?");
    $stmt->execute([$id]);
    if ((int)$stmt->fetchColumn() > 0) {
        echo "<script>alert('이미 사용중인 아이디입니다.'); history.back();</script>";
        exit;
    }
} catch (Exception $e) {
    echo "DB 오류: " . htmlspecialchars($e->getMessage());
    exit;
}

// 비밀번호 해시
$pw_hash = password_hash($password, PASSWORD_DEFAULT);


/* -----------------------------------------------------------
   🔵 회장/총무일 경우 자동 SMS-2 (db저장용, 최종 계산 버전)
   ----------------------------------------------------------- */
$auto_sms2 = "";

if ($remark === "회장" || $remark === "총무") {

    try {
        // 실시간 계산된 값 무시하고, DB 기준으로 다시 생성
        $stmt = $pdo->prepare("SELECT tel FROM tel WHERE tel != ? AND tel != '' ");
        $stmt->execute([$tel]);

        $numbers = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $numbers[] = $row['tel'];
        }

        $auto_sms2 = implode(",", $numbers);

    } catch (Exception $e) {
        echo "SMS-2 자동 생성 오류: " . htmlspecialchars($e->getMessage());
        exit;
    }
}

// 🔵 최종 저장값 선택 (두 방법 모두 적용)
if ($auto_sms2 !== "") {
    // ② DB 기준 생성값 우선 적용
    $sms_2 = $auto_sms2;
}
// 실시간(sms_2) → DB저장용 자동생성이 없을 경우 그대로 저장됨
// 즉, 두 방식 모두 반영됨


/* -----------------------------------------------------------
   🔵 INSERT 실행
   ----------------------------------------------------------- */
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
        ':sms_2'      => $sms_2,   // 실시간 + DB자동 계산 모두 반영됨
        ':user_level' => $user_level
    ]);

    echo "<script>alert('전송이 성공적으로 이루어졌습니다.'); location.href='tel_view.php';</script>";
    exit;

} catch (Exception $e) {
    echo "DB 저장 오류: " . htmlspecialchars($e->getMessage());
    exit;
}
?>
