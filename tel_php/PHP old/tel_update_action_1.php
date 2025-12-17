<?php
session_start();
// ✅ 관리자 인증
require './php/auth_check.php';
// ✅ PDO DB 연결
require './php/db-connect-pdo.php';
// ✅ SMS_2 자동 업데이트 함수
require './php/update_leaders_sms2.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

$idx = $_POST['idx'] ?? '';
$id = $_POST['id'] ?? '';
$name = $_POST['name'] ?? '';
$tel = $_POST['tel'] ?? '';
$addr = $_POST['addr'] ?? '';
$remark = $_POST['remark'] ?? '';
$sms = $_POST['sms'] ?? '';
$sms_2 = $_POST['sms_2'] ?? '';
$user_level = $_POST['user_level'] ?? '';
$password = trim($_POST['password'] ?? '');

// 필수 항목 검증
if (empty($idx) || empty($name) || empty($tel)) {
    echo "<script>alert('필수 항목을 입력하세요.'); history.back();</script>";
    exit;
}

try {
    $pdo->beginTransaction();
    
    // ✅ 비고에 "회장" 또는 "총무"가 포함되어 있는지 확인
    $is_leader = (stripos($remark, '회장') !== false || stripos($remark, '총무') !== false);
    
    if ($is_leader) {
        // 회장 또는 총무인 경우: SMS_2 자동생성
        // 현재 회원을 제외한 모든 전화번호 가져오기
        $stmt_phones = $pdo->prepare("SELECT tel FROM tel WHERE idx != ? AND tel != '' ORDER BY name ASC");
        $stmt_phones->execute([$idx]);
        
        $phones = [];
        while ($row = $stmt_phones->fetch(PDO::FETCH_ASSOC)) {
            $phones[] = $row['tel'];
        }
        
        // 자동생성된 전화번호 목록으로 덮어쓰기
        $sms_2 = implode(',', $phones);
    } else {
        // ✅ 회장/총무가 아닌 경우: SMS_2 자동 삭제
        $sms_2 = '';
    }
    
    // 비밀번호 변경 여부 확인
    if (!empty($password)) {
        // 비밀번호 변경하는 경우
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
                user_level = ?
            WHERE idx = ?
        ");
        
        $result = $stmt->execute([
            $id,
            $hashedPassword,
            $name,
            $tel,
            $addr,
            $remark,
            $sms,
            $sms_2,
            $user_level,
            $idx
        ]);
    } else {
        // 비밀번호 변경하지 않는 경우
        $stmt = $pdo->prepare("
            UPDATE tel SET 
                id = ?,
                name = ?,
                tel = ?,
                addr = ?,
                remark = ?,
                sms = ?,
                sms_2 = ?,
                user_level = ?
            WHERE idx = ?
        ");
        
        $result = $stmt->execute([
            $id,
            $name,
            $tel,
            $addr,
            $remark,
            $sms,
            $sms_2,
            $user_level,
            $idx
        ]);
    }
    
    if ($result) {
        // ✅ 모든 회장/총무 SMS_2 자동 업데이트
        updateLeadersSms2($pdo);
        
        $pdo->commit();
        
        echo "<script>
            alert('회원정보가 수정되었습니다.');
            location.href = 'tel_edit.php';
        </script>";
    } else {
        $pdo->rollBack();
        echo "<script>
            alert('수정에 실패했습니다.');
            history.back();
        </script>";
    }
    
} catch (Exception $e) {
    $pdo->rollBack();
    echo "<script>
        alert('오류가 발생했습니다: " . addslashes($e->getMessage()) . "');
        history.back();
    </script>";
}
?>