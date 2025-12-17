<?php
session_start();
require './php/auth_check.php';
require './php/db-connect-pdo.php';

// update_leaders_sms2.php가 있으면 불러오기
if (file_exists('./php/update_leaders_sms2.php')) {
    require './php/update_leaders_sms2.php';
}

if (!isset($_POST['idx'])) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

/* ------------------------
   POST 값 안전하게 수집
------------------------- */
$idx        = $_POST['idx'];
$id         = trim($_POST['id'] ?? '');
$name       = trim($_POST['name'] ?? '');
$tel        = trim($_POST['tel'] ?? '');
$addr       = trim($_POST['addr'] ?? '');
$remark     = trim($_POST['remark'] ?? '');
$sms        = trim($_POST['sms'] ?? '');
$sms_2      = trim($_POST['sms_2'] ?? '');
$user_level = trim($_POST['user_level'] ?? '');
$memo       = trim($_POST['memo'] ?? '');
$password   = trim($_POST['password'] ?? '');

try {
    $pdo->beginTransaction();

    /* -----------------------------------------
       회장 / 총무 판별
       stripos()는 대소문자/공백/문자 포함 모두 허용
    ------------------------------------------ */
    $is_leader =
        (stripos($remark, '회장') !== false) ||
        (stripos($remark, '총무') !== false);

    /* -----------------------------------------
       회장/총무일 경우 sms_2 자동 생성
       → 본인 번호 제외 + 공백 제거 + NULL 제외
    ------------------------------------------ */
    if ($is_leader) {

        $stmt_collect = $pdo->prepare("
            SELECT tel 
            FROM tel 
            WHERE tel != ? 
              AND tel IS NOT NULL 
              AND TRIM(tel) != '' 
            ORDER BY name ASC
        ");
        $stmt_collect->execute([$tel]);

        $all_tels = array_map('trim', $stmt_collect->fetchAll(PDO::FETCH_COLUMN));

        // 콤마로 연결
        $sms_2 = implode(',', $all_tels);

    } else {
        // 회장/총무가 아닐 때는 SMS_2 무조건 초기화
        $sms_2 = '';
    }

    /* -----------------------------------------
       비밀번호 변경 여부에 따라 UPDATE 분리
    ------------------------------------------ */
    if (!empty($password)) {
        // 비밀번호 암호화 후 업데이트
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            UPDATE tel SET 
                id = ?,
                password = ?,
                name = ?,
                tel = ?,
                addr = ?,
                remark = ?,
                sms = ?,
                sms_2 = ?,
                user_level = ?,
                memo = ?
            WHERE idx = ?
        ");

        $stmt->execute([
            $id, $hashedPassword, $name, $tel, $addr,
            $remark, $sms, $sms_2, $user_level, $memo, $idx
        ]);

    } else {
        // 비밀번호 제외 업데이트
        $stmt = $pdo->prepare("
            UPDATE tel SET 
                id = ?,
                name = ?,
                tel = ?,
                addr = ?,
                remark = ?,
                sms = ?,
                sms_2 = ?,
                user_level = ?,
                memo = ?
            WHERE idx = ?
        ");

        $stmt->execute([
            $id, $name, $tel, $addr,
            $remark, $sms, $sms_2, $user_level, $memo, $idx
        ]);
    }

    /* --------------------------------------------
       회장/총무가 설정된 경우
       updateLeadersSms2() 실행 → 전체 회장/총무 동기화
    --------------------------------------------- */
    if ($is_leader && function_exists('updateLeadersSms2')) {
        updateLeadersSms2($pdo);
    }

    $pdo->commit();

    echo "<script>alert('회원정보가 업데이트 되었습니다.'); location.href='tel_edit.php';</script>";
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    die("수정 오류: " . $e->getMessage());
}
?>
